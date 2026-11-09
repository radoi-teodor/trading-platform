<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\FundingRateRepository;
use App\Market\Instruments\ResearchUniverse;
use App\Market\Quality\QualityCheck;
use App\Simulation\Data\SetOutcome;
use App\Simulation\Metrics\Statistics;
use App\Simulation\Models\StrategyGateReport;
use App\Simulation\Models\StrategyHoldout;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Validation\Gates;
use App\Simulation\Validation\MonteCarlo;
use App\Simulation\Validation\RegimeLabels;
use App\Simulation\Validation\WalkForward;
use App\Simulation\Validation\WalkForwardSet;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\Document\UniverseRule;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Process\Factory as Processes;
use InvalidArgumentException;

/**
 * The gate report of a saved version (docs/simulation.md, sections 5 and 7). Over the version's history, less its
 * last 20 % held out: the grid of its declared parameters walks forward (D-57); the set chosen last is moved 20 % up
 * and down for robustness, run once on the hold-out (D-58), and run again with stressed costs. The out-of-sample
 * trades (walk-forward windows plus hold-out) are judged by every gate, resampled by Monte Carlo and split by regime.
 * It writes a report and never changes a stage: strategy:promote reads the report.
 */
final class StrategyGates extends Command
{
    use PreparesBacktests;
    use ReadsGridOptions;

    private const int MAX_SETS = 400;

    private const int HOLDOUT_PERCENT = 20;

    protected $signature = 'strategy:gates
        {strategy : A saved version, such as donchian-breakout@v1}
        {--from= : First day of the history (default: the first stored day after the warmup)}
        {--to= : Day after the last one (default: the day after the last complete stored day)}
        {--in=12m : In-sample length of the walk-forward, months (12m) or days (20d)}
        {--out=3m : Out-of-sample length}
        {--step=3m : Step between windows}
        {--min-trades=100 : Trades a set needs in-sample to be chosen}
        {--seed=2026 : Seed of the Monte Carlo (D-58)}
        {--workers=1 : Child processes to split the grids over; the result does not depend on it}
        {--json : End with a one-line JSON summary}';

    protected $description = 'Judge a saved version against the gates for the Backtested stage';

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, Clock $clock, ConnectionInterface $db, Processes $processes): int
    {
        try {
            $label = $this->stringArgument('strategy');
            $version = $this->versions($config, $clock)->find($label) ?? throw new InvalidArgumentException("{$label} is not a saved version. Save it with strategy:import first.");
            $loaded = $this->loadDocument($config, $clock, $label, null);

            if ($loaded === null) {
                return self::FAILURE;
            }

            [$spec, $number, $document] = $loaded;

            if ($spec->benchmark) {
                throw new InvalidArgumentException("{$label} is a benchmark: it runs in backtests only and is never promoted (D-56).");
            }

            $walk = $this->walkOptions();
            $workers = $this->workers();
            $seed = $this->seed();
            $sets = $this->declaredSets($spec);
            $defaults = self::shape($spec, [], $number);
            [$from, $to] = $this->history($db, $spec, $sets, $number);
            $days = intdiv($to->getTimestamp() - $from->getTimestamp(), 86_400);
            $holdoutFrom = $to->subDays(max(1, intdiv($days * self::HOLDOUT_PERCENT + 50, 100)));
            $equity = $this->text($config, 'simulation.initial_equity');
            $run = fn (array $s, CarbonImmutable $a, CarbonImmutable $b, bool $stressed = false): array => $this->runSets(
                $config, $quality, $funding, $db, $spec, $document, self::setList($s), $number, $a, $b, $equity, $stressed, $workers, $processes,
            );

            $this->info(sprintf(
                'Gates for %s: history %s to %s, hold-out from %s; %d parameter sets walk forward over %s in-sample and %s out of sample.',
                $label, $from->toDateString(), $to->toDateString(), $holdoutFrom->toDateString(), count($sets), $walk['in'], $walk['out'],
            ));
            $grid = $run($sets, $from, $holdoutFrom);
            $rows = GridBacktest::rows($grid['outcomes']);
            $this->recordTrials($spec, $grid['definition'], $rows, $from, $holdoutFrom, $clock);
            $validation = $this->validate($spec, $rows);
            $walked = WalkForward::evaluate(
                array_map(fn (SetOutcome $o): WalkForwardSet => new WalkForwardSet($o->parameters, $o->trips), $grid['outcomes']),
                $from, $holdoutFrom, $walk['in'], $walk['out'], $walk['step'], $walk['min'],
            );
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $chosen = $defaults->identity()->parameters;
        $outOfSample = [];

        foreach ($walked->windows as $window) {
            if ($window->chosen === null) {
                continue;
            }

            $chosen = $window->chosen;

            foreach ($grid['outcomes'] as $outcome) {
                if ($outcome->parameters === $window->chosen) {
                    foreach ($outcome->trips as $trip) {
                        if ($trip['closed'] >= $window->outFrom && $trip['closed'] < $window->outTo) {
                            $outOfSample[] = $trip;
                        }
                    }
                }
            }
        }

        $this->line('Chosen set: '.self::label($chosen).'.');
        $neighbours = $this->neighbours($spec, $chosen);
        $robust = null;

        if ($neighbours !== []) {
            $this->line(count($neighbours).' neighbours, each parameter 20 % down, unchanged and 20 % up...');
            $kept = array_filter($run($neighbours, $from, $holdoutFrom)['outcomes'], fn (SetOutcome $o): bool => self::keepsProfitFactor($o));
            $robust = Decimal::of(count($kept))->dividedBy(Decimal::of(count($neighbours)), 18);
        }

        $stored = StrategyHoldout::query()->where('strategy_version_id', $version->id)->first();

        if ($stored === null) {
            $this->line("Hold-out {$holdoutFrom->toDateString()} to {$to->toDateString()}, used once for this version...");
            $holdout = $run([$chosen], $holdoutFrom, $to)['outcomes'][0];
            StrategyHoldout::query()->create([
                'strategy_version_id' => $version->id, 'parameters' => $chosen, 'window_from' => $holdoutFrom, 'window_to' => $to,
                'outcome' => $holdout->toArray(), 'created_at' => $clock->now(),
            ]);
        } else {
            $holdout = SetOutcome::fromArray($stored->outcome);
            $this->warn("The hold-out of {$label} was used on {$stored->created_at->toDateString()} with ".self::label($stored->parameters).'; its recorded result is read, not run again (D-58).');
        }

        $outOfSample = [...$outOfSample, ...$holdout->trips];
        $firstOut = $walked->windows === [] ? $holdoutFrom : $walked->windows[0]->outFrom;
        $this->line("Cost stress from {$firstOut->toDateString()}: fees x".self::STRESS['fees'].', slippage x'.self::STRESS['slippage'].'...');
        $stressed = $run([$chosen], $firstOut, $to, true)['outcomes'][0];
        $nets = array_map(fn (array $trip): Decimal => $trip['net'], $outOfSample);
        $monteCarlo = MonteCarlo::run($nets, $holdout->daily, Decimal::of($equity), $seed);
        $regimes = RegimeLabels::fromDailyCandles($this->dailyCandles($db, $this->regimeInstrument($spec, $from), $from->subDays(60), $to))->breakdown($outOfSample);
        $limit = Decimal::of($this->text($config, 'risk.account.drawdown_halt'));
        $checks = Gates::evaluate(
            $nets, count($walked->windows), $walked->positiveWindows, self::probability($validation), $robust, $stressed->expectancy,
            $monteCarlo['trades']['drawdown']['p95'] ?? null, $limit, $spec->template !== null, (int) $firstOut->diffInMonths($to),
        );
        $passed = Gates::passed($checks);
        $report = [
            'version' => $label,
            'history' => ['from' => $from, 'to' => $to, 'holdout_from' => $holdoutFrom],
            'chosen' => $chosen,
            'gates' => $checks,
            'passed' => $passed,
            'out_of_sample' => [
                'trades' => count($nets), 'net' => Statistics::sum($nets), 'expectancy' => Statistics::mean($nets),
                'interval' => Statistics::confidenceInterval95($nets), 'profit_factor' => Gates::profitFactor($nets),
            ],
            'walk_forward' => ['windows' => count($walked->windows), 'positive' => $walked->positiveWindows, 'trades' => count($walked->outOfSample), 'net' => $walked->net],
            'holdout' => ['trades' => $holdout->trades, 'return' => $holdout->return, 'expectancy' => $holdout->expectancy, 'reused' => $stored !== null],
            'deflated_sharpe' => $validation['deflated_sharpe'] ?? null,
            'pbo' => $validation['pbo'] ?? null,
            'robustness' => ['neighbours' => count($neighbours), 'share' => $robust],
            'cost_stress' => ['expectancy' => $stressed->expectancy, 'trades' => $stressed->trades],
            'monte_carlo' => ['seed' => $seed, 'resamples' => MonteCarlo::RESAMPLES, 'results' => $monteCarlo],
            'regimes' => $regimes,
        ];
        $stored = StrategyGateReport::query()->create([
            'strategy_version_id' => $version->id, 'passed' => $passed, 'report' => json_decode(CanonicalJson::encode($report), true), 'created_at' => $clock->now(),
        ]);
        $this->show($checks, $regimes, $monteCarlo, $passed, $label, $stored->id);

        if ($this->option('json') === true) {
            $this->line(CanonicalJson::encode(['report' => $stored->id, ...$report]));
        }

        return self::SUCCESS;
    }

    /**
     * The history: the options, or what is stored for the strategy.
     *
     * @param  list<array<string, string>>  $sets
     * @return array{CarbonImmutable, CarbonImmutable}
     */
    private function history(ConnectionInterface $db, StrategySpec $spec, array $sets, int $number): array
    {
        [$from, $to] = $spec->universe === null ? $this->stored($db, $spec, $sets, $number) : $this->built($spec->universe);
        $from = is_string($this->option('from')) ? $this->day('from') : $from;
        $to = is_string($this->option('to')) ? $this->day('to') : $to;

        if ($to <= $from->addDays(30)) {
            throw new InvalidArgumentException("The history from {$from->toDateString()} to {$to->toDateString()} is too short for gates: backfill more.");
        }

        return [$from, $to];
    }

    /**
     * The history of a universe strategy (D-92): the months the research universe is built for, to the end of what
     * is stored of the members of the last one that still trade. It is not the days every member has in common,
     * which a universe whose members come and go does not have. The import brings each member from a month before
     * it joins, so the first month has its warmup.
     *
     * @return array{CarbonImmutable, CarbonImmutable}
     */
    private function built(UniverseRule $rule): array
    {
        $research = $this->laravel->make(ResearchUniverse::class);
        $candles = $this->laravel->make(CandleRepository::class);
        $built = $research->built() ?? throw new InvalidArgumentException('The research universe is not built: run market:universe:build first.');
        $last = CarbonImmutable::parse("{$built['to']}-01", 'UTC');
        $to = $last->addMonth();

        foreach ($research->months($rule->n, $last, $last)[$built['to']] ?? [] as $symbol) {
            $instrument = self::instrument(Symbol::parse($symbol));

            if ($instrument === null || $instrument->delisted_at !== null) {
                continue;
            }

            $stored = $candles->latestOpenTime($instrument, Timeframe::OneMinute)?->addMinute()->startOfDay() ?? throw new InvalidArgumentException("{$symbol} has no stored candles.");
            $to = $stored < $to ? $stored : $to;
        }

        return [CarbonImmutable::parse("{$built['from']}-01", 'UTC'), $to];
    }

    /** The instrument whose days label the regimes: the first one named, or the first of the universe when the history starts. */
    private function regimeInstrument(StrategySpec $spec, CarbonImmutable $from): Symbol
    {
        if ($spec->universe === null) {
            return $spec->instruments[0];
        }

        $first = $this->laravel->make(ResearchUniverse::class)->months(1, $from, $from)[$from->format('Y-m')][0]
            ?? throw new InvalidArgumentException("The research universe has no members for {$from->format('Y-m')}.");

        return Symbol::parse($first);
    }

    /**
     * A strategy compiled to be read, not run: a universe strategy with nobody in its universe.
     *
     * @param  array<string, string>  $set
     */
    private static function shape(StrategySpec $spec, array $set, int $number): Strategy
    {
        return CompiledStrategy::fromSpec($spec, $set, $number, $spec->universe === null ? null : UniverseSchedule::none());
    }

    /**
     * The stored candles every instrument of the strategy has in common, less the warmup of the slowest set.
     *
     * @param  list<array<string, string>>  $sets
     * @return array{CarbonImmutable, CarbonImmutable}
     */
    private function stored(ConnectionInterface $db, StrategySpec $spec, array $sets, int $number): array
    {
        $first = null;
        $last = null;

        foreach (self::followed(CompiledStrategy::fromSpec($spec, $sets[0], $number)) as $symbol) {
            $instrument = self::instrument($symbol) ?? throw new InvalidArgumentException("{$symbol} is not a stored instrument.");
            $range = $db->table('candles')->where('instrument_id', $instrument->id)->where('timeframe', '1m')->selectRaw('min(open_time) as first, max(open_time) as last')->first();
            $low = is_object($range) && is_string($range->first ?? null) ? CarbonImmutable::parse($range->first, 'UTC')->utc() : throw new InvalidArgumentException("{$symbol} has no stored candles.");
            $high = is_string($range->last ?? null) ? CarbonImmutable::parse($range->last, 'UTC')->utc() : $low;
            $first = $first === null || $low > $first ? $low : $first;
            $last = $last === null || $high < $last ? $high : $last;
        }

        $first ??= throw new InvalidArgumentException('The strategy follows no instrument.');
        $longest = 0;

        foreach ($sets as $set) {
            $longest = max($longest, $first->getTimestamp() - self::warmupFrom(CompiledStrategy::fromSpec($spec, $set, $number), $first)->getTimestamp());
        }

        return [$first->addSeconds($longest)->startOfDay()->addDay(), ($last ?? $first)->addMinute()->startOfDay()];
    }

    /**
     * Every combination of the declared parameters' values: their range and step, or their default.
     *
     * @return list<array<string, string>>
     */
    private function declaredSets(StrategySpec $spec): array
    {
        $values = [];

        foreach ($spec->params as $name => $param) {
            $options = $param->min !== null && $param->max !== null && $param->step !== null ? self::span($param->min, $param->max, $param->step) : [$param->default];
            $values[$name] = array_map(fn (Decimal $v): string => self::parameterText($param, $v), $options);
        }

        $sets = self::combine($values);

        if (count($sets) > self::MAX_SETS) {
            throw new InvalidArgumentException(count($sets).' parameter sets in the declared ranges; at most '.self::MAX_SETS.': narrow a range or widen a step.');
        }

        return $sets;
    }

    /**
     * The chosen set with each parameter 20 % down, unchanged and 20 % up, rounded to its step and kept in its range,
     * in every combination but the chosen set itself.
     *
     * @param  array<string, string>  $chosen
     * @return list<array<string, string>>
     */
    private function neighbours(StrategySpec $spec, array $chosen): array
    {
        $values = [];

        foreach ($spec->params as $name => $param) {
            $centre = self::parameterValue($param, $chosen[$name] ?? self::parameterText($param, $param->default));
            $options = [];

            foreach (['0.8', '1', '1.2'] as $factor) {
                $value = $centre->times(Decimal::of($factor));

                if ($param->step !== null && $param->min !== null) {
                    $value = $value->minus($param->min)->dividedBy($param->step, 18)->roundedTo(0)->times($param->step)->plus($param->min);
                }

                $value = $param->min !== null && $value->isLessThan($param->min) ? $param->min : $value;
                $value = $param->max !== null && $value->isGreaterThan($param->max) ? $param->max : $value;
                $options[self::parameterText($param, $value)] = true;
            }

            $values[$name] = array_map('strval', array_keys($options));
        }

        return array_values(array_filter(self::combine($values), fn (array $set): bool => $set !== $chosen));
    }

    /**
     * @param  array<string, list<string>>  $values
     * @return list<array<string, string>>
     */
    private static function combine(array $values): array
    {
        $sets = [[]];

        foreach ($values as $name => $options) {
            $next = [];

            foreach ($sets as $set) {
                foreach ($options as $value) {
                    $next[] = [...$set, $name => $value];
                }
            }

            $sets = $next;

            if (count($sets) > self::MAX_SETS * 1000) {
                break;
            }
        }

        return $sets;
    }

    /**
     * @param  array<array-key, mixed>  $sets
     * @return list<array<string, string>>
     */
    private static function setList(array $sets): array
    {
        $list = [];

        foreach ($sets as $raw) {
            $set = [];

            foreach (is_array($raw) ? $raw : [] as $name => $value) {
                $set[(string) $name] = is_string($value) ? $value : '';
            }

            $list[] = $set;
        }

        return $list;
    }

    private static function keepsProfitFactor(SetOutcome $outcome): bool
    {
        $nets = array_map(fn (array $trip): Decimal => $trip['net'], $outcome->trips);
        $factor = Gates::profitFactor($nets);

        return $factor === null
            ? $nets !== [] && Statistics::sum($nets)->isPositive()
            : ! $factor->isLessThan(Decimal::of(Gates::ROBUST_PROFIT_FACTOR));
    }

    /** @param array<string, mixed> $validation */
    private static function probability(array $validation): ?Decimal
    {
        $dsr = $validation['deflated_sharpe'] ?? null;

        return is_array($dsr) && ($dsr['probability'] ?? null) instanceof Decimal ? $dsr['probability'] : null;
    }

    /** @return list<Candle> daily candles of the instrument, built from its stored minutes */
    private function dailyCandles(ConnectionInterface $db, Symbol $symbol, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $instrument = self::instrument($symbol) ?? throw new InvalidArgumentException("{$symbol} is not a stored instrument.");
        $rows = $db->select(
            "select date_trunc('day', open_time at time zone 'UTC') as day,
                (array_agg(open order by open_time))[1] as open, max(high) as high, min(low) as low,
                (array_agg(close order by open_time desc))[1] as close, sum(volume) as volume, sum(quote_volume) as quote_volume,
                sum(trade_count) as trades, sum(taker_buy_volume) as taker
            from candles where instrument_id = ? and timeframe = '1m' and open_time >= ? and open_time < ?
            group by 1 order by 1",
            [$instrument->id, $from, $to],
        );
        $text = fn (object $row, string $key): string => is_scalar($row->{$key} ?? null) ? (string) $row->{$key} : '0';

        return array_values(array_map(fn (object $row): Candle => Candle::fromStrings(
            CarbonImmutable::parse($text($row, 'day'), 'UTC'), Timeframe::OneDay, $text($row, 'open'), $text($row, 'high'), $text($row, 'low'), $text($row, 'close'),
            $text($row, 'volume'), $text($row, 'quote_volume'), (int) $text($row, 'trades'), $text($row, 'taker'),
        ), array_filter($rows, 'is_object')));
    }

    private function seed(): int
    {
        $seed = is_string($this->option('seed')) ? $this->option('seed') : '';

        return preg_match('/^\d{1,9}$/', $seed) === 1 ? (int) $seed : throw new InvalidArgumentException('--seed is a whole number.');
    }

    /**
     * @param  list<array{gate: string, passed: bool, value: string, threshold: string, advice: string}>  $checks
     * @param  array<string, array{trades: int, net: Decimal, expectancy: ?Decimal}>  $regimes
     * @param  array<string, array<string, array{p5: Decimal, p50: Decimal, p95: Decimal}>>  $monteCarlo
     */
    private function show(array $checks, array $regimes, array $monteCarlo, bool $passed, string $label, int $id): void
    {
        $this->table(['Gate', 'Value', 'Threshold', 'Result', 'What would have to change'], array_map(fn (array $c): array => [
            $c['gate'], $c['value'], $c['threshold'], $c['passed'] ? 'pass' : 'FAIL', $c['advice'],
        ], $checks));
        $this->table(['Regime at entry', 'Trades', 'Net', 'Expectancy'], array_map(fn (string $name, array $r): array => [
            $name, (string) $r['trades'], (string) $r['net']->roundedTo(2), $r['expectancy'] === null ? 'n/a' : (string) $r['expectancy']->roundedTo(4),
        ], array_keys($regimes), $regimes));

        foreach ($monteCarlo as $method => $measures) {
            $this->line(sprintf('Monte Carlo, %s: return %s / %s / %s, drawdown %s / %s / %s (percentiles 5, 50, 95).', str_replace('_', ' ', $method),
                ...array_map(fn (Decimal $v): string => (string) $v->roundedTo(4), [
                    $measures['return']['p5'], $measures['return']['p50'], $measures['return']['p95'],
                    $measures['drawdown']['p5'], $measures['drawdown']['p50'], $measures['drawdown']['p95'],
                ])));
        }

        $passed
            ? $this->info("{$label} passes every gate (report {$id}). php artisan strategy:promote {$label} moves it to Backtested.")
            : $this->warn("{$label} fails ".count(array_filter($checks, fn (array $c): bool => ! $c['passed'])).' of '.count($checks)." gates (report {$id}); it stays where it is.");
    }
}
