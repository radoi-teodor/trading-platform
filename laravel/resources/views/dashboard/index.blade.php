@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<meta http-equiv="refresh" content="60">

{{-- Row 1: Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <x-stat-card
        label="Portfolio Value"
        :value="'$' . number_format(($totalValue ?? 0) / 100, 2)"
    />
    <x-stat-card
        label="Today's P&L"
        :value="($todayPnl >= 0 ? '+' : '-') . '$' . number_format(abs($todayPnl ?? 0) / 100, 2)"
        :changeType="($todayPnl ?? 0) >= 0 ? 'positive' : 'negative'"
    />
    <x-stat-card
        label="Open Positions"
        :value="$openPositions->count()"
    />
    <x-stat-card label="Active Bots" :value="$activeAgents ?? 0">
        <div class="mt-2">
            <x-status-dot :status="($activeAgents ?? 0) > 0 ? 'running' : 'stopped'" />
            <span class="text-xs text-gray-500 ml-1">{{ ($activeAgents ?? 0) > 0 ? 'Running' : 'Stopped' }}</span>
        </div>
    </x-stat-card>
    <x-stat-card label="Active Scrapers" :value="$activeScrapers ?? 0">
        <div class="mt-2">
            <x-status-dot :status="($activeScrapers ?? 0) > 0 ? 'running' : 'stopped'" />
            <span class="text-xs text-gray-500 ml-1">{{ ($activeScrapers ?? 0) > 0 ? 'Running' : 'Stopped' }}</span>
        </div>
    </x-stat-card>
</div>

{{-- Row 2: Today's Strategy Summary --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <x-card title="Today's Strategy">
        @if($todayStrategy)
            <div class="flex items-center space-x-3 mb-4">
                <x-badge
                    :color="$todayStrategy->market_outlook === 'bullish' ? 'green' : ($todayStrategy->market_outlook === 'bearish' ? 'red' : 'gray')"
                    :text="ucfirst($todayStrategy->market_outlook)"
                />
                <span class="text-sm text-gray-400">Confidence: <span class="text-white font-medium">{{ number_format($todayStrategy->confidence * 100, 0) }}%</span></span>
            </div>

            <p class="text-sm text-gray-400 mb-4">{{ Str::limit($todayStrategy->reasoning, 300) }}</p>

            <div class="flex items-center justify-between">
                <a href="{{ url('/strategies/today') }}" class="text-sm text-[#3b82f6] hover:text-blue-400 transition-colors">
                    View Full Strategy &rarr;
                </a>
                <span class="text-xs text-gray-500">{{ $todayStrategy->trades_executed }}/{{ $todayStrategy->trades_planned }} trades executed</span>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">No analysis has been run today.</p>
                <form method="POST" action="{{ url('/strategies/trigger') }}">
                    @csrf
                    <x-button type="submit">Run Analysis Now</x-button>
                </form>
            </div>
        @endif
    </x-card>

    <x-card title="Open Positions">
        @if($openPositions->count() > 0)
            <div class="space-y-3">
                @foreach($openPositions->take(8) as $position)
                    @php
                        $unrealizedPnl = ($position->current_price_cents - $position->avg_entry_price_cents) * $position->quantity;
                        if ($position->side === 'short') $unrealizedPnl = -$unrealizedPnl;
                    @endphp
                    <div class="flex items-center justify-between py-2 border-b border-[#2a2d3a] last:border-0">
                        <div class="flex items-center space-x-3">
                            <x-badge :color="$position->side === 'long' ? 'green' : 'red'" :text="strtoupper($position->side)" />
                            <span class="text-sm text-white font-medium">{{ $position->symbol }}</span>
                            <span class="text-xs text-gray-500">{{ $position->quantity }} shares</span>
                        </div>
                        <span class="text-sm font-medium {{ $unrealizedPnl >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                            {{ $unrealizedPnl >= 0 ? '+' : '' }}${{ number_format(abs($unrealizedPnl) / 100, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>
            @if($openPositions->count() > 8)
                <a href="{{ url('/portfolio/positions') }}" class="block text-sm text-[#3b82f6] hover:text-blue-400 mt-3">View all {{ $openPositions->count() }} positions &rarr;</a>
            @endif
        @else
            <p class="text-sm text-gray-500 text-center py-4">No open positions.</p>
        @endif
    </x-card>
</div>

{{-- Row 3: Recent Activity --}}
<x-card title="Recent Activity" class="mb-6">
    @if($recentActivity->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                    <tr>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2d3a]">
                    @foreach($recentActivity as $log)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $log->created_at->format('H:i:s') }}</td>
                            <td class="px-4 py-3">
                                <x-badge
                                    :color="$log->level === 'trade' ? 'blue' : ($log->level === 'error' ? 'red' : ($log->level === 'warning' ? 'amber' : 'gray'))"
                                    :text="ucfirst($log->level)"
                                />
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ $log->message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-sm text-gray-500 text-center py-4">No recent activity.</p>
    @endif
</x-card>

{{-- Row 4: Watchlist --}}
<x-card title="Watchlist" class="mb-6">
    @if($watchlistItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                        <tr>
                            <th class="px-4 py-3">Symbol</th>
                            <th class="px-4 py-3 text-right">Price</th>
                            <th class="px-4 py-3 text-right">Change</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2a2d3a]">
                        @foreach($watchlistItems as $item)
                            @php
                                $price = $watchlistPrices[$item->symbol] ?? null;
                                $closePrice = $price ? $price->close : 0;
                                $openPrice = $price ? $price->open : 0;
                                $change = $openPrice > 0 ? (($closePrice - $openPrice) / $openPrice) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-[#0f1117]/50 transition-colors">
                                <td class="px-4 py-3 text-white font-medium">{{ $item->symbol }}</td>
                                <td class="px-4 py-3 text-right text-gray-300">${{ number_format($closePrice / 100, 2) }}</td>
                                <td class="px-4 py-3 text-right {{ $change >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                <canvas id="watchlistChart" height="200"></canvas>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-500 text-center py-4">
            No watchlist items. <a href="{{ url('/watchlist') }}" class="text-[#3b82f6] hover:text-blue-400">Add symbols</a>
        </p>
    @endif
</x-card>

@if($watchlistItems->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var labels = @json($watchlistItems->pluck('symbol')->values());
    var prices = @json(collect($watchlistPrices)->map(function($p) { return $p ? ['open' => $p->open, 'close' => $p->close] : null; }));

    var changes = labels.map(function(sym) {
        var p = prices[sym];
        if (!p || !p.open) return 0;
        return ((p.close - p.open) / p.open * 100).toFixed(2);
    });

    var ctx = document.getElementById('watchlistChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Change %',
                data: changes,
                backgroundColor: changes.map(function(v) {
                    return v >= 0 ? 'rgba(34, 197, 94, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                }),
                borderColor: changes.map(function(v) {
                    return v >= 0 ? '#22c55e' : '#ef4444';
                }),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#9ca3af' }, grid: { color: '#2a2d3a' } },
                y: { ticks: { color: '#9ca3af' }, grid: { color: '#2a2d3a' } }
            }
        }
    });
});
</script>
@endif
@endsection
