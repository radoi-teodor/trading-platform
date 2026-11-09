<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\Deployment;
use App\Simulation\Models\StrategyGateReport;
use App\Strategy\Versions\StrategyStage;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;

/**
 * Moves a version from Draft to Backtested when its latest gate report passes every gate (docs/risk-policy.md,
 * section 6). It never moves a version into paper or any live stage: paper follows the gates in phase 4, and live
 * needs the operator's explicit approval (D-16).
 */
final class PromoteStrategy extends Command
{
    use PreparesBacktests;

    protected $signature = 'strategy:promote {strategy : A saved version, such as donchian-breakout@v1}';

    protected $description = 'Move a version from Draft to Backtested when its latest gate report passes';

    public function handle(Repository $config, Clock $clock): int
    {
        try {
            $label = $this->stringArgument('strategy');
            $version = $this->versions($config, $clock)->find($label) ?? throw new InvalidArgumentException("{$label} is not a saved version.");
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        if ($version->stage() !== 'draft') {
            $this->error("{$label} is {$version->stage()}, not draft; only a draft moves to backtested here.");

            return self::FAILURE;
        }

        $report = StrategyGateReport::query()->where('strategy_version_id', $version->id)->orderByDesc('id')->first();

        if ($report === null) {
            $this->error("{$label} has no gate report: run php artisan strategy:gates {$label} first.");

            return self::FAILURE;
        }

        if (! $report->passed) {
            $failed = array_filter(is_array($report->report['gates'] ?? null) ? $report->report['gates'] : [], fn (mixed $g): bool => is_array($g) && ($g['passed'] ?? false) !== true);
            $this->error("{$label} failed its last gate report ({$report->id}): ".implode(', ', array_map(fn (mixed $g): string => is_array($g) && is_string($g['gate'] ?? null) ? $g['gate'] : '', $failed)).'.');

            return self::FAILURE;
        }

        StrategyStage::query()->create([
            'strategy_version_id' => $version->id, 'stage' => 'backtested', 'reason' => "every gate passed (report {$report->id})", 'created_at' => $clock->now(),
        ]);
        $chosen = [];

        foreach (is_array($report->report['chosen'] ?? null) ? $report->report['chosen'] : [] as $name => $value) {
            $chosen[(string) $name] = is_scalar($value) ? (string) $value : '';
        }

        // Paper follows Backtested automatically, on every paper account (D-71, docs/risk-policy.md section 6).
        $accounts = Account::query()->where('mode', EngineMode::Paper)->orderBy('id')->get();

        foreach ($accounts as $account) {
            Deployment::query()->create([
                'account_id' => $account->id, 'strategy_version_id' => $version->id, 'parameters' => $chosen, 'active' => true,
                'reason' => "backtested, gate report {$report->id}", 'created_at' => $clock->now(),
            ]);
        }

        if ($accounts->isEmpty()) {
            $this->info("{$label} is backtested. No paper account exists yet: create one with account:create, then deploy it there.");

            return self::SUCCESS;
        }

        StrategyStage::query()->create(['strategy_version_id' => $version->id, 'stage' => 'paper', 'reason' => 'deployed on '.$accounts->pluck('name')->implode(', '), 'created_at' => $clock->now()]);
        $this->info("{$label} is backtested and runs in paper on ".$accounts->pluck('name')->implode(', ').' from their next start (D-71). Live needs the operator\'s explicit approval (D-16).');

        return self::SUCCESS;
    }
}
