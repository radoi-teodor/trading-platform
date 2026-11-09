<div class="flex flex-col gap-3" wire:poll.2s>
    @if ($current === null)
        <div class="rounded-card border border-border bg-surface p-card text-sm text-muted">No account yet. Open one on the <a href="{{ route('settings') }}" class="text-accent hover:underline">Settings</a> screen.</div>
    @else
        @php($p = $snapshot?->payload ?? [])
        <div class="flex flex-wrap items-center gap-2 rounded-card border border-border bg-surface p-card">
            <select wire:model.live="account" class="h-row rounded-control border border-border bg-surface-2 px-2 font-mono text-sm" aria-label="Account">
                @foreach ($accounts as $a)<option value="{{ $a->id }}">{{ $a->name }} ({{ $a->mode->value }})</option>@endforeach
            </select>
            <span class="text-xs text-muted">Last minute {{ $snapshot?->minute?->format('Y-m-d H:i') ?? 'none yet' }} UTC</span>
            @if (($p['halt'] ?? null) !== null)
                <span class="inline-flex h-6 items-center rounded-control border border-down px-2 text-xs text-down" role="status">Halted: {{ $p['halt'] }}</span>
            @endif
            @if ($pending > 0)<span class="text-xs text-warn">{{ $pending }} command(s) waiting for the next minute</span>@endif
            <div class="ml-auto flex flex-wrap gap-2">
                <x-button variant="secondary" type="button" wire:click="command('halt')">Halt account</x-button>
                <x-button variant="secondary" type="button" wire:click="command('resume')">Resume</x-button>
                <x-button variant="danger" type="button" wire:click="command('flatten')" wire:confirm="Flatten cancels every order of this account and closes every position at market, then halts it.">Flatten</x-button>
            </div>
        </div>
        @if ($message !== '')<p class="text-sm text-muted" role="status">{{ $message }}</p>@endif

        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <div class="rounded-card border border-border bg-surface p-card">
                <div class="text-2xs uppercase tracking-wider text-muted">Equity</div>
                <div class="font-mono text-lg">{{ $p['equity'] ?? 'n/a' }} {{ $current->quote }}</div>
            </div>
            <div class="rounded-card border border-border bg-surface p-card md:col-span-3">
                <div class="mb-1 text-2xs uppercase tracking-wider text-muted">Balances</div>
                <div class="flex flex-wrap gap-4 font-mono text-sm">
                    @foreach ($p['balances'] ?? [] as $asset => $amount)<span>{{ $amount }} {{ $asset }}</span>@endforeach
                    <span class="text-muted">USD-M wallet {{ $p['futures_wallet'] ?? '0' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
            <section class="rounded-card border border-border bg-surface" aria-label="Positions">
                <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Positions</h2>
                <table class="w-full font-mono text-xs">
                    @forelse ($p['positions'] ?? [] as $pos)
                        <tr class="h-row border-b border-border"><td class="px-3">{{ $pos['symbol'] }}</td><td>{{ $pos['side'] === 'buy' ? 'long' : 'short' }}</td>
                            <td class="text-right">{{ $pos['quantity'] }}</td><td class="text-right">@ {{ $pos['entry'] }}</td><td class="px-3 text-right text-muted">stop {{ $pos['stop'] ?? 'none' }}</td></tr>
                    @empty
                        <tr><td class="px-3 py-2 text-muted">No position.</td></tr>
                    @endforelse
                </table>
            </section>
            <section class="rounded-card border border-border bg-surface" aria-label="Strategies">
                <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Strategies</h2>
                <table class="w-full text-xs">
                    @forelse ($deployments as $d)
                        @php($label = $d->version?->label() ?? '')
                        <tr class="h-row border-b border-border"><td class="px-3 font-mono">{{ $label }}</td>
                            <td class="text-muted">{{ in_array($label, $p['paused'] ?? [], true) ? 'paused' : 'running' }}</td>
                            <td class="px-3 text-right">
                                <button type="button" class="text-muted hover:text-text" wire:click="command('pause', '{{ $label }}')">Pause</button>
                                <button type="button" class="ml-2 text-muted hover:text-text" wire:click="command('resume_strategy', '{{ $label }}')">Resume</button>
                            </td></tr>
                    @empty
                        <tr><td class="px-3 py-2 text-muted">No strategy runs here. A version reaches paper when it passes its gates (D-71).</td></tr>
                    @endforelse
                </table>
                @if (($p['blocked'] ?? []) !== [])<p class="px-3 py-2 text-xs text-warn">Blocked by a breaker: {{ implode(', ', $p['blocked']) }}</p>@endif
            </section>
        </div>

        <section class="min-w-0 overflow-x-auto rounded-card border border-border bg-surface" aria-label="Journal">
            <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Journal</h2>
            <table class="w-full font-mono text-xs">
                @foreach ($journal as $entry)
                    <tr class="border-b border-border align-top">
                        <td class="whitespace-nowrap px-3 py-1 text-muted">{{ $entry->event_at->format('Y-m-d H:i') }}</td>
                        <td class="px-2 py-1">{{ $entry->kind === 'decision' ? $entry->payload['strategy'] ?? '' : $entry->payload['kind'] ?? '' }}</td>
                        <td class="break-words px-2 py-1 text-muted [overflow-wrap:anywhere]">
                            @if ($entry->kind === 'decision')
                                @foreach ($entry->payload['intents'] ?? [] as $intent){{ $intent['type'] ?? '' }} {{ ($intent['approved'] ?? false) ? 'approved' : 'rejected: '.($intent['rejection'] ?? '') }} @endforeach
                            @else
                                {{ $entry->payload['detail'] ?? '' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </section>
    @endif
</div>
