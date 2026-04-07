@extends('layouts.app')

@section('title', 'Watchlist')

@section('content')
{{-- Add Symbol --}}
<x-card class="mb-6">
    <form method="POST" action="{{ route('watchlist.store') }}" class="flex items-end gap-4">
        @csrf
        <div class="flex-1 max-w-xs">
            <label class="block text-sm text-gray-400 mb-1">Add Symbol</label>
            <input type="text" name="symbol" placeholder="e.g. AAPL" required
                   class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
            @error('symbol')
                <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
            @enderror
        </div>
        <x-button type="submit">Add to Watchlist</x-button>
    </form>
</x-card>

<x-card title="Watchlist">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Symbol</th>
                <th class="px-4 py-3 text-right">Current Price</th>
                <th class="px-4 py-3 text-right">Daily Change</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($items as $item)
                @php
                    $price = $prices[$item->symbol] ?? null;
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
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('watchlist.destroy', $item) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-[#ef4444] hover:text-red-400 transition-colors"
                                    onclick="return confirm('Remove {{ $item->symbol }} from watchlist?')">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No symbols in watchlist. Add one above.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>
</x-card>
@endsection
