<?php

declare(strict_types=1);

namespace App\Simulation\Ladder;

use App\Execution\Contracts\Notifier;
use App\Execution\Data\CommandKind;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Journal\DatabaseDecisionLog;
use App\Execution\Models\Account;
use App\Execution\Models\AccountEquity;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\Deployment;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\JournalEntry;
use App\Simulation\Models\StrategyGateReport;
use App\Simulation\Validation\Band;
use App\Strategy\Versions\StrategyStage;
use App\Strategy\Versions\StrategyVersion;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use LogicException;

/**
 * The promotion ladder in code (D-83, D-16, docs/risk-policy.md sections 6 and 7): each deployment's results against
 * its version's latest passing gate report, a step up only when its stage's days and trades are reached in band and
 * the operator approves (effective 24 hours later, D-19), and a step down at once when it leaves the band.
 */
final readonly class Ladder
{
    /** From a stage and size: the next stage, its size, the days and trades needed, and how it reads. */
    private const array STEPS = [
        'paper' => ['live_micro', '0.25', 30, 30, 'live micro, a quarter of the size'],
        'live_micro' => ['live', '0.5', 30, 20, 'live at half size'],
        'live:0.5' => ['live', '1', 30, 20, 'live at full size'],
    ];

    public function __construct(private Clock $clock, private Notifier $notifier, private Repository $config) {}

    public function status(Deployment $deployment): LadderStatus
    {
        $now = $this->clock->now();
        $trades = $this->trades($deployment);
        $days = intdiv($now->getTimestamp() - $deployment->start()->getTimestamp(), 86_400);
        $report = $this->report($deployment);
        $breaches = $report === null ? ['no passing gate report for this version'] : Band::breaches(
            array_map(fn (AccountTrade $t): Decimal => Decimal::of($t->net), $trades),
            array_map(fn (AccountTrade $t): Decimal => Decimal::of($t->fees)->plus(Decimal::of($t->slippage))->dividedBy(Decimal::of($t->quantity)->times(Decimal::of($t->entry_price)), 18), $trades),
            $this->startEquity($deployment), $report, $this->modelCost($trades),
        );
        $step = self::STEPS[$this->key($deployment)] ?? null;
        $missing = $step === null ? null : implode(', ', array_filter([
            $days < $step[2] ? "{$days} of {$step[2]} days" : null,
            count($trades) < $step[3] ? count($trades).' of '.$step[3].' trades' : null,
            $this->promoted($deployment, $step[0], $step[1]) ? 'already promoted' : null,
        ]));

        return new LadderStatus($days, count($trades), $breaches, $step[4] ?? null, $missing === '' ? null : $missing, $step !== null && ($missing === null || $missing === '') && $breaches === []);
    }

    /**
     * One step up, approved by the operator after re-authentication; effective 24 hours later (D-19).
     *
     * @return list<Deployment> the new deployments
     */
    public function promote(Deployment $deployment, string $by): array
    {
        $status = $this->status($deployment);
        $step = self::STEPS[$this->key($deployment)] ?? throw new InvalidArgumentException('It is at full size already.');

        if (Account::query()->where('mode', EngineMode::Live)->doesntExist()) {
            throw new InvalidArgumentException('Open a live account first, on the Settings screen.');
        }

        if (! $status->ready) {
            throw new InvalidArgumentException('Not yet: '.($status->missing ?? implode('; ', $status->breaches)).'.');
        }

        $now = $this->clock->now();
        $effective = $now->addDay();
        $accounts = $this->targets($deployment, $step[0], $step[1]);

        $created = [];

        foreach ($accounts as $account) {
            $created[] = Deployment::query()->create([
                'account_id' => $account->id, 'strategy_version_id' => $deployment->strategy_version_id, 'parameters' => $deployment->parameters, 'active' => true,
                'stage' => $step[0], 'size_multiplier' => $step[1], 'effective_at' => $effective, 'created_at' => $now,
                'reason' => "promoted by {$by} after {$status->days} days and {$status->trades} trades in band",
            ]);
            $this->tell($account, $now, 'promotion', "{$this->label($deployment)} goes {$step[4]} at {$effective->format('Y-m-d H:i')} UTC; restart the engine then");
        }

        if ($deployment->stage !== 'paper') {
            $deployment->update(['ended_at' => $effective]);
        }

        StrategyStage::query()->create(['strategy_version_id' => $deployment->strategy_version_id, 'stage' => $step[0], 'reason' => "{$step[4]} from {$effective->format('Y-m-d H:i')} UTC, approved by {$by}", 'created_at' => $now]);

        return $created;
    }

    /** @return list<string> what was demoted and why, for every deployment in force that left its band */
    public function review(): array
    {
        $now = $this->clock->now();
        $done = [];

        foreach (Account::query()->whereIn('mode', [EngineMode::Paper, EngineMode::Live])->orderBy('id')->get() as $account) {
            foreach (Deployment::inForce($account->id, $now)->orderBy('id')->get() as $deployment) {
                $breaches = $this->status($deployment)->breaches;

                if ($breaches !== [] && $breaches !== ['no passing gate report for this version']) {
                    $done[] = $this->demote($account, $deployment, $breaches);
                }
            }
        }

        return $done;
    }

    /** @param list<string> $breaches */
    private function demote(Account $account, Deployment $deployment, array $breaches): string
    {
        $now = $this->clock->now();
        $label = $this->label($deployment);
        $lower = match ($deployment->stage) {
            'live' => 'live_micro',
            'live_micro' => 'paper',
            default => 'backtested',
        };
        $deployment->update(['active' => false, 'ended_at' => $now, 'reason' => $deployment->reason.'; demoted: '.implode('; ', $breaches)]);

        if ($deployment->stage === 'live') {
            Deployment::query()->create([
                'account_id' => $account->id, 'strategy_version_id' => $deployment->strategy_version_id, 'parameters' => $deployment->parameters, 'active' => true,
                'stage' => 'live_micro', 'size_multiplier' => '0.25', 'effective_at' => $now, 'created_at' => $now, 'reason' => 'demoted from live',
            ]);
        }

        // The running engine pauses the strategy at its next minute; a smaller step applies from its next start.
        EngineCommandRow::query()->create(['account_id' => $account->id, 'kind' => CommandKind::Pause, 'strategy' => $label, 'issued_by' => 'ladder', 'created_at' => $now]);
        StrategyStage::query()->create(['strategy_version_id' => $deployment->strategy_version_id, 'stage' => $lower, 'reason' => 'out of band on '.$account->name.': '.implode('; ', $breaches), 'created_at' => $now]);
        $text = "{$label} demoted to {$lower} on {$account->name}: ".implode('; ', $breaches);
        $this->tell($account, $now, 'demotion', $text);

        return $text;
    }

    private function tell(Account $account, CarbonImmutable $now, string $kind, string $detail): void
    {
        JournalEntry::query()->create([
            'account_id' => $account->id, 'event_at' => $now, 'kind' => 'notice', 'format' => DatabaseDecisionLog::FORMAT,
            'payload' => ['time' => $now->toIso8601ZuluString(), 'kind' => $kind, 'detail' => $detail], 'created_at' => $now,
        ]);
        $this->notifier->engineEvent($account->name, new EngineRecord($now, $kind, $detail));
    }

    /** Whether the step up was approved already: there are accounts to go to and each has it, waiting or in force. */
    private function promoted(Deployment $deployment, string $stage, string $size): bool
    {
        return ($deployment->stage !== 'paper' || Account::query()->where('mode', EngineMode::Live)->exists()) && $this->targets($deployment, $stage, $size) === [];
    }

    /**
     * The accounts a step up goes to: every live account from paper, the same account further up; less those where the
     * version already has that step, waiting or in force, so an approval is never given twice.
     *
     * @return list<Account>
     */
    private function targets(Deployment $deployment, string $stage, string $size): array
    {
        $accounts = $deployment->stage === 'paper'
            ? Account::query()->where('mode', EngineMode::Live)->orderBy('id')->get()->all()
            : [Account::query()->findOrFail($deployment->account_id)];
        $now = $this->clock->now();

        return array_values(array_filter($accounts, fn (Account $account): bool => Deployment::query()
            ->where('account_id', $account->id)->where('strategy_version_id', $deployment->strategy_version_id)
            ->where('stage', $stage)->where('size_multiplier', $size)->where('active', true)
            ->where(fn ($q) => $q->whereNull('ended_at')->orWhere('ended_at', '>', $now))
            ->doesntExist()));
    }

    /** @return list<AccountTrade> the deployment's trades since it took effect, oldest first */
    private function trades(Deployment $deployment): array
    {
        return array_values(AccountTrade::query()->where('account_id', $deployment->account_id)->where('label', $this->label($deployment))
            ->where('closed_at', '>=', $deployment->start())->orderBy('closed_at')->get()->all());
    }

    /** @return array<array-key, mixed>|null */
    private function report(Deployment $deployment): ?array
    {
        $report = StrategyGateReport::query()->where('strategy_version_id', $deployment->strategy_version_id)->where('passed', true)->orderByDesc('id')->first();

        return $report === null ? null : $report->report;
    }

    private function startEquity(Deployment $deployment): Decimal
    {
        $point = AccountEquity::query()->where('account_id', $deployment->account_id)->where('at', '<=', $deployment->start())->orderByDesc('at')->first();

        if ($point !== null) {
            return Decimal::of($point->equity);
        }

        $account = Account::query()->findOrFail($deployment->account_id);

        return Decimal::of($account->spot_balance)->plus(Decimal::of($account->futures_balance));
    }

    /** @param list<AccountTrade> $trades what the cost model charges a round trip, over the notional: twice the taker fee and the half spread */
    private function modelCost(array $trades): Decimal
    {
        $perpetual = $trades !== [] && str_contains($trades[0]->symbol, ':');
        $fee = $this->config->get($perpetual ? 'simulation.perpetual.fees.taker' : 'simulation.fees.taker');
        $spread = $this->config->get('simulation.cost_model.half_spread');

        return Decimal::of(is_string($fee) ? $fee : '0')->plus(Decimal::of(is_string($spread) ? $spread : '0'))->times(Decimal::of(2));
    }

    private function key(Deployment $deployment): string
    {
        return $deployment->stage === 'live' ? 'live:'.(string) Decimal::of($deployment->size_multiplier) : $deployment->stage;
    }

    private function label(Deployment $deployment): string
    {
        $version = StrategyVersion::query()->find($deployment->strategy_version_id) ?? throw new LogicException("Deployment {$deployment->id} has no version.");

        return $version->label();
    }
}
