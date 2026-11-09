<div class="flex flex-col gap-3">
    <div class="flex flex-wrap items-end gap-2 rounded-card border border-border bg-surface p-card">
        <label class="flex flex-col gap-1">
            <span class="text-2xs uppercase tracking-wider text-muted">Instrument</span>
            <select wire:model.live="symbol" class="h-row rounded-control border border-border bg-surface-2 px-2 font-mono text-sm">
                @foreach ($instruments as $instrument)<option value="{{ $instrument }}">{{ $instrument }}</option>@endforeach
            </select>
        </label>
        <label class="flex flex-col gap-1">
            <span class="text-2xs uppercase tracking-wider text-muted">Timeframe</span>
            <select wire:model.live="timeframe" class="h-row rounded-control border border-border bg-surface-2 px-2 font-mono text-sm">
                @foreach ($timeframes as $tf)<option value="{{ $tf }}">{{ $tf }}</option>@endforeach
            </select>
        </label>
        <label class="flex flex-col gap-1">
            <span class="text-2xs uppercase tracking-wider text-muted">Candles</span>
            <input type="number" min="20" max="1000" wire:model.live.debounce.600ms="candles" class="h-row w-24 rounded-control border border-border bg-surface-2 px-2 font-mono text-sm">
        </label>
        <form wire:submit="add" class="flex flex-wrap items-end gap-2">
            <label class="flex flex-col gap-1">
                <span class="text-2xs uppercase tracking-wider text-muted">Function</span>
                <select wire:model="function" class="h-row rounded-control border border-border bg-surface-2 px-2 font-mono text-sm">
                    @foreach ($functions as $name)<option value="{{ $name }}">{{ $name }}</option>@endforeach
                </select>
            </label>
            <label class="flex min-w-[9rem] flex-1 flex-col gap-1 sm:flex-none">
                <span class="text-2xs uppercase tracking-wider text-muted">Or an expression</span>
                <input type="text" wire:model="expression" placeholder="ema(21) on 4h" class="h-row w-full rounded-control sm:w-48 border border-border bg-surface-2 px-2 font-mono text-sm">
            </label>
            <x-button variant="secondary">Add layer</x-button>
        </form>
        <label class="ml-auto flex items-center gap-2 text-sm text-muted">
            <input type="checkbox" wire:model.live="table"> Table view
        </label>
    </div>

    <div class="flex flex-wrap gap-2" aria-label="Layers">
        @foreach ($layers as $i => $layer)
            <span class="inline-flex h-row items-center gap-2 rounded-control border border-border bg-surface px-2 font-mono text-xs">
                {{ $layer }}<span class="text-subtle">{{ $payload['types'][$layer] ?? '' }}</span>
                <button type="button" wire:click="remove({{ $i }})" class="text-muted hover:text-down" aria-label="Remove {{ $layer }}">x</button>
            </span>
        @endforeach
    </div>

    @if ($error !== '')
        <div class="rounded-card border border-down bg-surface p-card text-sm text-down" role="alert">{{ $error }}</div>
    @endif

    <div wire:ignore x-data="priceChart(@js($payload))" class="relative rounded-card border border-border bg-surface p-card">
        <div x-ref="main" class="h-[420px] w-full"></div>
        <div x-ref="profile" class="pointer-events-none absolute inset-y-3 right-16 z-10 w-16 sm:w-32" aria-hidden="true"></div>
        <div x-ref="panels" class="flex flex-col gap-2"></div>
    </div>

    @if ($table && $payload['candles'] !== [])
        <div class="overflow-x-auto rounded-card border border-border bg-surface">
            <table class="w-full font-mono text-xs">
                <thead class="sticky top-0 bg-surface-2 text-muted">
                    <tr><th class="px-2 py-1 text-left">Time (UTC)</th><th class="px-2 py-1 text-right">Close</th>
                        @foreach ($layers as $layer)<th class="px-2 py-1 text-right">{{ $layer }}</th>@endforeach</tr>
                </thead>
                <tbody>
                    @foreach (array_slice(array_reverse(array_keys($payload['candles'])), 0, 50) as $row)
                        <tr class="border-t border-border">
                            <td class="px-2 py-1">{{ $payload['candles'][$row]['time'] }}</td>
                            <td class="px-2 py-1 text-right">{{ $payload['candles'][$row]['close'] }}</td>
                            @foreach ($layers as $layer)<td class="px-2 py-1 text-right">{{ $payload['series'][$layer][$row] ?? 'n/a' }}</td>@endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
