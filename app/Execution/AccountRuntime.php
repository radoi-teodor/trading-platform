<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Data\EngineCommand;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\FeeSchedule;
use App\Execution\Journal\DatabaseDecisionLog;
use App\Execution\Models\Account;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\Deployment;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Market\Instruments\StoredInstruments;
use App\Market\Models\Instrument;
use App\Portfolio\Ledger;
use App\Risk\RiskPolicy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use BackedEnum;
use Carbon\CarbonImmutable;
use Closure;
use InvalidArgumentException;
use LogicException;
use Stringable;

/**
 * One account's engine as paper (and, with its venue, testnet) runs it (docs/engine.md, section 9): its deployed
 * strategies compiled from their versions, the simulated venue with the backtest's costs, its ledger and journal. A
 * minute goes through the same steps as in a backtest (D-8): the venue advances, then the engine handles it.
 */
final class AccountRuntime
{
    /** @var list<Symbol> */
    public readonly array $symbols;

    public readonly CarbonImmutable $feedFrom;

    private readonly Engine $engine;

    private readonly SimClock $venueClock;

    private readonly SimulatedVenue $venue;

    public readonly DatabaseDecisionLog $log;

    /**
     * @param  array<array-key, mixed>  $risk  the `account` section of config/risk.php
     * @param  array<array-key, mixed>  $simulation  config/simulation.php
     * @param  (Closure(EngineRecord): void)|null  $onEvent  called for every new engine event and notice
     */
    public function __construct(public readonly Account $account, array $risk, array $simulation, private readonly Clock $clock, ?Closure $onEvent = null)
    {
        if ($account->mode !== EngineMode::Paper) {
            throw new InvalidArgumentException("{$account->name} is a {$account->mode->value} account; this runtime runs paper accounts (testnet comes with its venue, live with phase 6).");
        }

        $policy = RiskPolicy::account($risk);
        $strategies = $this->strategies($account, $policy);
        $symbols = [];

        foreach ($account->symbols as $symbol) {
            $symbols[$symbol] = Symbol::parse($symbol);
        }

        $feedFrom = $account->started_at;

        foreach ($strategies as $strategy) {
            $seconds = 60;

            foreach ($strategy->subscriptions() as $subscription) {
                $symbols[(string) $subscription->symbol] ??= $subscription->symbol;
                $seconds = max($seconds, $subscription->timeframe->seconds());
            }

            $start = $account->started_at->subSeconds($seconds * $strategy->warmupBars())->startOfDay();
            $feedFrom = $start < $feedFrom ? $start : $feedFrom;
        }

        ksort($symbols, SORT_STRING);
        $this->symbols = array_values($symbols);
        $this->feedFrom = $feedFrom;
        $rules = array_map(fn (Symbol $symbol): RulesHistory => self::instrument($symbol)->rulesHistory(), $symbols);
        $quote = Asset::of($account->quote);
        $spot = Money::of($account->spot_balance, $quote);
        $futures = Decimal::of($account->futures_balance);
        $fees = self::section($simulation, 'fees');
        $costs = self::section($simulation, 'cost_model');
        $perpetual = self::section($simulation, 'perpetual');
        $perpetualFees = self::section($perpetual, 'fees');
        $maintenance = Decimal::of(self::text($perpetual, 'maintenance_margin_rate'));
        $this->venueClock = new SimClock($feedFrom);
        $this->venue = new SimulatedVenue(
            $this->venueClock,
            new FeeSchedule(Decimal::of(self::text($fees, 'maker')), Decimal::of(self::text($fees, 'taker'))),
            new CostModel(
                is_int($costs['version'] ?? null) ? $costs['version'] : 0, Decimal::of(self::text($costs, 'half_spread')),
                Decimal::of(self::text($costs, 'impact')), Decimal::of(self::text($costs, 'limit_volume_share')),
            ),
            $rules,
            [$spot],
            new FeeSchedule(Decimal::of(self::text($perpetualFees, 'maker')), Decimal::of(self::text($perpetualFees, 'taker'))),
            $maintenance,
            $futures,
        );
        $this->log = new DatabaseDecisionLog($account->id, $clock, $onEvent);
        $this->engine = new Engine(
            EngineMode::Paper, $this->venue, new Ledger($quote, [$spot], $futures), $policy, $this->log, $strategies, $rules,
            $account->started_at, $feedFrom, $maintenance,
        );
    }

    /**
     * Handles one minute: the venue matches its orders, then the engine runs its loop with the commands.
     *
     * @param  list<EngineCommand>  $commands
     */
    public function step(MinuteCandles $minute, array $commands = []): void
    {
        $this->venueClock->advanceTo($minute->closedAt);
        $this->venue->advance($minute);
        $this->engine->handle($minute, $commands);
    }

    /** Writes what the UI reads (D-70). */
    public function publish(CarbonImmutable $minute): void
    {
        $payload = self::plain($this->engine->snapshot());
        AccountSnapshot::query()->updateOrCreate(['account_id' => $this->account->id], [
            'minute' => $minute, 'quiet_decisions' => $this->log->quietDecisions, 'payload' => is_array($payload) ? $payload : [], 'updated_at' => $this->clock->now(),
        ]);
    }

    /** @return list<Strategy> the account's active deployments, compiled from their versions with their parameters */
    private function strategies(Account $account, RiskPolicy $policy): array
    {
        $limits = new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions);
        $strategies = [];

        foreach (Deployment::query()->where('account_id', $account->id)->where('active', true)->orderBy('id')->with('version')->get() as $deployment) {
            $version = $deployment->version ?? throw new LogicException("Deployment {$deployment->id} has no version.");
            $result = DocumentChecker::check($version->document, new StoredInstruments, $limits);
            $spec = $result->spec ?? throw new InvalidArgumentException("{$version->label()} no longer compiles: ".implode(' ', array_map(fn ($e): string => $e->getMessage(), $result->errors)));
            $strategies[] = CompiledStrategy::fromSpec($spec, $deployment->parameters, $version->version);
        }

        return $strategies;
    }

    private static function instrument(Symbol $symbol): Instrument
    {
        return Instrument::query()->where('market', $symbol->isPerpetual() ? 'perp' : 'spot')->where('symbol', (string) $symbol)->first()
            ?? throw new InvalidArgumentException("{$symbol} is not a stored instrument; run market:instruments first.");
    }

    /** Decimals, times and enums as text, recursively, for a JSON column. */
    private static function plain(mixed $value): mixed
    {
        return match (true) {
            is_array($value) => array_map(self::plain(...), $value),
            $value instanceof CarbonImmutable => $value->toIso8601ZuluString(),
            $value instanceof BackedEnum => $value->value,
            $value instanceof Stringable => (string) $value,
            default => $value,
        };
    }

    /**
     * @param  array<array-key, mixed>  $config
     * @return array<array-key, mixed>
     */
    private static function section(array $config, string $key): array
    {
        return is_array($config[$key] ?? null) ? $config[$key] : throw new InvalidArgumentException("Configuration {$key} is missing.");
    }

    /** @param array<array-key, mixed> $config */
    private static function text(array $config, string $key): string
    {
        return is_string($config[$key] ?? null) ? $config[$key] : throw new InvalidArgumentException("Configuration {$key} must be text.");
    }
}
