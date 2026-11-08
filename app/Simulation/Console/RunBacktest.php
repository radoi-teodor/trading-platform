<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\MarketType;
use App\Market\Data\RulesHistory;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Market\Quality\QualityCheck;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Models\BacktestRun;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Report\Report;
use App\Simulation\Report\ReportBuilder;
use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;
use RuntimeException;

/** Runs a hand-coded strategy over stored history with its benchmarks and stores the report (docs/simulation.md). */
final class RunBacktest extends Command
{
    protected $signature = 'backtest:run
        {strategy : A strategy name from config/simulation.php}
        {--symbol=* : Unified symbols, such as BTC/USDT (default BTC/USDT)}
        {--from= : First day of the window, YYYY-MM-DD}
        {--to= : Day after the last one, YYYY-MM-DD}
        {--param=* : Strategy parameters as key=value}
        {--equity= : Initial quote balance (default from config/simulation.php)}
        {--no-benchmarks : Skip the comparisons}';

    protected $description = 'Backtest a strategy on stored 1-minute candles and store its report';

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, Clock $clock, ConnectionInterface $db): int
    {
        $started = $clock->now();

        try {
            [$name, $class, $profile] = $this->registered($config, $this->stringArgument('strategy'));
            $symbols = array_map(fn (string $s): Symbol => Symbol::parse($s), $this->strings('symbol') ?: ['BTC/USDT']);
            $from = $this->day('from');
            $to = $this->day('to');
            $strategy = $class::build($this->parameters($class::defaults()), $symbols);
            // The run is over what the strategy follows: a carry given its perpetual also trades the spot (D-49).
            $symbols = self::followed($strategy);
            $rules = $this->rules($symbols);
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        if ($to <= $from) {
            $this->error('--to must be after --from.');

            return self::FAILURE;
        }

        $warmupFrom = self::warmupFrom($strategy, $from);

        foreach ($symbols as $symbol) {
            $problem = $this->coverage($quality, $symbol, $warmupFrom, $to) ?? $this->fundingCoverage($funding, $symbol, $warmupFrom, $to);

            if ($problem !== null) {
                $this->error("{$symbol}: {$problem}. Backfill it before backtesting (market:backfill).");

                return self::FAILURE;
            }
        }

        $policy = $profile === 'benchmark' ? RiskPolicy::benchmark() : RiskPolicy::account($this->section($config, 'risk.account'));
        $quote = Asset::of($this->text($config, 'simulation.quote'));
        $equity = Money::of(is_string($this->option('equity')) ? $this->option('equity') : $this->text($config, 'simulation.initial_equity'), $quote);
        [$spotBalance, $futuresBalance] = $this->wallets($config, $symbols, $equity);
        [$benchmarks, $missing] = $this->option('no-benchmarks')
            ? [[], []]
            : $this->benchmarks($config, $quality, $symbols, $from, $to, $rules, $futuresBalance === null ? null : $equity);
        $costs = $this->section($config, 'simulation.cost_model');
        $fees = $this->section($config, 'simulation.fees');
        $perpetual = $this->section($config, 'simulation.perpetual');
        $perpetualFees = is_array($perpetual['fees'] ?? null) ? $perpetual['fees'] : [];
        $definition = new RunDefinition(
            strategy: $strategy->identity(),
            market: $futuresBalance === null ? MarketType::Spot : MarketType::Perp,
            symbols: $symbols,
            from: $from,
            to: $to,
            warmupFrom: $warmupFrom,
            initialBalance: $spotBalance,
            fees: new FeeSchedule(self::decimal($fees, 'maker'), self::decimal($fees, 'taker')),
            costs: new CostModel(is_int($costs['version'] ?? null) ? $costs['version'] : 0, self::decimal($costs, 'half_spread'), self::decimal($costs, 'impact'), self::decimal($costs, 'limit_volume_share')),
            policy: $policy,
            benchmarks: [...array_map(fn (Participant $p): string => $p->label, $benchmarks), ...array_keys($missing)],
            futuresBalance: $futuresBalance,
            perpetualFees: $futuresBalance === null ? null : new FeeSchedule(self::decimal($perpetualFees, 'maker'), self::decimal($perpetualFees, 'taker')),
            maintenanceRate: $futuresBalance === null ? null : self::decimal($perpetual, 'maintenance_margin_rate'),
        );

        $this->info("Backtesting {$strategy->identity()->label()} ({$name}) on ".implode(', ', array_map('strval', $symbols))." from {$from->toDateString()} to {$to->toDateString()}...");
        $result = (new Backtest(new DatabaseCandleFeed($db)))->run($definition, new Participant('strategy', $strategy, $policy, $warmupFrom), $rules, $benchmarks);
        $report = ReportBuilder::build($definition, $result, $rules, $missing);
        $hash = $report->hash();
        $artifact = $this->store($this->text($config, 'simulation.artifacts'), $hash, $report->json());
        $finished = $clock->now();
        $metrics = $report->metrics;

        BacktestRun::query()->create([
            'strategy' => $strategy->identity()->label(),
            'strategy_key' => $strategy->identity()->key(),
            'definition' => json_decode(CanonicalJson::encode($definition->toArray()), true, flags: JSON_THROW_ON_ERROR),
            'definition_hash' => $definition->hash(),
            'report_hash' => $hash,
            'data_fingerprint' => $result->fingerprint,
            'window_from' => $from,
            'window_to' => $to,
            'net_return' => (string) $metrics->return,
            'max_drawdown' => (string) $metrics->maxDrawdown,
            'sharpe' => $metrics->sharpe === null ? null : (string) $metrics->sharpe,
            'trades' => $metrics->trades->count,
            'artifact' => $artifact,
            'started_at' => $started,
            'finished_at' => $finished,
            'duration_ms' => (int) $started->diffInMilliseconds($finished),
        ]);

        $this->summary($report);
        $this->line("Report {$hash}");
        $this->line("Stored in {$artifact}, {$result->candles} candles read in ".number_format((int) $started->diffInMilliseconds($finished) / 1000, 1).' s.');

        return self::SUCCESS;
    }

    private function summary(Report $report): void
    {
        $metrics = $report->metrics;
        $percent = fn (?Decimal $value): string => $value === null ? 'n/a' : $value->times(Decimal::of(100))->roundedTo(2).' %';
        $number = fn (?Decimal $value): string => $value === null ? 'n/a' : (string) $value->roundedTo(4);
        $rows = [
            ['Return', $percent($metrics->return)],
            ['CAGR', $percent($metrics->cagr)],
            ['Maximum drawdown', $percent($metrics->maxDrawdown)],
            ['Sharpe', $number($metrics->sharpe)],
            ['Sortino', $number($metrics->sortino)],
            ['Time in market', $percent($metrics->timeInMarket)],
            ['Trades', (string) $metrics->trades->count],
            ['Win rate', $percent($metrics->trades->winRate)],
            ['Profit factor', $number($metrics->trades->profitFactor)],
            ['Expectancy per trade', $number($metrics->trades->expectancy)],
            ['Fees', $number($metrics->trades->fees)],
            ...($metrics->trades->funding === null ? [] : [['Funding', $number($metrics->trades->funding)]]),
        ];
        $benchmarks = $report->content['benchmarks'] ?? [];

        foreach (is_array($benchmarks) ? $benchmarks : [] as $label => $benchmark) {
            $missing = is_array($benchmark) ? ($benchmark['missing'] ?? null) : null;
            $return = is_array($benchmark) ? ($benchmark['return'] ?? null) : null;
            $rows[] = ["Benchmark {$label}", is_string($missing) ? "missing: {$missing}" : $percent($return instanceof Decimal ? $return : null)];
        }

        $this->table(['Metric', 'Value'], $rows);
    }

    /** @return array{string, class-string<BuildableStrategy>, string} */
    private function registered(Repository $config, string $name): array
    {
        $entry = $config->get("simulation.strategies.{$name}");
        $class = is_array($entry) ? ($entry['class'] ?? null) : null;

        if (! is_string($class) || ! is_a($class, BuildableStrategy::class, true)) {
            throw new InvalidArgumentException("Unknown strategy '{$name}'. Known: ".implode(', ', array_keys($this->section($config, 'simulation.strategies'))).'.');
        }

        $profile = is_array($entry) && ($entry['profile'] ?? 'account') === 'benchmark' ? 'benchmark' : 'account';

        return [$name, $class, $profile];
    }

    /**
     * @param  array<string, string>  $defaults
     * @return array<string, string>
     */
    private function parameters(array $defaults): array
    {
        $parameters = $defaults;

        foreach ($this->strings('param') as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');

            if (! array_key_exists($key, $defaults) || $value === '') {
                throw new InvalidArgumentException("Unknown or empty parameter '{$pair}'. Parameters: ".implode(', ', array_keys($defaults)).'.');
            }

            $parameters[$key] = $value;
        }

        return $parameters;
    }

    /**
     * @param  list<Symbol>  $symbols
     * @return array<string, RulesHistory>
     */
    private function rules(array $symbols): array
    {
        $rules = [];

        foreach ($symbols as $symbol) {
            $market = self::market($symbol);
            $instrument = self::instrument($symbol)
                ?? throw new InvalidArgumentException("{$symbol} is not a known {$market->value} instrument; run market:instruments first.");
            $rules[(string) $symbol] = $instrument->rulesHistory();
        }

        return $rules;
    }

    /**
     * The starting balances (D-49): everything in the spot wallet when only spot is traded, everything in the USD-M
     * wallet when only perpetuals are, and the configured split when both are. A null futures balance means a spot run.
     *
     * @param  list<Symbol>  $symbols
     * @return array{Money, ?Decimal}
     */
    private function wallets(Repository $config, array $symbols, Money $equity): array
    {
        $perpetuals = count(array_filter($symbols, fn (Symbol $symbol): bool => $symbol->isPerpetual()));

        if ($perpetuals === 0) {
            return [$equity, null];
        }

        if ($perpetuals === count($symbols)) {
            return [Money::of('0', $equity->asset), $equity->amount];
        }

        $spot = $equity->amount->times(self::decimal($this->section($config, 'simulation.perpetual'), 'spot_share_when_both'));

        return [Money::of((string) $spot, $equity->asset), $equity->amount->minus($spot)];
    }

    /**
     * The comparisons, on the spot instrument of the first symbol's base, each in its own account with the whole
     * equity in the spot wallet (D-49). Their rules join $rules.
     *
     * @param  list<Symbol>  $symbols
     * @param  array<string, RulesHistory>  $rules
     * @param  Money|null  $equity  the whole equity when the strategy's own split differs
     * @return array{list<Participant>, array<string, string>}
     */
    private function benchmarks(Repository $config, QualityCheck $quality, array $symbols, CarbonImmutable $from, CarbonImmutable $to, array &$rules, ?Money $equity): array
    {
        $participants = [];
        $missing = [];
        $symbol = Symbol::parse("{$symbols[0]->base}/{$symbols[0]->quote}");

        foreach ($this->section($config, 'simulation.benchmarks') as $label => $entry) {
            $label = (string) $label;
            $name = is_array($entry) && is_string($entry['strategy'] ?? null) ? $entry['strategy'] : '';
            [, $class] = $this->registered($config, $name);
            $parameters = $class::defaults();

            foreach (is_array($entry) && is_array($entry['parameters'] ?? null) ? $entry['parameters'] : [] as $key => $value) {
                $parameters[(string) $key] = is_scalar($value) ? (string) $value : '';
            }

            $strategy = $class::build($parameters, [$symbol]);
            $warmupFrom = self::warmupFrom($strategy, $from);
            $problem = $this->coverage($quality, $symbol, $warmupFrom, $to);

            if ($problem === null) {
                $rules[(string) $symbol] ??= $this->rules([$symbol])[(string) $symbol];
                $participants[] = new Participant($label, $strategy, RiskPolicy::benchmark(), $warmupFrom, $equity, $equity === null ? null : Decimal::zero());
            } else {
                $missing[$label] = "needs complete history of {$symbol} from {$warmupFrom->toDateString()}: {$problem}";
            }
        }

        return [$participants, $missing];
    }

    /** Why the stored candles cannot carry a run over [$from, $to), or null. */
    private function coverage(QualityCheck $quality, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): ?string
    {
        $instrument = self::instrument($symbol);

        if ($instrument === null) {
            return 'unknown instrument';
        }

        $report = $quality->run($instrument, $from, $to->subMinute());

        if ($report->gaps !== []) {
            $first = $report->gaps[0];

            return sprintf(
                '%d minutes missing in %d gaps, the first from %s to %s',
                $report->missingMinutes,
                count($report->gaps),
                $first['from']->format('Y-m-d H:i'),
                $first['to']->format('Y-m-d H:i'),
            );
        }

        return $report->ohlcViolations > 0 ? "{$report->ohlcViolations} candles break high and low" : null;
    }

    /**
     * Why the stored funding cannot carry a perpetual over [$from, $to], or null: no stored rate may follow the one
     * before it, or the start, by more than its own interval.
     */
    private function fundingCoverage(FundingRateRepository $funding, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): ?string
    {
        $instrument = $symbol->isPerpetual() ? self::instrument($symbol) : null;

        if ($instrument === null) {
            return null;
        }

        $rates = $funding->range($instrument, $from, $to);

        if ($rates === []) {
            return sprintf('no funding stored between %s and %s', $from->format('Y-m-d H:i'), $to->format('Y-m-d H:i'));
        }

        $previous = $from;

        foreach ($rates as $rate) {
            if ($rate->fundingTime->getTimestamp() - $previous->getTimestamp() > $rate->intervalHours * 3600) {
                return sprintf('funding missing between %s and %s', $previous->format('Y-m-d H:i'), $rate->fundingTime->format('Y-m-d H:i'));
            }

            $previous = $rate->fundingTime;
        }

        $last = $rates[count($rates) - 1];

        return $to->getTimestamp() - $last->fundingTime->getTimestamp() > $last->intervalHours * 3600
            ? sprintf('funding missing after %s', $last->fundingTime->format('Y-m-d H:i'))
            : null;
    }

    /** @return list<Symbol> the instruments the strategy subscribes to, in subscription order */
    private static function followed(Strategy $strategy): array
    {
        $symbols = [];

        foreach ($strategy->subscriptions() as $subscription) {
            $symbols[(string) $subscription->symbol] ??= $subscription->symbol;
        }

        return array_values($symbols);
    }

    private static function market(Symbol $symbol): MarketType
    {
        return $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot;
    }

    private static function instrument(Symbol $symbol): ?Instrument
    {
        return Instrument::query()->where('market', self::market($symbol))->where('symbol', (string) $symbol)->first();
    }

    /** The start of the warmup: as many candles of the strategy's longest timeframe as it needs, from a midnight. */
    private static function warmupFrom(Strategy $strategy, CarbonImmutable $from): CarbonImmutable
    {
        $seconds = 60;

        foreach ($strategy->subscriptions() as $subscription) {
            $seconds = max($seconds, $subscription->timeframe->seconds());
        }

        return $from->subSeconds($seconds * $strategy->warmupBars())->startOfDay();
    }

    private function store(string $directory, string $hash, string $json): string
    {
        if (! is_dir($directory) && ! mkdir($directory, 0o755, true) && ! is_dir($directory)) {
            throw new RuntimeException("Cannot create {$directory}.");
        }

        $path = "{$directory}/{$hash}.json.gz";
        $compressed = gzencode($json, 9);

        if ($compressed === false || file_put_contents($path, $compressed) === false) {
            throw new RuntimeException("Cannot write {$path}.");
        }

        return $path;
    }

    private function day(string $option): CarbonImmutable
    {
        $value = $this->option($option);

        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            throw new InvalidArgumentException("--{$option} must be a day, YYYY-MM-DD.");
        }

        return CarbonImmutable::parse($value, 'UTC')->startOfDay();
    }

    private function stringArgument(string $name): string
    {
        $value = $this->argument($name);

        return is_string($value) ? $value : '';
    }

    /** @return list<string> */
    private function strings(string $option): array
    {
        $values = $this->option($option);

        return is_array($values) ? array_values(array_filter($values, 'is_string')) : [];
    }

    /** @return array<array-key, mixed> */
    private function section(Repository $config, string $key): array
    {
        $value = $config->get($key);

        return is_array($value) ? $value : throw new InvalidArgumentException("config {$key} must be an array.");
    }

    private function text(Repository $config, string $key): string
    {
        $value = $config->get($key);

        return is_string($value) ? $value : throw new InvalidArgumentException("config {$key} must be a string.");
    }

    /** @param array<array-key, mixed> $section */
    private static function decimal(array $section, string $key): Decimal
    {
        $value = $section[$key] ?? null;

        return is_string($value) ? Decimal::of($value) : throw new InvalidArgumentException("Missing decimal setting '{$key}'.");
    }
}
