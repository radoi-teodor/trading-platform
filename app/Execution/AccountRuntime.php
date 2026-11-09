<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Contracts\Venue;
use App\Execution\Data\Balance;
use App\Execution\Data\EngineCommand;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\FeeSchedule;
use App\Execution\Deployments\SizedStrategy;
use App\Execution\Journal\DatabaseDecisionLog;
use App\Execution\Models\Account;
use App\Execution\Models\AccountEquity;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\Deployment;
use App\Execution\Venues\CostModel;
use App\Execution\Venues\SimulatedVenue;
use App\Execution\Venues\WarmingVenue;
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
    /** Live micro's cap on a position's notional, as a share of equity (docs/risk-policy.md, section 6). */
    public const string MICRO_NOTIONAL = '0.05';

    /** @var list<Symbol> */
    public readonly array $symbols;

    public readonly CarbonImmutable $feedFrom;

    private readonly Engine $engine;

    private readonly SimClock $venueClock;

    private readonly ?SimulatedVenue $venue;

    private readonly ?WarmingVenue $exchange;

    public readonly CarbonImmutable $tradingFrom;

    public readonly DatabaseDecisionLog $log;

    /** @var array<string, string> equity at each hour closed since the last publish, replay included (D-76) */
    private array $hourlyEquity = [];

    /** @var array<string, string> each strategy's label by its identity key */
    private array $labels = [];

    /** @var array<string, list<string>> the stores of data for scalping each perpetual's strategies read (D-79), by symbol */
    private array $scalping = [];

    /** Round trips already written to `account_trades` by this process. */
    private int $publishedTrips = 0;

    /**
     * @param  array<array-key, mixed>  $risk  the `account` section of config/risk.php
     * @param  array<array-key, mixed>  $simulation  config/simulation.php
     * @param  (Closure(EngineRecord): void)|null  $onEvent  called for every new engine event and notice
     * @param  (Closure(list<Symbol>): Venue)|null  $exchange  builds a testnet account's venue for its instruments
     */
    public function __construct(public readonly Account $account, array $risk, array $simulation, private readonly Clock $clock, ?Closure $onEvent = null, ?Closure $exchange = null)
    {
        if ($account->mode === EngineMode::Backtest || ($account->mode !== EngineMode::Paper && $exchange === null)) {
            throw new InvalidArgumentException("{$account->name} is a {$account->mode->value} account: this runtime runs paper, and testnet and live with their venue (D-82).");
        }

        $policy = RiskPolicy::account($risk);
        $strategies = $this->strategies($account, $policy);

        foreach ($strategies as $strategy) {
            $this->labels[$strategy->identity()->key()] = $strategy->identity()->label();

            foreach ($strategy instanceof CompiledStrategy ? $strategy->scalpingData() : [] as $table) {
                foreach ($strategy->subscriptions() as $subscription) {
                    if ($subscription->symbol->isPerpetual()) {
                        $this->scalping[(string) $subscription->symbol][] = $table;
                    }
                }
            }
        }
        $symbols = [];

        foreach ($account->symbols as $symbol) {
            $symbols[$symbol] = Symbol::parse($symbol);
        }

        // An exchange account starts from the exchange as it is now: it trades from this minute, after its warmup.
        $onExchange = $account->mode === EngineMode::Testnet || $account->mode === EngineMode::Live;
        $tradingFrom = $onExchange ? max($account->started_at, $clock->now()->startOfMinute()) : $account->started_at;
        $feedFrom = $tradingFrom;

        foreach ($strategies as $strategy) {
            $seconds = 60;

            foreach ($strategy->subscriptions() as $subscription) {
                $symbols[(string) $subscription->symbol] ??= $subscription->symbol;
                $seconds = max($seconds, $subscription->timeframe->seconds());
            }

            $start = $tradingFrom->subSeconds($seconds * $strategy->warmupBars())->startOfDay();
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
        $this->tradingFrom = $tradingFrom;
        $this->venueClock = new SimClock($feedFrom);

        if ($exchange !== null && $onExchange) {
            $this->venue = null;
            $this->exchange = new WarmingVenue($exchange($this->symbols));
            $balances = array_values(array_map(fn (Balance $b): Money => Money::of((string) $b->total(), $b->asset), $this->exchange->balances()));
            $this->log = new DatabaseDecisionLog($account->id, $clock, $onEvent, replay: false);
            $this->engine = new Engine(
                $account->mode, $this->exchange, new Ledger($quote, $balances, $this->exchange->futuresWallet()), $policy, $this->log, $strategies, $rules,
                $tradingFrom, $feedFrom, $maintenance,
            );

            return;
        }

        $this->exchange = null;
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
        if ($this->exchange !== null) {
            if ($minute->closedAt > $this->tradingFrom) {
                $this->exchange->warmedUp();
            }

            $this->engine->handle($minute, $commands);
            $this->recordHour($minute->closedAt);

            return;
        }

        $this->venueClock->advanceTo($minute->closedAt);
        $this->venue?->advance($minute);
        $this->engine->handle($minute, $commands);
        $this->recordHour($minute->closedAt);
    }

    /**
     * Whether the minute carries the data for scalping its strategies read (D-84): the flow, mark and premium of the
     * minute, and at each 5-minute mark the open interest that became known. Without it the engine waits, so a replay
     * reads what the live engine read.
     */
    public function hasScalpingData(MinuteCandles $minute): bool
    {
        foreach ($this->scalping as $symbol => $tables) {
            foreach (array_unique($tables) as $table) {
                $present = match ($table) {
                    'trade_flow' => isset($minute->flow[$symbol]),
                    'mark_price_minutes' => isset($minute->marks[$symbol]),
                    'premium_index_minutes' => isset($minute->premium[$symbol]),
                    'open_interest' => $minute->closedAt->getTimestamp() % 300 !== 0 || isset($minute->openInterest[$symbol]),
                    default => true,
                };

                if (! $present) {
                    return false;
                }
            }
        }

        return true;
    }

    /** Writes what the UI reads (D-70): the snapshot, the trades closed since the last publish and the hours' equity (D-76). */
    public function publish(CarbonImmutable $minute): void
    {
        $payload = self::plain($this->engine->snapshot());
        AccountSnapshot::query()->updateOrCreate(['account_id' => $this->account->id], [
            'minute' => $minute, 'quiet_decisions' => $this->log->quietDecisions, 'payload' => is_array($payload) ? $payload : [], 'updated_at' => $this->clock->now(),
        ]);

        $trips = $this->engine->ledger()->roundTrips();
        $rows = [];

        foreach (array_slice($trips, $this->publishedTrips, preserve_keys: true) as $seq => $trip) {
            $rows[] = [
                'account_id' => $this->account->id, 'seq' => $seq, 'strategy' => $trip->strategy, 'label' => $this->labels[$trip->strategy] ?? $trip->strategy, 'symbol' => (string) $trip->symbol,
                'side' => $trip->side->value, 'opened_at' => $trip->openedAt, 'closed_at' => $trip->closedAt, 'quantity' => (string) $trip->quantity,
                'entry_price' => (string) $trip->entryPrice, 'exit_price' => $trip->exitPrice === null ? null : (string) $trip->exitPrice,
                'gross' => (string) $trip->grossProfit, 'fees' => (string) $trip->fees, 'slippage' => (string) $trip->slippage,
                'funding' => (string) ($trip->funding ?? Decimal::zero()), 'net' => (string) $trip->netProfit,
                'r_multiple' => $trip->rMultiple === null ? null : (string) $trip->rMultiple, 'exit_reason' => $trip->exitReason?->value,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            AccountTrade::query()->upsert($chunk, ['account_id', 'seq'], array_keys(array_diff_key($chunk[0], ['account_id' => 0, 'seq' => 0])));
        }

        $this->publishedTrips = count($trips);
        $points = array_map(fn (string $at, string $equity): array => ['account_id' => $this->account->id, 'at' => $at, 'equity' => $equity], array_keys($this->hourlyEquity), $this->hourlyEquity);

        foreach (array_chunk($points, 500) as $chunk) {
            AccountEquity::query()->upsert($chunk, ['account_id', 'at'], ['equity']);
        }

        $this->hourlyEquity = [];
    }

    private function recordHour(CarbonImmutable $closedAt): void
    {
        // From the account's first minute of trading: the hour it starts at is the baseline of every later change.
        if ($closedAt->minute === 0 && $closedAt->second === 0 && $closedAt >= $this->tradingFrom) {
            $this->hourlyEquity[$closedAt->toIso8601ZuluString()] = (string) $this->engine->ledger()->equity($this->engine->marks());
        }
    }

    /** @return list<Strategy> the account's active deployments, compiled from their versions with their parameters */
    private function strategies(Account $account, RiskPolicy $policy): array
    {
        $limits = new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions);
        $strategies = [];

        foreach (Deployment::inForce($account->id, $this->clock->now())->orderBy('id')->with('version')->get() as $deployment) {
            $version = $deployment->version ?? throw new LogicException("Deployment {$deployment->id} has no version.");
            $result = DocumentChecker::check($version->document, new StoredInstruments, $limits);
            $spec = $result->spec ?? throw new InvalidArgumentException("{$version->label()} no longer compiles: ".implode(' ', array_map(fn ($e): string => $e->getMessage(), $result->errors)));
            $strategy = CompiledStrategy::fromSpec($spec, $deployment->parameters, $version->version);
            $multiplier = Decimal::of($deployment->size_multiplier);
            // A step of the ladder below full size, or live micro's notional cap, tightens the strategy's limits (D-83).
            $cap = $deployment->stage === 'live_micro' ? Decimal::of(self::MICRO_NOTIONAL) : null;
            $strategies[] = $multiplier->equals(Decimal::of(1)) && $cap === null ? $strategy : new SizedStrategy($strategy, $multiplier, $cap, $policy);
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
