@extends('layouts.app')

@section('title', 'Portfolio')

@section('content')
@if($portfolio)
{{-- Overview Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card
        label="Cash Balance"
        :value="'$' . number_format($portfolio->balance_cents / 100, 2)"
    />
    <x-stat-card
        label="Total Portfolio Value"
        :value="'$' . number_format($totalValue / 100, 2)"
    />
    <x-stat-card
        label="Positions Value"
        :value="'$' . number_format($positionsValue / 100, 2)"
    />
    <x-stat-card
        label="Today's P&L"
        :value="($dailyPnl >= 0 ? '+' : '-') . '$' . number_format(abs($dailyPnl) / 100, 2)"
        :changeType="$dailyPnl >= 0 ? 'positive' : 'negative'"
    />
</div>

{{-- Open Positions Summary --}}
@if($openPositions->count() > 0)
<x-card title="Open Positions ({{ $openPositions->count() }})" class="mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                <tr>
                    <th class="px-4 py-3">Symbol</th>
                    <th class="px-4 py-3">Side</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3 text-right">Avg Entry</th>
                    <th class="px-4 py-3 text-right">Current</th>
                    <th class="px-4 py-3 text-right">Unrealized P&L</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2a2d3a]">
                @foreach($openPositions as $pos)
                    @php
                        $unrealized = ($pos->current_price_cents - $pos->avg_entry_price_cents) * $pos->quantity;
                        if ($pos->side === 'short') $unrealized = -$unrealized;
                    @endphp
                    <tr class="hover:bg-[#0f1117]/50">
                        <td class="px-4 py-3 text-white font-medium">{{ $pos->symbol }}</td>
                        <td class="px-4 py-3"><x-badge :color="$pos->side === 'long' ? 'green' : 'red'" :text="strtoupper($pos->side)" /></td>
                        <td class="px-4 py-3 text-gray-300">{{ $pos->quantity }}</td>
                        <td class="px-4 py-3 text-right text-gray-300">${{ number_format($pos->avg_entry_price_cents / 100, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-300">${{ number_format($pos->current_price_cents / 100, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium {{ $unrealized >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                            {{ $unrealized >= 0 ? '+' : '' }}${{ number_format(abs($unrealized) / 100, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>
@endif

{{-- Quick Links --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <a href="{{ url('/portfolio/positions') }}" class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-6 hover:border-[#3b82f6] transition-colors">
        <h3 class="text-lg font-semibold text-white mb-1">Open Positions</h3>
        <p class="text-sm text-gray-500">View your current holdings</p>
    </a>
    <a href="{{ url('/portfolio/trades') }}" class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-6 hover:border-[#3b82f6] transition-colors">
        <h3 class="text-lg font-semibold text-white mb-1">Trade History</h3>
        <p class="text-sm text-gray-500">Browse all past trades</p>
    </a>
    <a href="{{ url('/portfolio/performance') }}" class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-6 hover:border-[#3b82f6] transition-colors">
        <h3 class="text-lg font-semibold text-white mb-1">Performance</h3>
        <p class="text-sm text-gray-500">Detailed analytics and metrics</p>
    </a>
</div>
@else
<x-card>
    <p class="text-gray-500 text-center py-8">No portfolio found. Create one in settings.</p>
</x-card>
@endif
@endsection
