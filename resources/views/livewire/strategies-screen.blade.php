<div class="grid grid-cols-1 gap-3 lg:grid-cols-12" wire:poll.5s>
    <section class="min-w-0 overflow-x-auto rounded-card border border-border bg-surface lg:col-span-3" aria-label="Versions">
        <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Versions</h2>
        <table class="w-full text-xs">
            <tbody>
                @forelse ($versions as $v)
                    <tr class="h-row cursor-pointer border-b border-border hover:bg-surface-2 {{ $selected === $v['label'] ? 'bg-surface-2' : '' }}" wire:click="open('{{ $v['label'] }}')">
                        <td class="px-3 font-mono">{{ $v['label'] }}</td>
                        <td class="px-2 text-muted">{{ $v['stage'] }}</td>
                        <td class="px-2 text-subtle">{{ $v['author'] }}</td>
                    </tr>
                @empty
                    <tr><td class="px-3 py-2 text-muted">No version yet. Write a document and save it.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="flex min-w-0 flex-col gap-3 lg:col-span-6" aria-label="Editor">
        <div wire:ignore x-data="dslEditor('document')" class="overflow-hidden rounded-card border border-border">
            <div x-ref="editor"></div>
        </div>
        <div class="flex items-center gap-2">
            <x-button wire:click="save" type="button" :disabled="$problems !== [] || trim($document) === ''">Save as a version</x-button>
            @if ($message !== '')<span class="text-sm text-muted" role="status">{{ $message }}</span>@endif
        </div>
        @if ($problems !== [])
            <ul class="rounded-card border border-down bg-surface p-card font-mono text-xs text-down" role="alert">
                @foreach ($problems as $e)<li>line {{ $e['line'] }}, column {{ $e['column'] }}: {{ $e['problem'] }}</li>@endforeach
            </ul>
        @endif
        @foreach ($warnings as $w)<p class="text-xs text-warn">warning: {{ $w }}</p>@endforeach
        @if ($compiled)
            <article class="rounded-card border border-border bg-surface p-card" aria-label="Read-back">
                <h3 class="mb-2 text-2xs uppercase tracking-wider text-muted">Read-back · {{ $compiled['strategy'] }} · warmup {{ $compiled['warmup_candles'] }} candles</h3>
                <p class="text-sm leading-relaxed">{{ $compiled['read_back'] }}</p>
            </article>
        @endif
    </section>

    <section class="flex min-w-0 flex-col gap-3 lg:col-span-3" aria-label="Runs">
        <div class="rounded-card border border-border bg-surface p-card">
            <h2 class="mb-2 text-2xs uppercase tracking-wider text-muted">Run {{ $selected ?? 'a saved version' }}</h2>
            <div class="mb-2 flex gap-2">
                <input type="text" wire:model="from" placeholder="from YYYY-MM-DD" class="h-row w-full rounded-control border border-border bg-surface-2 px-2 font-mono text-xs">
                <input type="text" wire:model="to" placeholder="to YYYY-MM-DD" class="h-row w-full rounded-control border border-border bg-surface-2 px-2 font-mono text-xs">
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button variant="secondary" type="button" wire:click="run('backtest')" :disabled="$selected === null">Backtest</x-button>
                <x-button variant="secondary" type="button" wire:click="run('grid')" :disabled="$selected === null">Grid</x-button>
                <x-button variant="secondary" type="button" wire:click="run('gates')" :disabled="$selected === null">Gates</x-button>
            </div>
        </div>
        @if ($report)
            <div class="rounded-card border border-border bg-surface p-card">
                <h2 class="mb-2 text-2xs uppercase tracking-wider text-muted">Gate report {{ $report->id }}: {{ $report->passed ? 'passes' : 'fails' }}</h2>
                <table class="w-full text-xs">
                    @foreach ($report->report['gates'] ?? [] as $gate)
                        <tr class="border-t border-border"><td class="py-1">{{ $gate['gate'] }}</td><td class="py-1 text-right font-mono">{{ $gate['value'] }}</td>
                            <td class="py-1 pl-2 {{ $gate['passed'] ? 'text-up' : 'text-down' }}">{{ $gate['passed'] ? 'pass' : 'FAIL' }}</td></tr>
                    @endforeach
                </table>
                @if ($report->passed && $stage === 'draft')
                    <x-button type="button" wire:click="promote" class="mt-2 w-full">Promote to Backtested</x-button>
                @endif
            </div>
        @endif
        <div class="rounded-card border border-border bg-surface" aria-label="Ladder">
            <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Ladder to live</h2>
            @forelse ($ladder as $row)
                <div class="border-b border-border px-3 py-2 text-xs" wire:key="ladder-{{ $row['id'] }}">
                    <div class="flex flex-wrap items-baseline justify-between gap-x-2">
                        <span class="font-mono">{{ $row['label'] }}</span>
                        <span class="text-muted">{{ $row['account'] }} · {{ $row['stage'] }} · size {{ $row['size'] }}</span>
                    </div>
                    @if ($row['from'] !== null)
                        <p class="text-warn">Takes effect {{ $row['from'] }} UTC</p>
                    @else
                        <p class="text-muted">{{ $row['days'] }} days, {{ $row['trades'] }} trades,
                            @if ($row['breaches'] === [])<span class="text-up">in band</span>@else<span class="text-down">{{ implode('; ', $row['breaches']) }}</span>@endif
                        </p>
                        @if ($row['next'] !== null)
                            <p class="text-subtle">Next: {{ $row['next'] }}{{ $row['missing'] !== null ? ' ('.$row['missing'].')' : '' }}</p>
                            @if ($row['ready'])
                                <x-button type="button" class="mt-1 w-full" wire:key="promote-{{ $row['id'] }}" wire:click="promoteDeployment({{ $row['id'] }})" wire:confirm="Approve {{ $row['label'] }} for {{ $row['next'] }}? It takes effect 24 hours from now.">Promote to {{ $row['next'] }}</x-button>
                            @endif
                        @endif
                    @endif
                </div>
            @empty
                <p class="px-3 py-2 text-xs text-muted">No deployment yet.</p>
            @endforelse
        </div>
        <div class="rounded-card border border-border bg-surface">
            <h2 class="border-b border-border px-3 py-2 text-2xs uppercase tracking-wider text-muted">Background runs</h2>
            <table class="w-full text-xs">
                @foreach ($jobs as $job)
                    <tr class="h-row border-b border-border"><td class="px-3 font-mono">{{ $job->id }}</td><td>{{ $job->kind }}</td>
                        <td class="font-mono text-muted">{{ $job->arguments['strategy'] ?? '' }}</td>
                        <td @class(['px-2', 'text-up' => $job->status === 'done', 'text-down' => $job->status === 'failed', 'text-muted' => ! in_array($job->status, ['done', 'failed'], true)])>{{ $job->status }}</td></tr>
                @endforeach
            </table>
        </div>
    </section>
</div>
