<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Market\FundingRateRepository;
use App\Market\Quality\QualityCheck;
use App\Simulation\Data\SetOutcome;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Validation\Cscv;
use App\Simulation\Validation\WalkForward;
use App\Simulation\Validation\WalkForwardSet;
use App\Simulation\Validation\WalkForwardWindow;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Process\Factory as Processes;
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
    use ReadsGridOptions;

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
        {--min-trades=100 : Trades a set needs in-sample to be chosen}
        {--workers=1 : Child processes to split the sets over; the result does not depend on it}
        {--json : End with a one-line JSON summary, for background runs (D-62)}';

    protected $description = 'Backtest every parameter set of a strategy document in one pass, with DSR and PBO';

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, Clock $clock, ConnectionInterface $db, Processes $processes): int
    {
        try {
            $file = $this->option('file');
            $label = $this->argument('strategy');
            $loaded = $this->loadDocument($config, $clock, is_string($label) ? $label : null, is_string($file) ? $file : null);

            if ($loaded === null) {
                return self::FAILURE;
            }

            [$spec, $version, $document] = $loaded;
            $sets = $this->sets($spec);
            $walk = $this->option('walk-forward') === true ? $this->walkOptions() : null;
            $workers = $this->workers();
            $from = $this->day('from');
            $to = $this->day('to');
            $equity = is_string($this->option('equity')) ? $this->option('equity') : $this->text($config, 'simulation.initial_equity');
            $this->info(count($sets)." parameter sets of {$spec->id} on ".self::traded($spec)." from {$from->toDateString()} to {$to->toDateString()}, in one pass"
                .($workers > 1 ? " split over {$workers} workers" : '').'...');
            $started = $clock->now();
            $run = $this->runSets($config, $quality, $funding, $db, $spec, $document, $sets, $version, $from, $to, $equity, workers: $workers, processes: $processes);
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $definition = $run['definition'];
        $rows = self::rows($run['outcomes']);
        $this->recordTrials($spec, $definition, $rows, $from, $to, $clock);
        $validation = $this->validate($spec, $rows);
        $this->show($rows, $validation);

        if ($walk !== null) {
            try {
                $validation['walk_forward'] = $this->walkForward($run['outcomes'], $from, $to, $walk);
            } catch (InvalidArgumentException $failure) {
                $this->error($failure->getMessage());

                return self::FAILURE;
            }
        }
        $artifact = $this->store(
            $this->text($config, 'simulation.artifacts'),
            'grid-'.substr(hash('sha256', CanonicalJson::encode(['definition' => $definition->toArray(), 'rows' => $rows, 'validation' => $validation])), 0, 16),
            CanonicalJson::encode(['definition' => $definition->toArray(), 'data' => ['fingerprint' => $run['fingerprint']], 'sets' => $rows, 'validation' => $validation]),
        );
        $this->line("Stored in {$artifact}, {$run['candles']} candles read in ".number_format((int) $started->diffInMilliseconds($clock->now()) / 1000, 1).' s.');

        if ($this->option('json') === true) {
            $this->line(CanonicalJson::encode([
                'artifact' => $artifact,
                'strategy' => $definition->strategy->label(),
                'sets' => array_map(fn (array $row): array => array_diff_key($row, ['daily' => true]), $rows),
                'validation' => $validation,
            ]));
        }

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
            $values[$name] = array_map(fn (Decimal $v): string => self::parameterText($param, $v), $options);
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

    /**
     * @param  list<SetOutcome>  $outcomes
     * @return list<array{parameters: array<string, string>, return: Decimal, sharpe: ?Decimal, daily_sharpe: ?Decimal, max_drawdown: Decimal, trades: int, profit_factor: ?Decimal, expectancy: ?Decimal, daily: list<Decimal>}>
     */
    public static function rows(array $outcomes): array
    {
        return array_map(fn (SetOutcome $o): array => [
            'parameters' => $o->parameters,
            'return' => $o->return,
            'sharpe' => $o->sharpe,
            'daily_sharpe' => $o->dailySharpe,
            'max_drawdown' => $o->maxDrawdown,
            'trades' => $o->trades,
            'profit_factor' => $o->profitFactor,
            'expectancy' => $o->expectancy,
            'daily' => $o->daily,
        ], $outcomes);
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

    /**
     * Walks forward over the sets' continuous runs (D-57) and shows every window and the stitched result.
     *
     * @param  list<SetOutcome>  $outcomes
     * @param  array{in: string, out: string, step: string, min: int}  $walk
     * @return array<string, mixed>
     */
    private function walkForward(array $outcomes, CarbonImmutable $from, CarbonImmutable $to, array $walk): array
    {
        $sets = array_map(fn (SetOutcome $o): WalkForwardSet => new WalkForwardSet($o->parameters, $o->trips), $outcomes);
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
}
