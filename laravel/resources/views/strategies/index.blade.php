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

{{-- Strategy Control Panel --}}
<x-card title="Strategy Control Panel" class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Portfolio Summary --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Current Portfolio</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Cash Balance</span>
                    <span class="text-white font-medium">${{ number_format(($portfolio->balance_cents ?? 0) / 100, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Open Positions</span>
                    <span class="text-white font-medium">{{ $openPositions->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Equity</span>
                    <span class="text-white font-medium">${{ number_format($totalEquity / 100, 2) }}</span>
                </div>
                @if($brokerCred)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Broker</span>
                    <span class="text-white font-medium">{{ ucfirst($brokerCred->broker) }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Strategy Style Preview --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Current Strategy Style</h3>
            @php
                $currentStyle = $settings->strategy_style ?? 'day_trading';
                $styleLabels = [
                    'scalping' => ['icon' => "\xF0\x9F\x94\xA5", 'label' => 'Scalping', 'desc' => 'Many small trades (20-50/day)'],
                    'day_trading' => ['icon' => "\xF0\x9F\x93\x8A", 'label' => 'Day Trading', 'desc' => '5-15 trades/day'],
                    'swing_trading' => ['icon' => "\xF0\x9F\x93\x88", 'label' => 'Swing Trading', 'desc' => '1-3 trades/day'],
                    'conservative' => ['icon' => "\xF0\x9F\x8E\xAF", 'label' => 'Conservative', 'desc' => '1-2 carefully selected trades'],
                ];
                $active = $styleLabels[$currentStyle] ?? $styleLabels['day_trading'];
            @endphp
            <div class="bg-[#0f1117] border border-[#3b82f6] rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">{{ $active['icon'] }}</span>
                    <div>
                        <div class="text-white font-semibold">{{ $active['label'] }}</div>
                        <div class="text-gray-400 text-sm">{{ $active['desc'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Strategy Style Cards — clicking selects + opens modal --}}
    <div class="mt-6">
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Select Strategy &amp; Launch</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach([
                'scalping' => ['icon' => "\xF0\x9F\x94\xA5", 'label' => 'Scalping', 'desc' => 'Many small trades (20-50/day). Target 0.1-0.3% per trade. Very tight SL/TP.'],
                'day_trading' => ['icon' => "\xF0\x9F\x93\x8A", 'label' => 'Day Trading', 'desc' => '5-15 trades/day. Hold minutes to hours. Target 0.5-2% per trade.'],
                'swing_trading' => ['icon' => "\xF0\x9F\x93\x88", 'label' => 'Swing Trading', 'desc' => '1-3 trades/day. Hold hours to days. Target 2-5% per trade.'],
                'conservative' => ['icon' => "\xF0\x9F\x8E\xAF", 'label' => 'Conservative', 'desc' => '1-2 carefully selected trades. High-probability setups only.'],
            ] as $styleKey => $styleInfo)
                <label for="strategy-modal"
                       class="bg-[#0f1117] border {{ $currentStyle === $styleKey ? 'border-[#3b82f6]' : 'border-[#2a2d3a]' }} hover:border-[#3b82f6] rounded-lg p-4 transition-colors cursor-pointer block"
                       onclick="setTimeout(function(){var r=document.getElementById('style_{{ $styleKey }}');if(r)r.checked=true;},50)">
                    <div class="flex items-start space-x-3">
                        <span class="text-xl mt-0.5">{{ $styleInfo['icon'] }}</span>
                        <div>
                            <div class="text-white font-semibold text-sm">{{ $styleInfo['label'] }}</div>
                            <div class="text-gray-500 text-xs mt-1 leading-relaxed">{{ $styleInfo['desc'] }}</div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Or just open modal with current style --}}
    <div class="mt-4 flex justify-end">
        <label for="strategy-modal" class="inline-flex items-center px-5 py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold rounded-lg cursor-pointer transition-colors text-sm">
            Make New Strategy Now
        </label>
    </div>
</x-card>

{{-- CSS-only Modal (checkbox trick) --}}
<input type="checkbox" id="strategy-modal" class="hidden peer/modal">
<div class="fixed inset-0 z-50 hidden peer-checked/modal:flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <label for="strategy-modal" class="absolute inset-0 bg-black/70 cursor-pointer"></label>
    {{-- Modal Content --}}
    <div class="relative bg-[#1a1d29] border border-[#2a2d3a] rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <form method="POST" action="{{ url('/strategies/trigger') }}">
            @csrf
            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-[#2a2d3a]">
                <h2 class="text-lg font-semibold text-white">Generate New Strategy</h2>
                <label for="strategy-modal" class="text-gray-400 hover:text-white cursor-pointer text-xl">&times;</label>
            </div>

            <div class="p-6 space-y-6">
                {{-- Portfolio Overview --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Your Portfolio</h3>
                    <div class="bg-[#0f1117] rounded-lg p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Cash</span>
                            <span class="text-white font-medium">${{ number_format(($portfolio->balance_cents ?? 0) / 100, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Open Positions</span>
                            <span class="text-white font-medium">{{ $openPositions->count() }}</span>
                        </div>
                        @foreach($openPositions as $pos)
                            @php
                                $unrealizedPnl = ((float) $pos->current_price_cents - (float) $pos->avg_entry_price_cents) * (float) $pos->quantity;
                            @endphp
                            <div class="flex justify-between text-xs pl-4">
                                <span class="text-gray-500">{{ $pos->symbol }} ({{ number_format((float) $pos->quantity, 4) }})</span>
                                <span class="{{ $unrealizedPnl >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                                    {{ $unrealizedPnl >= 0 ? '+' : '' }}${{ number_format(abs($unrealizedPnl) / 100, 2) }}
                                </span>
                            </div>
                        @endforeach
                        <div class="flex justify-between text-sm pt-2 border-t border-[#2a2d3a]">
                            <span class="text-gray-400">Total Equity</span>
                            <span class="text-white font-semibold">${{ number_format($totalEquity / 100, 2) }}</span>
                        </div>
                        @if($brokerCred)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Broker</span>
                            <span class="text-gray-300">{{ ucfirst($brokerCred->broker) }}{{ $brokerCred->broker === 'paper' ? ' (simulated)' : '' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Strategy Style Selection --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Strategy Style</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach([
                            'scalping' => ['icon' => "\xF0\x9F\x94\xA5", 'label' => 'Scalping', 'desc' => 'Many small trades (20-50/day). Target 0.1-0.3% per trade. Very tight SL/TP. High frequency.'],
                            'day_trading' => ['icon' => "\xF0\x9F\x93\x8A", 'label' => 'Day Trading', 'desc' => '5-15 trades/day. Hold minutes to hours. Target 0.5-2% per trade. Moderate risk.'],
                            'swing_trading' => ['icon' => "\xF0\x9F\x93\x88", 'label' => 'Swing Trading', 'desc' => '1-3 trades/day. Hold hours to days. Target 2-5% per trade. Lower frequency.'],
                            'conservative' => ['icon' => "\xF0\x9F\x8E\xAF", 'label' => 'Conservative', 'desc' => '1-2 carefully selected trades. Focus on high-probability setups. Minimal risk.'],
                        ] as $styleKey => $styleInfo)
                            <label class="block cursor-pointer">
                                <input type="radio" name="strategy_style" id="style_{{ $styleKey }}" value="{{ $styleKey }}" {{ $currentStyle === $styleKey ? 'checked' : '' }}
                                    class="hidden peer">
                                <div class="bg-[#0f1117] border border-[#2a2d3a] peer-checked:border-[#3b82f6] peer-checked:bg-[#1e2433] rounded-lg p-4 transition-colors hover:border-[#3b82f6]/50">
                                    <div class="flex items-start space-x-3">
                                        <span class="text-xl mt-0.5">{{ $styleInfo['icon'] }}</span>
                                        <div>
                                            <div class="text-white font-semibold text-sm">{{ $styleInfo['label'] }}</div>
                                            <div class="text-gray-500 text-xs mt-1 leading-relaxed">{{ $styleInfo['desc'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Custom Notes --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Custom Notes for This Analysis</h3>
                    <textarea name="custom_notes" rows="3"
                        placeholder="Any specific instructions for this analysis? E.g., 'Focus on BTC momentum' or 'Close all positions and go to cash'"
                        class="w-full px-4 py-3 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm placeholder-gray-600 focus:outline-none focus:border-[#3b82f6] resize-none"></textarea>
                    <p class="text-xs text-gray-600 mt-1">Optional. These notes are one-time and will not be saved.</p>
                </div>

                {{-- Include Scraper Data --}}
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="include_scraper_data" value="1" checked
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <div>
                            <span class="text-sm text-white">Include news & scraper data</span>
                            <p class="text-xs text-gray-500">Send scraped articles and sentiment analysis to Claude. Uncheck for faster, data-only analysis.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-[#2a2d3a]">
                <label for="strategy-modal" class="px-4 py-2 text-sm text-gray-400 hover:text-white cursor-pointer transition-colors">Cancel</label>
                <button type="submit" class="px-5 py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold rounded-lg transition-colors text-sm">
                    Generate Strategy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Strategy History Table --}}
<x-card title="Strategy History">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Style</th>
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
                    <td class="px-4 py-3 text-gray-400 text-xs">
                        @php
                            $styleIcons = ['scalping'=>'🔥','day_trading'=>'📊','swing_trading'=>'📈','conservative'=>'🎯'];
                        @endphp
                        {{ $styleIcons[$analysis->strategy_style ?? ''] ?? '' }}
                        {{ ucfirst(str_replace('_', ' ', $analysis->strategy_style ?? 'N/A')) }}
                    </td>
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
                        <div class="flex items-center space-x-2">
                            <x-badge
                                :color="$analysis->status === 'completed' ? 'green' : ($analysis->status === 'active' ? 'blue' : ($analysis->status === 'failed' ? 'red' : 'amber'))"
                                :text="ucfirst($analysis->status ?? 'pending')"
                            />
                            @if($analysis->status === 'pending')
                                <form method="POST" action="{{ url('/strategies/' . $analysis->id . '/cancel') }}" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#ef4444] hover:text-red-400" onclick="return confirm('Cancel this analysis?')">Cancel</button>
                                </form>
                            @endif
                        </div>
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
