<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\MarketType;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\FundingRateRepository;
use App\Market\Quality\QualityCheck;
use App\Portfolio\Data\RoundTrip;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\ParticipantResult;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Metrics\Performance;
use App\Simulation\Metrics\Statistics;
use App\Simulation\Models\StrategyTrial;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Validation\Cscv;
use App\Simulation\Validation\DeflatedSharpe;
use App\Simulation\Validation\WalkForward;
use App\Simulation\Validation\WalkForwardSet;
use App\Simulation\Validation\WalkForwardWindow;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\Types\Kind;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;

/**
 * Runs every parameter set of a grid over the same candles in one pass (D-57), then validates the search
 * (docs/simulation.md, section 5): every set is a trial of the strategy's family, the best set's Sharpe ratio is
 * deflated by the number and spread of the family's trials (D-58), and the probability of backtest overfitting is
 * estimated by CSCV over the sets' daily returns.
 */
final class GridBacktest extends Command
{
    use PreparesBacktests;

    private const int MAX_SETS = 400;

    protected $signature = 'backtest:grid
        {strategy? : A saved version, such as ema-cross-scalp-long@v1}
        {--file= : A strategy document with declared parameters, instead of a saved version}
        {--from= : First day of the window, YYYY-MM-DD}
        {--to= : Day after the last one, YYYY-MM-DD}
        {--grid=* : Values of a parameter, as fast=5,7,9 or slow=15..34:3 (default: its range and step)}
        {--equity= : Initial quote balance (default from config/simulation.php)}
        {--walk-forward : Also walk forward over the sets (docs/simulation.md, section 5)}
        {--in=12m : In-sample length, months (12m) or days (20d)}
        {--out=3m : Out-of-sample length}
        {--step=3m : Step between windows}
        {--min-trades=100 : Trades a set needs in-sample to be chosen}';

    protected $description = 'Backtest every parameter set of a strategy document in one pass, with DSR and PBO';

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, Clock $clock, ConnectionInterface $db): int
    {
        try {
            $file = $this->option('file');
            $label = $this->argument('strategy');
            $policyLimits = RiskPolicy::account($this->section($config, 'risk.account'));
            $loaded = $this->loadDocument($config, $clock, is_string($label) ? $label : null, is_string($file) ? $file : null);

            if ($loaded === null) {
                return self::FAILURE;
            }

            [$spec, $version] = $loaded;
            $sets = $this->sets($spec);
            $walk = $this->option('walk-forward') === true ? $this->walkOptions() : null;
            $strategies = array_map(fn (array $set): Strategy => CompiledStrategy::fromSpec($spec, $set, $version), $sets);
            $from = $this->day('from');
            $to = $this->day('to');
            $symbols = self::followed($strategies[0]);
            $rules = $this->rules($symbols);
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $warmupFrom = $from;

        foreach ($strategies as $strategy) {
            $start = self::warmupFrom($strategy, $from);
            $warmupFrom = $start < $warmupFrom ? $start : $warmupFrom;
        }

        foreach ($symbols as $symbol) {
            $problem = $this->coverage($quality, $symbol, $warmupFrom, $to) ?? $this->fundingCoverage($funding, $symbol, $warmupFrom, $to);

            if ($problem !== null) {
                $this->error("{$symbol}: {$problem}. Backfill it before backtesting (market:backfill).");

                return self::FAILURE;
            }
        }

        $policy = $spec->benchmark ? RiskPolicy::benchmark() : $policyLimits;
        $equity = Money::of(is_string($this->option('equity')) ? $this->option('equity') : $this->text($config, 'simulation.initial_equity'), Asset::of($this->text($config, 'simulation.quote')));
        [$spotBalance, $futuresBalance] = $this->wallets($config, $symbols, $equity);
        $costs = $this->section($config, 'simulation.cost_model');
        $fees = $this->section($config, 'simulation.fees');
        $perpetual = $this->section($config, 'simulation.perpetual');
        $perpetualFees = is_array($perpetual['fees'] ?? null) ? $perpetual['fees'] : [];
        $definition = new RunDefinition(
            strategy: $strategies[0]->identity(),
            market: $futuresBalance === null ? MarketType::Spot : MarketType::Perp,
            symbols: $symbols,
            from: $from,
            to: $to,
            warmupFrom: $warmupFrom,
            initialBalance: $spotBalance,
            fees: new FeeSchedule(self::decimal($fees, 'maker'), self::decimal($fees, 'taker')),
            costs: new CostModel(is_int($costs['version'] ?? null) ? $costs['version'] : 0, self::decimal($costs, 'half_spread'), self::decimal($costs, 'impact'), self::decimal($costs, 'limit_volume_share')),
            policy: $policy,
            futuresBalance: $futuresBalance,
            perpetualFees: $futuresBalance === null ? null : new FeeSchedule(self::decimal($perpetualFees, 'maker'), self::decimal($perpetualFees, 'taker')),
            maintenanceRate: $futuresBalance === null ? null : self::decimal($perpetual, 'maintenance_margin_rate'),
        );
        $participants = array_map(
            fn (Strategy $strategy): Participant => new Participant(self::label($strategy->identity()->parameters), $strategy, $policy, self::warmupFrom($strategy, $from)),
            $strategies,
        );

        $this->info(count($participants)." parameter sets of {$spec->id} on ".implode(', ', array_map('strval', $symbols))." from {$from->toDateString()} to {$to->toDateString()}, in one pass...");
        $started = $clock->now();
        $run = (new Backtest(new DatabaseCandleFeed($db)))->run($definition, $participants[0], $rules, array_slice($participants, 1));
        $results = [$run->main, ...$run->benchmarks];
        $rows = $this->rows($results);
        $this->recordTrials($spec, $definition, $rows, $from, $to, $clock);
        $validation = $this->validate($spec, $rows);
        $this->show($rows, $validation);

        if ($walk !== null) {
            try {
                $validation['walk_forward'] = $this->walkForward($results, $from, $to, $walk);
            } catch (InvalidArgumentException $failure) {
                $this->error($failure->getMessage());

                return self::FAILURE;
            }
        }
        $artifact = $this->store(
            $this->text($config, 'simulation.artifacts'),
            'grid-'.substr(hash('sha256', CanonicalJson::encode(['definition' => $definition->toArray(), 'rows' => $rows, 'validation' => $validation])), 0, 16),
            CanonicalJson::encode(['definition' => $definition->toArray(), 'data' => ['fingerprint' => $run->fingerprint], 'sets' => $rows, 'validation' => $validation]),
        );
        $this->line("Stored in {$artifact}, {$run->candles} candles read in ".number_format((int) $started->diffInMilliseconds($clock->now()) / 1000, 1).' s.');

        return self::SUCCESS;
    }

    /**
     * Every combination of the values each parameter takes: --grid values, or its declared range and step.
     *
     * @return list<array<string, string>>
     */
    private function sets(StrategySpec $spec): array
    {
        $values = [];

        foreach ($spec->params as $name => $param) {
            $options = $param->min !== null && $param->max !== null && $param->step !== null
                ? self::span($param->min, $param->max, $param->step)
                : [$param->default];
            $values[$name] = array_map(fn (Decimal $v): string => $param->kind === Kind::Percent ? $v->times(Decimal::of(100))->roundedTo(6).'%' : (string) $v, $options);
        }

        foreach ($this->strings('grid') as $option) {
            [$name, $list] = array_pad(explode('=', $option, 2), 2, '');

            if (! isset($spec->params[$name])) {
                throw new InvalidArgumentException("{$spec->id} has no parameter {$name}. Parameters: ".implode(', ', array_keys($spec->params)).'.');
            }

            if (preg_match('/^(-?\d+(?:\.\d+)?)\.\.(-?\d+(?:\.\d+)?):(\d+(?:\.\d+)?)$/', $list, $range) === 1 && Decimal::of($range[3])->isPositive()) {
                $values[$name] = array_map('strval', self::span(Decimal::of($range[1]), Decimal::of($range[2]), Decimal::of($range[3])));
            } elseif (preg_match('/^-?\d+(\.\d+)?%?(,-?\d+(\.\d+)?%?)*$/', $list) === 1) {
                $values[$name] = explode(',', $list);
            } else {
                throw new InvalidArgumentException("{$option}: write values as 5,7,9 or 5..13:2.");
            }
        }

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

        if (count($sets) > self::MAX_SETS) {
            throw new InvalidArgumentException(count($sets).' parameter sets; at most '.self::MAX_SETS.' run in one pass.');
        }

        return $sets;
    }

    /** @return list<Decimal> */
    private static function span(Decimal $min, Decimal $max, Decimal $step): array
    {
        $values = [];

        for ($value = $min; ! $value->isGreaterThan($max) && count($values) <= 100_000; $value = $value->plus($step)) {
            $values[] = $value;
        }

        return $values;
    }

    /**
     * @param  list<ParticipantResult>  $results
     * @return list<array{parameters: array<string, string>, return: Decimal, sharpe: ?Decimal, daily_sharpe: ?Decimal, max_drawdown: Decimal, trades: int, profit_factor: ?Decimal, expectancy: ?Decimal, daily: list<Decimal>}>
     */
    private function rows(array $results): array
    {
        $rows = [];

        foreach ($results as $result) {
            $metrics = Performance::measure($result->equity, $result->ledger);
            $daily = Performance::dailyReturns($result->equity);
            $rows[] = [
                'parameters' => $result->participant->strategy->identity()->parameters,
                'return' => $metrics->return,
                'sharpe' => $metrics->sharpe,
                'daily_sharpe' => count($daily) >= 2 ? DeflatedSharpe::sharpe($daily) : null,
                'max_drawdown' => $metrics->maxDrawdown,
                'trades' => $metrics->trades->count,
                'profit_factor' => $metrics->trades->profitFactor,
                'expectancy' => $metrics->trades->expectancy,
                'daily' => $daily,
            ];
        }

        return $rows;
    }

    /** @param list<array{parameters: array<string, string>, daily_sharpe: ?Decimal, daily: list<Decimal>}> $rows */
    private function recordTrials(StrategySpec $spec, RunDefinition $definition, array $rows, CarbonImmutable $from, CarbonImmutable $to, Clock $clock): void
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
    private function validate(StrategySpec $spec, array $rows): array
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

    /**
     * @param  list<array{parameters: array<string, string>, return: Decimal, sharpe: ?Decimal, max_drawdown: Decimal, trades: int, profit_factor: ?Decimal, expectancy: ?Decimal}>  $rows
     * @param  array<string, mixed>  $validation
     */
    private function show(array $rows, array $validation): void
    {
        $percent = fn (?Decimal $v): string => $v === null ? 'n/a' : $v->times(Decimal::of(100))->roundedTo(2).' %';
        $number = fn (?Decimal $v): string => $v === null ? 'n/a' : (string) $v->roundedTo(3);
        usort($rows, fn (array $a, array $b): int => ($b['sharpe'] ?? Decimal::of(-1000))->compare($a['sharpe'] ?? Decimal::of(-1000)));
        $this->table(
            ['Parameters', 'Return', 'Sharpe', 'Max drawdown', 'Trades', 'Profit factor', 'Expectancy'],
            array_map(fn (array $row): array => [
                self::label($row['parameters']), $percent($row['return']), $number($row['sharpe']), $percent($row['max_drawdown']),
                (string) $row['trades'], $number($row['profit_factor']), $number($row['expectancy']),
            ], $rows),
        );

        $dsr = $validation['deflated_sharpe'] ?? null;

        if (is_array($dsr) && ($dsr['probability'] ?? null) instanceof Decimal && is_int($dsr['trials'] ?? null)) {
            $probability = $dsr['probability'];
            $this->line(sprintf(
                'Deflated Sharpe ratio of the best set (%s): %s, over %d trials of the family; the gate wants at least 0.95: %s.',
                is_string($validation['best']) ? $validation['best'] : '',
                $probability->roundedTo(4), $dsr['trials'], $probability->isLessThan(Decimal::of('0.95')) ? 'fails' : 'passes',
            ));
        }

        $pbo = $validation['pbo'] ?? null;

        if (is_array($pbo) && ($pbo['probability'] ?? null) instanceof Decimal && is_int($pbo['combinations'] ?? null)) {
            $this->line(sprintf(
                'Probability of backtest overfitting: %s over %d splits of %d days; above 0.5 the best in-sample set is more often than not below the median out of sample.',
                $pbo['probability']->roundedTo(4), $pbo['combinations'], is_int($pbo['days'] ?? null) ? $pbo['days'] : 0,
            ));
        }

        foreach (is_array($validation['notes'] ?? null) ? $validation['notes'] : [] as $note) {
            $this->warn(is_string($note) ? $note : '');
        }
    }

    /** @return array{in: string, out: string, step: string, min: int} */
    private function walkOptions(): array
    {
        $text = fn (string $name): string => is_string($this->option($name)) ? $this->option($name) : '';
        [$in, $out, $step] = [$text('in'), $text('out'), $text('step')];

        foreach ([$in, $out, $step] as $length) {
            if (preg_match('/^[1-9]\d{0,3}[md]$/', $length) !== 1) {
                throw new InvalidArgumentException('--in and --out are whole numbers of days or months, such as 12m or 20d.');
            }
        }

        $min = $text('min-trades');

        if (preg_match('/^[1-9]\d{0,5}$/', $min) !== 1) {
            throw new InvalidArgumentException('--min-trades is a whole number from 1.');
        }

        return ['in' => $in, 'out' => $out, 'step' => $step, 'min' => (int) $min];
    }

    /**
     * Walks forward over the sets' continuous runs (D-57) and shows every window and the stitched result.
     *
     * @param  list<ParticipantResult>  $results
     * @param  array{in: string, out: string, step: string, min: int}  $walk
     * @return array<string, mixed>
     */
    private function walkForward(array $results, CarbonImmutable $from, CarbonImmutable $to, array $walk): array
    {
        $sets = array_map(fn (ParticipantResult $result): WalkForwardSet => new WalkForwardSet(
            $result->participant->strategy->identity()->parameters,
            array_map(fn (RoundTrip $trip): array => ['opened' => $trip->openedAt, 'closed' => $trip->closedAt, 'net' => $trip->netProfit], $result->ledger->roundTrips()),
        ), $results);
        $result = WalkForward::evaluate($sets, $from, $to, $walk['in'], $walk['out'], $walk['step'], $walk['min']);
        $words = fn (string $length): string => substr($length, 0, -1).(str_ends_with($length, 'm') ? ' months' : ' days');
        $number = fn (?Decimal $value): string => $value === null ? 'n/a' : (string) $value->roundedTo(3);

        $this->line(sprintf(
            'Walk-forward: %d windows of %s in-sample and %s out of sample, stepped by %s; a set needs %d trades in-sample.',
            count($result->windows), $words($walk['in']), $words($walk['out']), $words($walk['step']), $walk['min'],
        ));
        $this->table(['Out of sample', 'Chosen in-sample', 'In-sample expectancy', 'Trades', 'Net'], array_map(fn (WalkForwardWindow $w): array => [
            "{$w->outFrom->toDateString()} to {$w->outTo->toDateString()}",
            $w->chosen === null ? 'none traded enough' : self::label($w->chosen),
            $number($w->inExpectancy),
            (string) $w->outTrades.($w->startedEarlier > 0 ? " ({$w->startedEarlier} opened before)" : ''),
            $number($w->outNet),
        ], $result->windows));
        $this->line(sprintf(
            'Out of sample: %d trades, net %s, expectancy %s (95 %% interval %s to %s), profit factor %s; %d of %d positive windows (the gate wants 2 of every 3).',
            count($result->outOfSample), $number($result->net), $number($result->expectancy),
            $number($result->interval['low'] ?? null), $number($result->interval['high'] ?? null),
            $number($result->profitFactor), $result->positiveWindows, count($result->windows),
        ));

        return [
            'windows' => array_map(fn (WalkForwardWindow $w): array => [
                'in_from' => $w->inFrom, 'out_from' => $w->outFrom, 'out_to' => $w->outTo, 'chosen' => $w->chosen,
                'in_expectancy' => $w->inExpectancy, 'in_trades' => $w->inTrades, 'out_trades' => $w->outTrades,
                'out_net' => $w->outNet, 'opened_before' => $w->startedEarlier,
            ], $result->windows),
            'trades' => count($result->outOfSample),
            'net' => $result->net,
            'expectancy' => $result->expectancy,
            'interval' => $result->interval,
            'profit_factor' => $result->profitFactor,
            'positive_windows' => $result->positiveWindows,
        ];
    }

    /** @param array<string, string> $parameters */
    private static function label(array $parameters): string
    {
        return implode(' ', array_map(fn (string $name, string $value): string => "{$name}={$value}", array_keys($parameters), $parameters)) ?: 'defaults';
    }
}
