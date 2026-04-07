@extends('layouts.app')

@section('title', 'Strategies')

@section('content')
{{-- Filters --}}
<x-card class="mb-6">
    <form method="GET" action="{{ route('strategies.index') }}" class="flex flex-wrap items-end gap-4">
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
        <div>
            <label class="block text-sm text-gray-400 mb-1">Outlook</label>
            <select name="outlook"
                    class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
                <option value="">All</option>
                <option value="bullish" {{ request('outlook') === 'bullish' ? 'selected' : '' }}>Bullish</option>
                <option value="bearish" {{ request('outlook') === 'bearish' ? 'selected' : '' }}>Bearish</option>
                <option value="neutral" {{ request('outlook') === 'neutral' ? 'selected' : '' }}>Neutral</option>
            </select>
        </div>
        <x-button type="submit" size="sm">Filter</x-button>
        <a href="{{ route('strategies.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors py-2">Clear</a>
    </form>
</x-card>

{{-- Strategy History Table --}}
<x-card title="Strategy History">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Market Outlook</th>
                <th class="px-4 py-3">Confidence</th>
                <th class="px-4 py-3">Trades Planned</th>
                <th class="px-4 py-3">Trades Executed</th>
                <th class="px-4 py-3 text-right">Day P&L</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($analyses as $analysis)
                <tr class="hover:bg-[#0f1117]/50 transition-colors cursor-pointer" onclick="window.location='{{ route('strategies.show', $analysis->id) }}'">
                    <td class="px-4 py-3 text-white whitespace-nowrap">{{ $analysis->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3">
                        <x-sentiment-badge :sentiment="$analysis->market_outlook ?? 'neutral'" />
                    </td>
                    <td class="px-4 py-3 text-gray-300">{{ number_format(($analysis->confidence ?? 0) * 100, 0) }}%</td>
                    <td class="px-4 py-3 text-gray-300">{{ $analysis->trades_planned ?? 0 }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $analysis->trades_executed ?? 0 }}</td>
                    <td class="px-4 py-3 text-right font-medium {{ ($analysis->total_pnl_cents ?? 0) >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                        {{ ($analysis->total_pnl_cents ?? 0) >= 0 ? '+' : '' }}${{ number_format(abs($analysis->total_pnl_cents ?? 0) / 100, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        <x-badge
                            :color="$analysis->status === 'completed' ? 'green' : ($analysis->status === 'active' ? 'blue' : ($analysis->status === 'failed' ? 'red' : 'gray'))"
                            :text="ucfirst($analysis->status ?? 'pending')"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No strategy analyses found.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $analyses->links('partials._pagination') }}
    </div>
</x-card>
@endsection
