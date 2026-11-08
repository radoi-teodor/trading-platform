<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Market\FundingRateRepository;
use App\Market\Instruments\StoredInstruments;
use App\Market\Quality\QualityCheck;
use App\Risk\RiskPolicy;
use App\Simulation\Data\SetOutcome;
use App\Simulation\Report\CanonicalJson;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/** One worker's share of a grid (backtest:grid --workers): runs the sets of a job file and writes their outcomes. */
final class GridPart extends Command
{
    use PreparesBacktests;

    protected $signature = 'backtest:grid-part {job : A job file written by backtest:grid or strategy:gates}';

    protected $description = 'Run a share of a grid for a parent process';

    protected $hidden = true;

    public function handle(Repository $config, QualityCheck $quality, FundingRateRepository $funding, ConnectionInterface $db): int
    {
        $path = $this->stringArgument('job');
        $job = json_decode(is_file($path) ? (string) file_get_contents($path) : '', true);

        if (! is_array($job) || ! is_string($job['document'] ?? null) || ! is_array($job['sets'] ?? null) || ! is_string($job['out'] ?? null)) {
            throw new RuntimeException("{$path} is not a grid job.");
        }

        $policy = RiskPolicy::account($this->section($config, 'risk.account'));
        $spec = DocumentChecker::check($job['document'], new StoredInstruments, new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions))->spec
            ?? throw new RuntimeException('The job\'s document does not compile.');
        $text = fn (string $key): string => is_string($job[$key] ?? null) ? $job[$key] : '';
        $sets = [];

        foreach ($job['sets'] as $raw) {
            $set = [];

            foreach (is_array($raw) ? $raw : [] as $name => $value) {
                $set[(string) $name] = is_string($value) ? $value : '';
            }

            $sets[] = $set;
        }

        $run = $this->runSets(
            $config, $quality, $funding, $db, $spec, $job['document'], $sets, is_int($job['version'] ?? null) ? $job['version'] : 1,
            CarbonImmutable::parse($text('from'), 'UTC'), CarbonImmutable::parse($text('to'), 'UTC'), $text('equity'), ($job['stressed'] ?? false) === true,
            warmupFrom: CarbonImmutable::parse($text('warmup_from'), 'UTC'),
        );
        file_put_contents($job['out'], CanonicalJson::encode([
            'outcomes' => array_map(fn (SetOutcome $o): array => $o->toArray(), $run['outcomes']),
            'fingerprint' => $run['fingerprint'],
            'candles' => $run['candles'],
        ]));

        return self::SUCCESS;
    }
}
