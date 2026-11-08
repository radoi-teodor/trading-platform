<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Data\MarketType;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\FundingRateRepository;
use App\Market\Quality\QualityCheck;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Metrics\Performance;
use App\Simulation\Models\BacktestRun;
use App\Simulation\Models\StrategyTrial;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Report\Report;
use App\Simulation\Report\ReportBuilder;
use App\Simulation\Validation\DeflatedSharpe;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;

/** Runs a hand-coded strategy over stored history with its benchmarks and stores the report (docs/simulation.md). */
final class RunBacktest extends Command
{
    use PreparesBacktests;

    protected $signature = 'backtest:run
        {strategy? : A strategy name from config/simulation.php, or a saved version such as ema-cross-scalp-long@v1}
        {--file= : A strategy document (docs/strategy/configurator.md) instead of a name}
        {--symbol=* : Unified symbols, such as BTC/USDT (default BTC/USDT)}
        {--from= : First day of the window, YYYY-MM-DD}
        {--to= : Day after the last one, YYYY-MM-DD}
        {--param=* : Strategy parameters as key=value}
        {--equity= : Initial quote balance (default from config/simulation.php)}
        {--no-benchmarks : Skip the comparisons}
        {--json : End with a one-line JSON summary, for background runs (D-62)}';

    protected $description = 'Backtest a strategy on stored 1-minute candles and store its report';

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, Clock $clock, ConnectionInterface $db): int
    {
        $started = $clock->now();

        try {
            $file = $this->option('file');
            $label = str_contains($this->stringArgument('strategy'), '@v') ? $this->stringArgument('strategy') : null;
            $fromDocument = is_string($file) || $label !== null;

            if ($fromDocument) {
                $loaded = $this->document($config, $clock, $label, is_string($file) ? $file : null);

                if ($loaded === null) {
                    return self::FAILURE;
                }

                [$name, $strategy, $profile] = $loaded;
            } else {
                [$name, $class, $profile] = $this->registered($config, $this->stringArgument('strategy'));
                $symbols = array_map(fn (string $s): Symbol => Symbol::parse($s), $this->strings('symbol') ?: ['BTC/USDT']);
                $strategy = $class::build($this->parameters($class::defaults()), $symbols);
            }

            $from = $this->day('from');
            $to = $this->day('to');
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

        if ($fromDocument) {
            // Every backtest of a document is a trial of its family, for the deflated Sharpe ratio (D-58).
            $daily = Performance::dailyReturns($result->main->equity);
            $sharpe = count($daily) >= 2 ? DeflatedSharpe::sharpe($daily) : null;
            StrategyTrial::query()->create([
                'family' => $name,
                'strategy' => $name,
                'parameters' => $strategy->identity()->parameters,
                'definition_hash' => $definition->hash(),
                'window_from' => $from,
                'window_to' => $to,
                'daily_sharpe' => $sharpe === null ? null : (string) $sharpe,
                'observations' => count($daily),
                'created_at' => $finished,
            ]);
        }

        $this->summary($report);
        $this->line("Report {$hash}");
        $this->line("Stored in {$artifact}, {$result->candles} candles read in ".number_format((int) $started->diffInMilliseconds($finished) / 1000, 1).' s.');

        if ($this->option('json') === true) {
            $benchmarks = [];

            foreach (is_array($report->content['benchmarks'] ?? null) ? $report->content['benchmarks'] : [] as $label => $benchmark) {
                $benchmarks[(string) $label] = is_array($benchmark) ? ($benchmark['missing'] ?? $benchmark['return'] ?? null) : null;
            }

            $this->line(CanonicalJson::encode([
                'report' => $hash,
                'artifact' => $artifact,
                'strategy' => $strategy->identity()->label(),
                'parameters' => $strategy->identity()->parameters,
                'metrics' => [
                    'return' => $metrics->return, 'cagr' => $metrics->cagr, 'max_drawdown' => $metrics->maxDrawdown,
                    'sharpe' => $metrics->sharpe, 'sortino' => $metrics->sortino, 'time_in_market' => $metrics->timeInMarket,
                    'trades' => $metrics->trades->count, 'win_rate' => $metrics->trades->winRate,
                    'profit_factor' => $metrics->trades->profitFactor, 'expectancy' => $metrics->trades->expectancy,
                    'fees' => $metrics->trades->fees, 'funding' => $metrics->trades->funding,
                ],
                'benchmarks' => $benchmarks,
            ]));
        }

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

    /**
     * Compiles a strategy document with the --param values; prints its errors and returns null when it does not.
     *
     * @return array{string, Strategy, string}|null
     */
    private function document(Repository $config, Clock $clock, ?string $label, ?string $file): ?array
    {
        if ($this->strings('symbol') !== []) {
            throw new InvalidArgumentException('A strategy document names its own instruments; leave out --symbol.');
        }

        $loaded = $this->loadDocument($config, $clock, $label, $file);

        if ($loaded === null) {
            return null;
        }

        [$spec, $version] = $loaded;

        $parameters = [];

        foreach ($this->strings('param') as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $parameters[$key] = $value;
        }

        return [$spec->id, CompiledStrategy::fromSpec($spec, $parameters, $version), $spec->benchmark ? 'benchmark' : 'account'];
    }
}
