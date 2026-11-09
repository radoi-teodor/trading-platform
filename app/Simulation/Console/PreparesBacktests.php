<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\MarketType;
use App\Market\Data\RulesHistory;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\FundingRateRepository;
use App\Market\Instruments\StoredInstruments;
use App\Market\Models\Instrument;
use App\Market\Quality\QualityCheck;
use App\Market\ScalpingRepository;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Data\SetOutcome;
use App\Simulation\Metrics\Statistics;
use App\Simulation\Models\StrategyTrial;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Validation\Cscv;
use App\Simulation\Validation\DeflatedSharpe;
use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\ParameterSpec;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Versions\StrategyRepository;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Process\InvokedProcess as InvokedProcessContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Process\Factory as Processes;
use InvalidArgumentException;
use RuntimeException;

/** What the backtest commands share: reading options and config, filters, data coverage, wallets, running parameter sets. */
trait PreparesBacktests
{
    /** @return array{string, class-string<BuildableStrategy>, string} */
    protected function registered(Repository $config, string $name): array
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
    protected function parameters(array $defaults): array
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
    protected function rules(array $symbols): array
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
    protected function wallets(Repository $config, array $symbols, Money $equity): array
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
    protected function benchmarks(Repository $config, QualityCheck $quality, array $symbols, CarbonImmutable $from, CarbonImmutable $to, array &$rules, ?Money $equity): array
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
    protected function coverage(QualityCheck $quality, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): ?string
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
    protected function fundingCoverage(FundingRateRepository $funding, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): ?string
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

    /**
     * Why the stored data for scalping cannot carry a strategy that reads it over [$from, $to], or null (D-79): every
     * day of the range must have rows in each store the strategy reads.
     */
    protected function scalpingCoverage(Strategy $strategy, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): ?string
    {
        $instrument = $symbol->isPerpetual() ? self::instrument($symbol) : null;

        if (! $strategy instanceof CompiledStrategy || $instrument === null) {
            return null;
        }

        $names = ['trade_flow' => 'trade flow', 'open_interest' => 'open interest', 'mark_price_minutes' => 'mark price', 'premium_index_minutes' => 'premium index', 'positioning' => 'positioning ratios', 'book_depth' => 'order book depth'];
        $flag = ['trade_flow' => '--flow', 'open_interest' => '--oi', 'mark_price_minutes' => '--mark', 'premium_index_minutes' => '--mark', 'positioning' => '--oi', 'book_depth' => '--depth'];

        foreach ($strategy->scalpingData() as $table) {
            $missing = $this->laravel->make(ScalpingRepository::class)->missingDays($table, $instrument, $from, $to);

            if ($missing !== []) {
                return sprintf('no %s stored on %s%s (market:backfill %s)', $names[$table], $missing[0], count($missing) > 1 ? ' and '.(count($missing) - 1).' more days' : '', $flag[$table]);
            }
        }

        return null;
    }

    /** @return list<Symbol> the instruments the strategy subscribes to, in subscription order */
    protected static function followed(Strategy $strategy): array
    {
        $symbols = [];

        foreach ($strategy->subscriptions() as $subscription) {
            $symbols[(string) $subscription->symbol] ??= $subscription->symbol;
        }

        return array_values($symbols);
    }

    protected static function market(Symbol $symbol): MarketType
    {
        return $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot;
    }

    protected static function instrument(Symbol $symbol): ?Instrument
    {
        return Instrument::query()->where('market', self::market($symbol))->where('symbol', (string) $symbol)->first();
    }

    /** The start of the warmup: as many candles of the strategy's longest timeframe as it needs, from a midnight. */
    protected static function warmupFrom(Strategy $strategy, CarbonImmutable $from): CarbonImmutable
    {
        $seconds = 60;

        foreach ($strategy->subscriptions() as $subscription) {
            $seconds = max($seconds, $subscription->timeframe->seconds());
        }

        return $from->subSeconds($seconds * $strategy->warmupBars())->startOfDay();
    }

    protected function store(string $directory, string $hash, string $json): string
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

    protected function day(string $option): CarbonImmutable
    {
        $value = $this->option($option);

        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            throw new InvalidArgumentException("--{$option} must be a day, YYYY-MM-DD.");
        }

        return CarbonImmutable::parse($value, 'UTC')->startOfDay();
    }

    protected function stringArgument(string $name): string
    {
        $value = $this->argument($name);

        return is_string($value) ? $value : '';
    }

    /** @return list<string> */
    protected function strings(string $option): array
    {
        $values = $this->option($option);

        return is_array($values) ? array_values(array_filter($values, 'is_string')) : [];
    }

    /** @return array<array-key, mixed> */
    protected function section(Repository $config, string $key): array
    {
        $value = $config->get($key);

        return is_array($value) ? $value : throw new InvalidArgumentException("config {$key} must be an array.");
    }

    protected function text(Repository $config, string $key): string
    {
        $value = $config->get($key);

        return is_string($value) ? $value : throw new InvalidArgumentException("config {$key} must be a string.");
    }

    /** @param array<array-key, mixed> $section */
    protected static function decimal(array $section, string $key): Decimal
    {
        $value = $section[$key] ?? null;

        return is_string($value) ? Decimal::of($value) : throw new InvalidArgumentException("Missing decimal setting '{$key}'.");
    }

    /** Fees times 1.5 and slippage (half spread and impact) times 2: the cost stress of docs/simulation.md, section 5. */
    public const array STRESS = ['fees' => '1.5', 'slippage' => '2'];

    /**
     * Runs parameter sets of a document over the same candles in one pass (D-57), or split over child processes that
     * each run a share of the sets from the same warmup start, which gives the same outcomes in the same order.
     *
     * @param  list<array<string, string>>  $sets
     * @return array{outcomes: list<SetOutcome>, definition: RunDefinition, fingerprint: string, candles: int}
     */
    protected function runSets(
        Repository $config, QualityCheck $quality, FundingRateRepository $funding, ConnectionInterface $db, StrategySpec $spec, string $document,
        array $sets, int $version, CarbonImmutable $from, CarbonImmutable $to, string $equityText, bool $stressed = false, int $workers = 1,
        ?Processes $processes = null, ?CarbonImmutable $warmupFrom = null,
    ): array {
        $strategies = array_map(fn (array $set): Strategy => CompiledStrategy::fromSpec($spec, $set, $version), $sets);
        $symbols = self::followed($strategies[0]);
        $rules = $this->rules($symbols);

        if ($warmupFrom === null) {
            $warmupFrom = $from;

            foreach ($strategies as $strategy) {
                $start = self::warmupFrom($strategy, $from);
                $warmupFrom = $start < $warmupFrom ? $start : $warmupFrom;
            }

            foreach ($symbols as $symbol) {
                $problem = $this->coverage($quality, $symbol, $warmupFrom, $to) ?? $this->fundingCoverage($funding, $symbol, $warmupFrom, $to)
                    ?? $this->scalpingCoverage($strategies[0], $symbol, $from, $to);

                if ($problem !== null) {
                    throw new InvalidArgumentException("{$symbol}: {$problem}. Backfill it before backtesting (market:backfill).");
                }
            }
        }

        $policy = $spec->benchmark ? RiskPolicy::benchmark() : RiskPolicy::account($this->section($config, 'risk.account'));
        $equity = Money::of($equityText, Asset::of($this->text($config, 'simulation.quote')));
        [$spotBalance, $futuresBalance] = $this->wallets($config, $symbols, $equity);
        $costs = $this->section($config, 'simulation.cost_model');
        $fees = $this->section($config, 'simulation.fees');
        $perpetual = $this->section($config, 'simulation.perpetual');
        $perpetualFees = is_array($perpetual['fees'] ?? null) ? $perpetual['fees'] : [];
        $fee = Decimal::of($stressed ? self::STRESS['fees'] : '1');
        $slip = Decimal::of($stressed ? self::STRESS['slippage'] : '1');
        $definition = new RunDefinition(
            strategy: $strategies[0]->identity(),
            market: $futuresBalance === null ? MarketType::Spot : MarketType::Perp,
            symbols: $symbols,
            from: $from,
            to: $to,
            warmupFrom: $warmupFrom,
            initialBalance: $spotBalance,
            fees: new FeeSchedule(self::decimal($fees, 'maker')->times($fee), self::decimal($fees, 'taker')->times($fee)),
            costs: new CostModel(
                is_int($costs['version'] ?? null) ? $costs['version'] : 0, self::decimal($costs, 'half_spread')->times($slip), self::decimal($costs, 'impact')->times($slip),
                self::decimal($costs, 'limit_volume_share'),
            ),
            policy: $policy,
            futuresBalance: $futuresBalance,
            perpetualFees: $futuresBalance === null ? null : new FeeSchedule(self::decimal($perpetualFees, 'maker')->times($fee), self::decimal($perpetualFees, 'taker')->times($fee)),
            maintenanceRate: $futuresBalance === null ? null : self::decimal($perpetual, 'maintenance_margin_rate'),
        );

        if ($workers > 1 && count($sets) > 1 && $processes !== null) {
            return ['definition' => $definition, ...$this->splitSets($processes, $document, $sets, $version, $from, $to, $warmupFrom, $equityText, $stressed, $workers)];
        }

        $participants = array_map(
            fn (Strategy $strategy): Participant => new Participant(implode(' ', array_map(fn (string $k, string $v): string => "{$k}={$v}", array_keys($strategy->identity()->parameters), $strategy->identity()->parameters)) ?: 'defaults', $strategy, $policy, self::warmupFrom($strategy, $from)),
            $strategies,
        );
        $run = (new Backtest(new DatabaseCandleFeed($db)))->run($definition, $participants[0], $rules, array_slice($participants, 1));

        return [
            'outcomes' => array_map(SetOutcome::of(...), [$run->main, ...$run->benchmarks]),
            'definition' => $definition,
            'fingerprint' => $run->fingerprint,
            'candles' => $run->candles,
        ];
    }

    /**
     * @param  list<array<string, string>>  $sets
     * @return array{outcomes: list<SetOutcome>, fingerprint: string, candles: int}
     */
    private function splitSets(
        Processes $processes, string $document, array $sets, int $version, CarbonImmutable $from, CarbonImmutable $to, CarbonImmutable $warmupFrom,
        string $equity, bool $stressed, int $workers,
    ): array {
        $parts = min($workers, count($sets));
        $chunks = array_chunk($sets, max(1, intdiv(count($sets) + $parts - 1, $parts)));
        $directory = sys_get_temp_dir().'/trading-grid-'.getmypid().'-'.substr(hash('sha256', $document.CanonicalJson::encode($sets)), 0, 12);

        if (! is_dir($directory) && ! mkdir($directory, 0700, true)) {
            throw new RuntimeException("Cannot create {$directory}.");
        }

        $jobs = [];

        foreach ($chunks as $index => $chunk) {
            $job = "{$directory}/part-{$index}.json";
            file_put_contents($job, CanonicalJson::encode([
                'document' => $document, 'version' => $version, 'sets' => $chunk, 'from' => $from->toDateString(), 'to' => $to->toDateString(),
                'warmup_from' => $warmupFrom->toIso8601ZuluString(), 'equity' => $equity, 'stressed' => $stressed, 'out' => "{$directory}/out-{$index}.json",
            ]));
            $jobs[] = $job;
        }

        $artisan = $this->laravel->basePath('artisan');
        // The workers read the database this process reads, whatever their environment says.
        $connection = $this->laravel->make('db')->connection();
        $environment = [
            'APP_ENV' => $this->laravel->environment(),
            'DB_CONNECTION' => $connection->getName(),
            'DB_DATABASE' => $connection->getDatabaseName(),
        ];
        $running = array_map(fn (string $job): InvokedProcessContract => $processes->newPendingProcess()->forever()->env($environment)->start([PHP_BINARY, $artisan, 'backtest:grid-part', $job]), $jobs);
        $outcomes = [];
        $fingerprint = '';
        $candles = 0;

        foreach ($jobs as $index => $job) {
            $result = $running[$index]->wait();

            if (! $result->successful()) {
                throw new RuntimeException("A grid worker failed: {$result->errorOutput()}{$result->output()}");
            }

            $out = json_decode((string) file_get_contents("{$directory}/out-{$index}.json"), true, flags: JSON_THROW_ON_ERROR);
            $part = is_array($out) ? $out : [];
            $fingerprint = $fingerprint === '' && is_string($part['fingerprint'] ?? null) ? $part['fingerprint'] : $fingerprint;
            $candles = max($candles, is_int($part['candles'] ?? null) ? $part['candles'] : 0);

            foreach (is_array($part['outcomes'] ?? null) ? $part['outcomes'] : [] as $outcome) {
                $outcomes[] = SetOutcome::fromArray(is_array($outcome) ? $outcome : []);
            }

            @unlink($job);
            @unlink("{$directory}/out-{$index}.json");
        }

        @rmdir($directory);

        return ['outcomes' => $outcomes, 'fingerprint' => $fingerprint, 'candles' => $candles];
    }

    /** @return list<Decimal> */
    protected static function span(Decimal $min, Decimal $max, Decimal $step): array
    {
        $values = [];

        for ($value = $min; ! $value->isGreaterThan($max) && count($values) <= 100_000; $value = $value->plus($step)) {
            $values[] = $value;
        }

        return $values;
    }

    /** @param list<array{parameters: array<string, string>, daily_sharpe: ?Decimal, daily: list<Decimal>}> $rows */
    protected function recordTrials(StrategySpec $spec, RunDefinition $definition, array $rows, CarbonImmutable $from, CarbonImmutable $to, Clock $clock): void
    {
        foreach ($rows as $row) {
            StrategyTrial::query()->create([
                'family' => $spec->id,
                'strategy' => $spec->id,
                'parameters' => $row['parameters'],
                'definition_hash' => $definition->hash(),
                'window_from' => $from,
                'window_to' => $to,
                'daily_sharpe' => $row['daily_sharpe'] === null ? null : (string) $row['daily_sharpe'],
                'observations' => count($row['daily']),
                'created_at' => $clock->now(),
            ]);
        }
    }

    /**
     * The deflated Sharpe ratio of the best set against every trial of the family, and PBO over the grid.
     *
     * @param  list<array{parameters: array<string, string>, daily_sharpe: ?Decimal, daily: list<Decimal>}>  $rows
     * @return array<string, mixed>
     */
    protected function validate(StrategySpec $spec, array $rows): array
    {
        $ranked = array_values(array_filter($rows, fn (array $row): bool => $row['daily_sharpe'] !== null));
        usort($ranked, fn (array $a, array $b): int => ($b['daily_sharpe'] ?? Decimal::zero())->compare($a['daily_sharpe'] ?? Decimal::zero()));
        $validation = ['best' => null, 'deflated_sharpe' => null, 'pbo' => null, 'notes' => []];

        if ($ranked === []) {
            $validation['notes'][] = 'No set has daily returns that move: nothing to rank.';

            return $validation;
        }

        $best = $ranked[0];
        $validation['best'] = self::label($best['parameters']);
        $sharpes = array_values(array_map(
            fn (mixed $value): Decimal => Decimal::of(is_string($value) ? $value : '0'),
            StrategyTrial::query()->where('family', $spec->id)->whereNotNull('daily_sharpe')->orderBy('id')->pluck('daily_sharpe')->all(),
        ));
        $variance = count($sharpes) >= 2 ? Statistics::standardDeviation($sharpes) : null;
        $variance = $variance === null ? Decimal::zero() : $variance->times($variance);
        $moments = DeflatedSharpe::moments($best['daily']);
        $benchmark = DeflatedSharpe::expectedMaximum(max(1, count($sharpes)), $variance);

        try {
            $validation['deflated_sharpe'] = [
                'probability' => DeflatedSharpe::probability($best['daily_sharpe'] ?? Decimal::zero(), count($best['daily']), $moments['skewness'], $moments['kurtosis'], $benchmark),
                'daily_sharpe' => $best['daily_sharpe'],
                'expected_maximum' => $benchmark,
                'trials' => count($sharpes),
                'trial_variance' => $variance,
                'observations' => count($best['daily']),
                'skewness' => $moments['skewness'],
                'kurtosis' => $moments['kurtosis'],
            ];
        } catch (InvalidArgumentException $failure) {
            $validation['notes'][] = "Deflated Sharpe ratio: {$failure->getMessage()}";
        }

        try {
            $validation['pbo'] = Cscv::overfitting(array_map(fn (array $row): array => $row['daily'], $rows));
        } catch (InvalidArgumentException $failure) {
            $validation['notes'][] = "Probability of backtest overfitting: {$failure->getMessage()}";
        }

        return $validation;
    }

    /** @param array<string, string> $parameters */
    protected static function label(array $parameters): string
    {
        return implode(' ', array_map(fn (string $name, string $value): string => "{$name}={$value}", array_keys($parameters), $parameters)) ?: 'defaults';
    }

    /** A parameter value as documents and sets write it: a percent as 0.1%, any other number as itself. */
    protected static function parameterText(ParameterSpec $param, Decimal $value): string
    {
        return $param->kind === Kind::Percent ? $value->times(Decimal::of(100))->roundedTo(6).'%' : (string) $value;
    }

    /** The value a set's text stands for: a fraction for a percent. */
    protected static function parameterValue(ParameterSpec $param, string $text): Decimal
    {
        return str_ends_with($text, '%') ? Decimal::of(substr($text, 0, -1))->dividedBy(Decimal::of(100), 18) : Decimal::of($text);
    }

    /** The saved strategy versions, checked against the stored instruments and the account policy (D-61). */
    protected function versions(Repository $config, Clock $clock): StrategyRepository
    {
        $policy = RiskPolicy::account($this->section($config, 'risk.account'));

        return new StrategyRepository($clock, new StoredInstruments, new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions));
    }

    /**
     * Reads a strategy document from a saved version (<id>@v<n>) or a file, checks it, prints its warnings and
     * errors, and returns the checked spec with the document's version number (1 for a file), or null on errors.
     *
     * @return array{StrategySpec, int, string}|null the spec, the version number and the document's text
     */
    protected function loadDocument(Repository $config, Clock $clock, ?string $label, ?string $file): ?array
    {
        if ($label !== null) {
            $version = $this->versions($config, $clock)->find($label)
                ?? throw new InvalidArgumentException("{$label} is not a saved version. Save it with strategy:import first.");
            [$text, $source, $number] = [$version->document, $label, $version->version];
        } else {
            $read = $file !== null && is_file($file) ? file_get_contents($file) : false;
            [$text, $source, $number] = [$read === false ? throw new InvalidArgumentException("Cannot read {$file}.") : $read, (string) $file, 1];
        }

        $policy = RiskPolicy::account($this->section($config, 'risk.account'));
        $result = DocumentChecker::check($text, new StoredInstruments, new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions));

        foreach ($result->warnings as $warning) {
            $this->warn("warning: {$warning}");
        }

        foreach ($result->errors as $error) {
            $this->error("{$source}:{$error->position->line}:{$error->position->column}: {$error->problem}");
        }

        return $result->spec === null ? null : [$result->spec, $number, $text];
    }
}
