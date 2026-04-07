@extends('layouts.app')

@section('title', 'Open Positions')

@section('content')
<x-card title="Open Positions" subtitle="Currently held positions">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Symbol</th>
                <th class="px-4 py-3">Side</th>
                <th class="px-4 py-3 text-right">Quantity</th>
                <th class="px-4 py-3 text-right">Avg Entry</th>
                <th class="px-4 py-3 text-right">Current Price</th>
                <th class="px-4 py-3 text-right">Unrealized P&L</th>
                <th class="px-4 py-3">Opened At</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($positions as $position)
                @php
                    $unrealizedPnl = ($position->current_price_cents - $position->avg_entry_price_cents) * $position->quantity;
                    if ($position->side === 'short') $unrealizedPnl = -$unrealizedPnl;
                @endphp
                <tr class="hover:bg-[#0f1117]/50 transition-colors">
                    <td class="px-4 py-3 text-white font-medium">{{ $position->symbol }}</td>
                    <td class="px-4 py-3">
                        <x-badge :color="$position->side === 'long' ? 'green' : 'red'" :text="strtoupper($position->side)" />
                    </td>
                    <td class="px-4 py-3 text-right text-gray-300">{{ $position->quantity }}</td>
                    <td class="px-4 py-3 text-right text-gray-300">${{ number_format($position->avg_entry_price_cents / 100, 2) }}</td>
                    <td class="px-4 py-3 text-right text-gray-300">${{ number_format($position->current_price_cents / 100, 2) }}</td>
                    <td class="px-4 py-3 text-right font-medium {{ $unrealizedPnl >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                        {{ $unrealizedPnl >= 0 ? '+' : '' }}${{ number_format(abs($unrealizedPnl) / 100, 2) }}
                    </td>
                    <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $position->opened_at->format('M d, Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No open positions.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>
</x-card>
@endsection
