<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Execution\Data\CommandKind;
use App\Execution\Models\Account;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\Deployment;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\JournalEntry;
use App\Http\Controllers\ReauthController;
use App\Support\Clock\Clock;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * An account as its engine last published it (D-70): equity, balances, positions, orders, halts, breakers and the
 * recent journal, and the operator's commands (docs/risk-policy.md, section 5). Halting and pausing lower risk and
 * act at once; resuming and flattening ask for re-authentication first (docs/security.md, section 3).
 */
#[Layout('layouts.app')]
#[Title('Account')]
final class AccountScreen extends Component
{
    private const array NEED_REAUTH = ['resume', 'flatten', 'resume_strategy'];

    public ?int $account = null;

    public string $message = '';

    public function mount(): void
    {
        $id = Account::query()->orderBy('name')->value('id');
        $this->account = is_int($id) ? $id : null;
    }

    public function command(string $kind, Clock $clock, ?string $strategy = null): void
    {
        $command = CommandKind::tryFrom($kind);
        $account = $this->account === null ? null : Account::query()->find($this->account);

        if ($command === null || $account === null) {
            return;
        }

        if (in_array($kind, self::NEED_REAUTH, true) && ! ReauthController::fresh()) {
            session()->put('url.intended', route('account'));
            $this->redirectRoute('reauth');

            return;
        }

        EngineCommandRow::query()->create(['account_id' => $account->id, 'kind' => $command, 'strategy' => $strategy, 'issued_by' => 'operator', 'created_at' => $clock->now()]);
        $this->message = ucfirst(str_replace('_', ' ', $kind)).($strategy === null ? '' : " {$strategy}")." queued for {$account->name}; its engine applies it at the next minute.";
    }

    public function render(): View
    {
        $account = $this->account === null ? null : Account::query()->find($this->account);

        return view('livewire.account-screen', [
            'accounts' => Account::query()->orderBy('name')->get(),
            'current' => $account,
            'snapshot' => $account === null ? null : AccountSnapshot::query()->find($account->id),
            'journal' => $account === null ? collect() : JournalEntry::query()->where('account_id', $account->id)->orderByDesc('id')->limit(40)->get(),
            'deployments' => $account === null ? collect() : Deployment::query()->where('account_id', $account->id)->where('active', true)->with('version')->get(),
            'pending' => $account === null ? 0 : EngineCommandRow::query()->where('account_id', $account->id)->whereNull('applied_at')->count(),
        ]);
    }
}
