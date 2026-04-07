@extends('layouts.app')

@section('title', 'All Planned Trades')

@section('content')
@php
    $pending = $trades->where('status', 'pending');
    $executed = $trades->where('status', 'executed');
    $executedToday = $executed->filter(fn($t) => $t->created_at->isToday());
@endphp

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <h2 class="text-3xl font-bold text-white">All Planned Trades</h2>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-400">Total Pending</p>
            <p class="text-2xl font-bold text-amber-400">{{ $pending->count() }}</p>
        </div>
    </x-card>
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-400">Executed Today</p>
            <p class="text-2xl font-bold text-green-400">{{ $executedToday->count() }}</p>
        </div>
    </x-card>
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-400">Total Trades</p>
            <p class="text-2xl font-bold text-blue-400">{{ $trades->count() }}</p>
        </div>
    </x-card>
</div>

{{-- Filter tabs --}}
<div class="mb-4 flex space-x-2">
    <button onclick="filterTrades('pending')" id="tab-pending"
            class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-amber-500/20 text-amber-400 border-amber-500/50">
        Pending ({{ $pending->count() }})
    </button>
    <button onclick="filterTrades('executed')" id="tab-executed"
            class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-[#1a1d29] text-gray-400 border-[#2a2d3a] hover:text-white">
        Executed ({{ $executed->count() }})
    </button>
    <button onclick="filterTrades('all')" id="tab-all"
            class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-[#1a1d29] text-gray-400 border-[#2a2d3a] hover:text-white">
        All ({{ $trades->count() }})
    </button>
</div>

{{-- Trades table --}}
<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                <tr>
                    <th class="px-4 py-3">Strategy Date</th>
                    <th class="px-4 py-3">Symbol</th>
                    <th class="px-4 py-3">Action</th>
                    <th class="px-4 py-3 text-right">Qty</th>
                    <th class="px-4 py-3 text-right">Entry Price</th>
                    <th class="px-4 py-3 text-right">SL</th>
                    <th class="px-4 py-3 text-right">TP</th>
                    <th class="px-4 py-3">Urgency</th>
                    <th class="px-4 py-3 text-right">Confidence</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Reentry</th>
                    <th class="px-4 py-3 text-right">Max Entries</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2a2d3a]" id="trades-body">
                @forelse($trades as $trade)
                    <tr class="hover:bg-[#0f1117]/50 trade-row" data-status="{{ $trade->status }}">
                        <td class="px-4 py-3 text-gray-300">
                            @if($trade->claudeAnalysis)
                                <a href="{{ route('strategies.show', $trade->claudeAnalysis->id) }}" class="text-blue-400 hover:text-blue-300">
                                    {{ $trade->claudeAnalysis->date->format('M d, Y') }}
                                </a>
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('strategies.trade.chart', [$trade->claude_analysis_id, $trade->id]) }}" class="text-white font-medium hover:text-blue-400">
                                {{ $trade->symbol }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <x-badge
                                :color="$trade->action === 'buy' ? 'green' : 'red'"
                                :text="strtoupper($trade->action)"
                            />
                        </td>
                        <td class="px-4 py-3 text-right text-gray-300">{{ $trade->quantity }}</td>
                        <td class="px-4 py-3 text-right text-blue-400 font-medium">
                            @if($trade->entry_price_cents)
                                ${{ number_format($trade->entry_price_cents / 100, 2) }}
                            @else
                                Market
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-red-400">
                            @if($trade->stop_loss_cents)
                                ${{ number_format($trade->stop_loss_cents / 100, 2) }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-green-400">
                            @if($trade->take_profit_cents)
                                ${{ number_format($trade->take_profit_cents / 100, 2) }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <x-badge
                                :color="$trade->urgency === 'immediate' ? 'red' : ($trade->urgency === 'wait_for_dip' ? 'amber' : 'gray')"
                                :text="ucfirst(str_replace('_', ' ', $trade->urgency ?? 'N/A'))"
                            />
                        </td>
                        <td class="px-4 py-3 text-right text-white">
                            {{ $trade->confidence ? number_format($trade->confidence * 100, 0) . '%' : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge
                                :color="$trade->status === 'executed' ? 'green' : ($trade->status === 'pending' ? 'amber' : ($trade->status === 'failed' ? 'red' : 'gray'))"
                                :text="ucfirst($trade->status)"
                            />
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $trade->re_entry_allowed ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-300">
                            {{ $trade->max_entries ?? 1 }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('strategies.trade.chart', [$trade->claude_analysis_id, $trade->id]) }}"
                               class="text-blue-400 hover:text-blue-300 text-xs font-medium">
                                Chart
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                            No planned trades found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

<script>
function filterTrades(tab) {
    var rows = document.querySelectorAll('.trade-row');
    rows.forEach(function(row) {
        var status = row.getAttribute('data-status');
        if (tab === 'all' || status === tab) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Update active tab styling
    var tabStyles = {
        pending:  { active: 'bg-amber-500/20 text-amber-400 border-amber-500/50', inactive: 'bg-[#1a1d29] text-gray-400 border-[#2a2d3a]' },
        executed: { active: 'bg-green-500/20 text-green-400 border-green-500/50', inactive: 'bg-[#1a1d29] text-gray-400 border-[#2a2d3a]' },
        all:      { active: 'bg-blue-500/20 text-blue-400 border-blue-500/50', inactive: 'bg-[#1a1d29] text-gray-400 border-[#2a2d3a]' },
    };

    ['pending', 'executed', 'all'].forEach(function(t) {
        var btn = document.getElementById('tab-' + t);
        var styles = tabStyles[t];
        if (t === tab) {
            btn.className = 'px-4 py-2 rounded-lg text-sm font-medium border transition-colors ' + styles.active;
        } else {
            btn.className = 'px-4 py-2 rounded-lg text-sm font-medium border transition-colors ' + styles.inactive + ' hover:text-white';
        }
    });
}

// Apply initial filter
document.addEventListener('DOMContentLoaded', function() {
    filterTrades('pending');
});
</script>
@endsection
