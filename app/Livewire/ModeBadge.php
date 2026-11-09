<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/** The modes of the accounts, on every screen (docs/brand.md, A4). */
final class ModeBadge extends Component
{
    public function render(): View
    {
        $modes = Account::query()->orderBy('name')->get()->map(fn (Account $a): array => ['name' => $a->name, 'mode' => $a->mode])->all();

        return view('livewire.mode-badge', ['modes' => $modes, 'live' => EngineMode::Live]);
    }
}
