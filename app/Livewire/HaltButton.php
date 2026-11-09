<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Execution\Data\CommandKind;
use App\Execution\Models\Account;
use App\Execution\Models\EngineCommandRow;
use App\Support\Clock\Clock;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * The Halt button on every screen (docs/risk-policy.md, section 5): no new entries on any account, working entries
 * cancelled, protection kept. Lowering risk needs no re-authentication (D-19).
 */
final class HaltButton extends Component
{
    public string $message = '';

    public function halt(Clock $clock): void
    {
        $names = [];

        foreach (Account::query()->orderBy('name')->get() as $account) {
            EngineCommandRow::query()->create(['account_id' => $account->id, 'kind' => CommandKind::Halt, 'strategy' => null, 'issued_by' => 'operator', 'created_at' => $clock->now()]);
            $names[] = $account->name;
        }

        $this->message = $names === [] ? 'No account to halt.' : 'Halt queued for '.implode(', ', $names).'.';
    }

    public function render(): View
    {
        return view('livewire.halt-button');
    }
}
