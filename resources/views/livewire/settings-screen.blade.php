@php
    $card = 'rounded-card border border-border bg-surface p-card';
    $heading = 'mb-2 text-2xs uppercase tracking-wider text-muted';
    $input = 'h-row w-full min-w-0 rounded-control border border-border bg-surface-2 px-2 font-mono text-sm text-text focus:border-focus focus:outline-none';
    $label = 'mb-1 block text-2xs uppercase tracking-wider text-muted';
@endphp
<div class="flex flex-col gap-3">
    <div class="grid grid-cols-1 gap-3 xl:grid-cols-2">
        {{-- Telegram (D-75): the token and chat id are never shown back. --}}
        <section class="{{ $card }}" aria-labelledby="telegram-heading">
            <h2 id="telegram-heading" class="{{ $heading }}">Telegram</h2>
            <dl class="mb-3 grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 text-sm">
                <dt class="text-muted">Token</dt>
                <dd data-test="token-source">{{ $telegram->tokenSource()->value }}</dd>
                <dt class="text-muted">Chat id</dt>
                <dd data-test="chat-source">{{ $telegram->chatSource()->value }}</dd>
                @if ($telegram->setAt() !== null)
                    <dt class="text-muted">Changed</dt>
                    <dd class="font-mono">{{ $telegram->setAt()->format('Y-m-d H:i') }} UTC</dd>
                @endif
            </dl>
            @if ($fresh)
                <form wire:submit="saveTelegram" class="grid grid-cols-1 gap-2 sm:grid-cols-2" autocomplete="off">
                    <label class="block min-w-0">
                        <span class="{{ $label }}">Bot token</span>
                        <input type="password" wire:model="telegramToken" autocomplete="new-password" placeholder="leave empty to keep" class="{{ $input }}">
                    </label>
                    <label class="block min-w-0">
                        <span class="{{ $label }}">Chat id</span>
                        <input type="password" wire:model="telegramChat" autocomplete="new-password" placeholder="leave empty to keep" class="{{ $input }}">
                    </label>
                    <div class="flex flex-wrap gap-2 sm:col-span-2">
                        <x-button>Save</x-button>
                        @if ($telegram->tokenSource() === \App\Notifications\SettingSource::App || $telegram->chatSource() === \App\Notifications\SettingSource::App)
                            <x-button variant="secondary" type="button" wire:click="forgetTelegram">Use .env instead</x-button>
                        @endif
                        <x-button variant="secondary" type="button" wire:click="testTelegram">Send a test message</x-button>
                    </div>
                </form>
            @else
                <div class="flex flex-wrap gap-2">
                    <x-button variant="secondary" type="button" wire:click="confirm">Confirm to change</x-button>
                    <x-button variant="secondary" type="button" wire:click="testTelegram">Send a test message</x-button>
                </div>
            @endif
            @if ($telegramMessage !== '')<p class="mt-2 text-sm text-muted" role="status">{{ $telegramMessage }}</p>@endif
        </section>

        {{-- Which events send a message (D-75); the ones that need the operator always do. --}}
        <section class="{{ $card }}" aria-labelledby="notify-heading">
            <h2 id="notify-heading" class="{{ $heading }}">Notifications</h2>
            <p class="mb-2 text-sm text-muted">Always sent: Halts, reconciliation mismatches, replay divergence, venue errors, clock skew, liquidation and stale data.</p>
            @foreach (['command' => 'Commands (halt, resume, flatten, pause)', 'volatility_shock' => 'Volatility shock (trips many times a day)', 'price_sanity' => 'Price sanity', 'daily_report' => 'Daily report ('.config('notifications.daily_report_at').', '.config('notifications.daily_report_timezone').')'] as $kind => $text)
                <label class="flex min-h-row items-center gap-2 text-sm">
                    <input type="checkbox" class="size-4 accent-[var(--accent)]" @checked($optional[$kind]) wire:click="notify('{{ $kind }}', {{ $optional[$kind] ? 'false' : 'true' }})" data-test="notify-{{ $kind }}">
                    <span>{{ $text }}</span>
                </label>
            @endforeach
            @if ($notifyMessage !== '')<p class="mt-2 text-sm text-down" role="status">{{ $notifyMessage }}</p>@endif
        </section>
    </div>

    {{-- Accounts: paper, testnet and live; live trades only after the approval and its 24 hours (D-82). --}}
    <section class="{{ $card }}" aria-labelledby="accounts-heading">
        <h2 id="accounts-heading" class="{{ $heading }}">Accounts</h2>
        <div class="mb-3 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-2xs uppercase tracking-wider text-muted">
                    <tr><th class="py-1 pr-3 font-normal">Name</th><th class="pr-3 font-normal">Mode</th><th class="hidden pr-3 font-normal sm:table-cell">Starts</th><th class="hidden pr-3 font-normal md:table-cell">Follows</th><th class="pr-3 font-normal">Key</th><th class="font-normal">Watched</th></tr>
                </thead>
                <tbody class="font-mono">
                    @forelse ($accounts as $a)
                        <tr class="h-row border-t border-border">
                            <td class="pr-3">{{ $a->name }}</td>
                            <td class="pr-3">{{ $a->mode->value }}</td>
                            <td class="hidden whitespace-nowrap pr-3 sm:table-cell">{{ $a->started_at->format('Y-m-d H:i') }}</td>
                            <td class="hidden pr-3 md:table-cell">{{ implode(', ', $a->symbols) }}</td>
                            <td class="pr-3 text-muted">{{ $a->mode === \App\Execution\Data\EngineMode::Paper ? 'not needed' : ($hints[$a->id] ?? 'none') }}</td>
                            <td><input type="checkbox" class="size-4 accent-[var(--accent)]" aria-label="Health check watches {{ $a->name }}" @checked($a->monitored) wire:click="monitor({{ $a->id }}, {{ $a->monitored ? 'false' : 'true' }})"></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-2 text-muted">No account yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <form wire:submit="openAccount" class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-6">
            <label class="block min-w-0"><span class="{{ $label }}">Name</span><input wire:model="accountName" placeholder="paper-2" class="{{ $input }}"></label>
            <label class="block min-w-0"><span class="{{ $label }}">Mode</span>
                <select wire:model="accountMode" class="{{ $input }}"><option value="paper">paper</option><option value="testnet">testnet</option><option value="live">live (real money)</option></select>
            </label>
            <label class="block min-w-0"><span class="{{ $label }}">Spot USDT</span><input wire:model="accountSpot" inputmode="decimal" class="{{ $input }}"></label>
            <label class="block min-w-0"><span class="{{ $label }}">USD-M USDT</span><input wire:model="accountFutures" inputmode="decimal" class="{{ $input }}"></label>
            <label class="block min-w-0"><span class="{{ $label }}">Instruments</span><input wire:model="accountSymbols" placeholder="BTC/USDT, ETH/USDT" class="{{ $input }}"></label>
            <label class="block min-w-0"><span class="{{ $label }}">Starts (UTC)</span><input wire:model="accountStart" placeholder="now" class="{{ $input }}"></label>
            <div class="sm:col-span-2 lg:col-span-6"><x-button>Open account</x-button></div>
        </form>
        @foreach ($approvals as $id => $approval)
            @php($live = $accounts->firstWhere('id', $id))
            <div class="mt-3 flex flex-wrap items-center gap-2 rounded-control border border-live p-2 text-sm" wire:key="approval-{{ $id }}" aria-label="Live approval of {{ $live?->name }}">
                <span class="font-mono">{{ $live?->name }}</span>
                @if ($approval === null)
                    <span class="text-muted">not approved for live trading</span>
                    <x-button variant="danger" type="button" class="ml-auto" wire:key="approve-{{ $id }}" wire:click="approveLive({{ $id }})" wire:confirm="Approve {{ $live?->name }} for live trading with real money? It takes effect in 24 hours.">Approve live trading</x-button>
                @else
                    <span>approved {{ $approval->approved_at->format('Y-m-d H:i') }} UTC, {{ $approval->effective_at->isFuture() ? 'takes effect '.$approval->effective_at->format('Y-m-d H:i').' UTC' : 'in effect' }}</span>
                    <x-button variant="secondary" type="button" class="ml-auto" wire:key="withdraw-{{ $id }}" wire:click="withdrawLive({{ $id }})">Withdraw</x-button>
                @endif
            </div>
        @endforeach
        @if ($accountMessage !== '')<p class="mt-2 text-sm text-muted" role="status">{{ $accountMessage }}</p>@endif
    </section>

    <div class="grid grid-cols-1 gap-3 xl:grid-cols-2">
        {{-- Exchange keys: stored encrypted, the secret never shown back (D-20, D-75). --}}
        <section class="{{ $card }}" aria-labelledby="keys-heading">
            <h2 id="keys-heading" class="{{ $heading }}">Exchange keys</h2>
            @if ($testnet->isEmpty())
                <p class="text-sm text-muted">Open a testnet or live account first.</p>
            @else
                <ul class="mb-3 text-sm">
                    @foreach ($testnet as $a)
                        <li class="flex min-h-row flex-wrap items-center gap-x-3 border-t border-border" wire:key="key-{{ $a->id }}">
                            <span class="font-mono">{{ $a->name }}</span>
                            <span class="text-muted">{{ $hints[$a->id] ?? 'no key' }}</span>
                            @if ($fresh && ($hints[$a->id] ?? null) !== null)
                                <button type="button" class="ml-auto text-sm text-down hover:underline" wire:click="forgetKey({{ $a->id }})" wire:confirm="Delete the key of {{ $a->name }}? Its engine will not start without one.">Delete</button>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @if ($fresh)
                    <form wire:submit="saveKey" class="grid grid-cols-1 gap-2 sm:grid-cols-3" autocomplete="off">
                        <label class="block min-w-0"><span class="{{ $label }}">Account</span>
                            <select wire:model="keyAccount" class="{{ $input }}">
                                <option value="">pick one</option>
                                @foreach ($testnet as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach
                            </select>
                        </label>
                        <label class="block min-w-0"><span class="{{ $label }}">API key</span><input type="password" wire:model="apiKey" autocomplete="new-password" class="{{ $input }}"></label>
                        <label class="block min-w-0"><span class="{{ $label }}">API secret</span><input type="password" wire:model="apiSecret" autocomplete="new-password" class="{{ $input }}"></label>
                        <div class="sm:col-span-3"><x-button>Store key</x-button></div>
                    </form>
                @else
                    <x-button variant="secondary" type="button" wire:click="confirm">Confirm to change</x-button>
                @endif
            @endif
            @if ($keyMessage !== '')<p class="mt-2 text-sm text-muted" role="status">{{ $keyMessage }}</p>@endif
        </section>

        {{-- MCP tokens (D-63): the secret is shown once, at creation. --}}
        <section class="{{ $card }}" aria-labelledby="tokens-heading">
            <h2 id="tokens-heading" class="{{ $heading }}">MCP tokens</h2>
            <ul class="mb-3 text-sm">
                @forelse ($tokens as $t)
                    <li class="flex min-h-row flex-wrap items-center gap-x-3 border-t border-border" wire:key="token-{{ $t->id }}">
                        <span class="font-mono">{{ $t->name }}</span>
                        <span class="text-muted">{{ $t->revoked_at !== null ? 'revoked '.$t->revoked_at->format('Y-m-d H:i') : 'last used '.($t->last_used_at?->format('Y-m-d H:i') ?? 'never') }}</span>
                        @if ($t->revoked_at === null)
                            <button type="button" class="ml-auto text-sm text-down hover:underline" wire:click="revokeToken('{{ $t->name }}')">Revoke</button>
                        @endif
                    </li>
                @empty
                    <li class="text-muted">No token yet.</li>
                @endforelse
            </ul>
            @if ($newToken !== '')
                <div class="mb-3 rounded-control border border-warn p-2">
                    <div class="{{ $label }}">New token, shown once</div>
                    <code class="block select-all break-all font-mono text-sm" data-test="new-token">{{ $newToken }}</code>
                </div>
            @endif
            @if ($fresh)
                <form wire:submit="createToken" class="flex flex-wrap items-end gap-2">
                    <label class="block min-w-0 flex-1"><span class="{{ $label }}">Name</span><input wire:model="tokenName" placeholder="laptop" class="{{ $input }}"></label>
                    <x-button>Create token</x-button>
                </form>
            @else
                <x-button variant="secondary" type="button" wire:click="confirm">Confirm to change</x-button>
            @endif
            @if ($tokenMessage !== '')<p class="mt-2 text-sm text-muted" role="status">{{ $tokenMessage }}</p>@endif
        </section>
    </div>

    {{-- Market data: the universe and history imports (D-80). --}}
    <section class="{{ $card }}" aria-labelledby="market-heading">
        <h2 id="market-heading" class="{{ $heading }}">Market data</h2>
        <div class="mb-3 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-2xs uppercase tracking-wider text-muted">
                    <tr><th class="py-1 pr-3 font-normal">Instrument</th><th class="pr-3 font-normal">Streamed</th><th class="pr-3 font-normal">Scalping data</th><th class="hidden pr-3 font-normal md:table-cell">Large trade from</th><th class="font-normal"></th></tr>
                </thead>
                <tbody class="font-mono">
                    @foreach ($universe as $u)
                        <tr class="h-row border-t border-border" wire:key="universe-{{ $u->id }}">
                            <td class="whitespace-nowrap pr-3">{{ $u->symbol }}</td>
                            <td class="pr-3"><input type="checkbox" class="size-4 accent-[var(--accent)]" aria-label="Stream {{ $u->symbol }}" @checked($u->streamed) wire:click="setUniverse({{ $u->id }}, 'streamed', {{ $u->streamed ? 'false' : 'true' }})"></td>
                            <td class="pr-3">
                                @if ($u->market === \App\Market\Data\MarketType::Perp)
                                    <input type="checkbox" class="size-4 accent-[var(--accent)]" aria-label="Scalping data of {{ $u->symbol }}" @checked($u->scalping) wire:click="setUniverse({{ $u->id }}, 'scalping', {{ $u->scalping ? 'false' : 'true' }})">
                                @else
                                    <span class="text-muted">spot</span>
                                @endif
                            </td>
                            <td class="hidden pr-3 md:table-cell">
                                @if ($u->market === \App\Market\Data\MarketType::Perp)
                                    <form wire:submit="setNotional({{ $u->id }})" class="flex items-center gap-1">
                                        <input wire:model="notional.{{ $u->id }}" placeholder="{{ $u->large_notional === null ? 'default' : rtrim(rtrim($u->large_notional, '0'), '.') }}" aria-label="Large-trade notional of {{ $u->symbol }}" class="{{ $input }} w-28">
                                    </form>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-right">
                                <button type="button" class="text-sm text-muted hover:text-text" wire:click="$set('importSymbol', {{ $u->id }})">Import</button>
                                <button type="button" class="ml-2 text-sm text-down hover:underline" wire:click="removeInstrument({{ $u->id }})" wire:confirm="Stop following {{ $u->symbol }}? Its stored history stays.">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
            <form wire:submit="addInstrument" class="flex flex-wrap items-end gap-2">
                <label class="block min-w-0 flex-1"><span class="{{ $label }}">Add an instrument</span><input wire:model="universeSymbol" placeholder="SOL/USDT:USDT or SOL/USDT" class="{{ $input }}"></label>
                <x-button>Add</x-button>
            </form>
            <form wire:submit="import" class="flex flex-wrap items-end gap-2">
                <label class="block min-w-0"><span class="{{ $label }}">Import history of</span>
                    <select wire:model="importSymbol" class="{{ $input }}" aria-label="Instrument to import">
                        <option value="">pick one</option>
                        @foreach ($universe as $u)<option value="{{ $u->id }}">{{ $u->symbol }}</option>@endforeach
                    </select>
                </label>
                <label class="block min-w-0"><span class="{{ $label }}">From</span><input wire:model="importFrom" placeholder="2025-11-01" class="{{ $input }} w-32"></label>
                <label class="flex min-h-row items-center gap-1 text-sm"><input type="checkbox" class="size-4 accent-[var(--accent)]" wire:model="importCandles"> candles</label>
                <label class="flex min-h-row items-center gap-1 text-sm"><input type="checkbox" class="size-4 accent-[var(--accent)]" wire:model="importScalping"> scalping data</label>
                <x-button>Import</x-button>
            </form>
        </div>
        @if ($marketMessage !== '')<p class="mt-2 text-sm text-muted" role="status">{{ $marketMessage }}</p>@endif
        @if ($marketJobs->isNotEmpty())
            <ul class="mt-3 text-sm" aria-label="Imports">
                @foreach ($marketJobs as $j)
                    <li class="border-t border-border py-1"><span class="font-mono">#{{ $j->id }} {{ $j->symbol }} from {{ $j->from->format('Y-m-d') }}</span>
                        <span @class(['ml-2', 'text-up' => $j->status === 'done', 'text-down' => $j->status === 'failed', 'text-muted' => ! in_array($j->status, ['done', 'failed'], true)])>{{ $j->status }}</span>
                        @if ($j->output)<span class="ml-2 text-muted">{{ \Illuminate\Support\Str::limit(trim(\Illuminate\Support\Arr::last(explode("\n", trim($j->output))) ?? ''), 120) }}</span>@endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Risk, read-only: raising a limit is a code change with a test (D-19). --}}
    <section class="{{ $card }}" aria-labelledby="risk-heading">
        <h2 id="risk-heading" class="{{ $heading }}">Risk limits</h2>
        <p class="mb-2 text-sm text-muted">Changing a limit is a code change with a test (D-19). Fractions are of equity.</p>
        <dl class="grid grid-cols-1 gap-x-6 gap-y-1 font-mono text-sm sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($risk as $name => $value)
                <div class="flex justify-between gap-3 border-t border-border py-1"><dt class="min-w-0 truncate text-muted">{{ $name }}</dt><dd>{{ $value }}</dd></div>
            @endforeach
        </dl>
        <dl class="mt-3 text-sm">
            @foreach ($breakers as $name => $text)
                <div class="border-t border-border py-1"><dt class="inline font-medium">{{ $name }}:</dt> <dd class="inline text-muted">{{ $text }}.</dd></div>
            @endforeach
        </dl>
    </section>
</div>
