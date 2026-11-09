<div class="flex flex-wrap items-center gap-2" wire:poll.10s>
    @forelse ($modes as $account)
        <span @class([
            'inline-flex h-6 items-center rounded-control px-2 font-mono text-2xs uppercase tracking-wider',
            'bg-live text-bg' => $account['mode'] === $live,
            'border border-warn text-warn' => $account['mode']->value === 'testnet',
            'border border-dashed border-muted text-muted' => $account['mode']->value === 'paper',
        ])>{{ strtoupper($account['mode']->value) }}@if ($account['name'] !== $account['mode']->value) {{ $account['name'] }}@endif</span>
    @empty
        <span class="inline-flex h-6 items-center rounded-control border border-info px-2 font-mono text-2xs uppercase tracking-wider text-info">BACKTEST</span>
    @endforelse
</div>
