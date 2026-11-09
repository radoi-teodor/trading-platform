<?php

declare(strict_types=1);

namespace App\Execution\Accounts;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\LiveApproval;
use App\Support\Clock\Clock;
use InvalidArgumentException;

/**
 * The operator's approval of a live account (D-82, D-16): given after re-authentication, effective 24 hours later
 * because it raises risk (D-19), and withdrawable at once because withdrawing lowers it.
 */
final readonly class LiveGate
{
    public const int DELAY_SECONDS = 86_400;

    public function __construct(private Clock $clock) {}

    public function approve(Account $account, string $by): LiveApproval
    {
        if ($account->mode !== EngineMode::Live) {
            throw new InvalidArgumentException("{$account->name} is a {$account->mode->value} account; only a live account needs approval.");
        }

        $now = $this->clock->now();

        return LiveApproval::query()->create(['account_id' => $account->id, 'approved_by' => $by, 'approved_at' => $now, 'effective_at' => $now->addSeconds(self::DELAY_SECONDS)]);
    }

    public function withdraw(Account $account): void
    {
        LiveApproval::query()->where('account_id', $account->id)->whereNull('withdrawn_at')->update(['withdrawn_at' => $this->clock->now()]);
    }

    /** The approval in force or waiting, if any. */
    public function current(Account $account): ?LiveApproval
    {
        return LiveApproval::query()->where('account_id', $account->id)->whereNull('withdrawn_at')->orderByDesc('id')->first();
    }

    /** Why the account may not trade live now, or null when it may. */
    public function refusal(Account $account): ?string
    {
        $approval = $this->current($account);

        if ($approval === null) {
            return "{$account->name} is not approved for live trading: approve it on the Settings screen or with php artisan account:approve-live {$account->name}; it takes effect 24 hours later (D-82).";
        }

        return $approval->effective_at > $this->clock->now()
            ? "{$account->name} waits: the approval of {$approval->approved_at->format('Y-m-d H:i')} UTC takes effect at {$approval->effective_at->format('Y-m-d H:i')} UTC (D-19)."
            : null;
    }
}
