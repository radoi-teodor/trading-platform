@extends('layouts.app')

@section('title', 'Trade History')

@section('content')
{{-- Filters --}}
<x-card class="mb-6">
    <form method="GET" action="{{ route('portfolio.trades') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Symbol</label>
            <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="e.g. AAPL"
                   class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
        </div>
        <x-button type="submit" size="sm">Filter</x-button>
        <a href="{{ route('portfolio.trades') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors py-2">Clear</a>
    </form>
</x-card>

<x-card title="Trades">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Symbol</th>
                <th class="px-4 py-3">Side</th>
                <th class="px-4 py-3 text-right">Quantity</th>
                <th class="px-4 py-3 text-right">Price</th>
                <th class="px-4 py-3 text-right">P&L</th>
                <th class="px-4 py-3 text-right">Fee</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($trades as $trade)
                <tr class="hover:bg-[#0f1117]/50 transition-colors">
                    <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $trade->executed_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3 text-white font-medium">{{ $trade->symbol }}</td>
                    <td class="px-4 py-3">
                        <x-badge :color="$trade->side === 'buy' ? 'green' : 'red'" :text="strtoupper($trade->side)" />
                    </td>
                    <td class="px-4 py-3 text-right text-gray-300">{{ $trade->quantity }}</td>
                    <td class="px-4 py-3 text-right text-gray-300">${{ number_format($trade->price_cents / 100, 2) }}</td>
                    <td class="px-4 py-3 text-right font-medium {{ $trade->pnl_cents >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                        {{ $trade->pnl_cents >= 0 ? '+' : '' }}${{ number_format(abs($trade->pnl_cents) / 100, 2) }}
                    </td>
                    <td class="px-4 py-3 text-right text-gray-500">${{ number_format($trade->fee_cents / 100, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No trades found.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $trades->links('partials._pagination') }}
    </div>
</x-card>
@endsection
