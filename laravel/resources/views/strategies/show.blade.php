@extends('layouts.app')

@section('title', 'Strategy Detail')

@section('content')
{{-- Section 1: Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <div class="flex items-center space-x-3 mb-2">
            <h2 class="text-2xl font-bold text-white">{{ $analysis->date->format('F d, Y') }}</h2>
            <x-badge
                :color="$analysis->market_outlook === 'bullish' ? 'green' : ($analysis->market_outlook === 'bearish' ? 'red' : 'gray')"
                :text="ucfirst($analysis->market_outlook)"
            />
            <x-badge
                :color="$analysis->status === 'completed' ? 'green' : ($analysis->status === 'active' ? 'blue' : ($analysis->status === 'failed' ? 'red' : 'gray'))"
                :text="ucfirst($analysis->status)"
            />
        </div>
        <p class="text-sm text-gray-400">
            @if($analysis->strategy_style)
                @php $styleIcons = ['scalping'=>'🔥','day_trading'=>'📊','swing_trading'=>'📈','conservative'=>'🎯']; @endphp
                {{ $styleIcons[$analysis->strategy_style] ?? '' }} {{ ucfirst(str_replace('_', ' ', $analysis->strategy_style)) }} &middot;
            @endif
            Confidence: <span class="text-white font-medium">{{ number_format($analysis->confidence * 100, 0) }}%</span>
            &middot; Trades: {{ $analysis->trades_executed }}/{{ $analysis->trades_planned }} executed
            &middot; Next Review: {{ ucfirst(str_replace('_', ' ', $analysis->next_review)) }}
        </p>
    </div>
    <div class="mt-4 md:mt-0">
        <span class="text-3xl font-bold {{ $analysis->total_pnl_cents >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
            {{ $analysis->total_pnl_cents >= 0 ? '+' : '' }}${{ number_format(abs($analysis->total_pnl_cents) / 100, 2) }}
        </span>
        <p class="text-sm text-gray-500 text-right">Day P&L</p>
    </div>
</div>

{{-- Section 2: Claude's Reasoning --}}
<x-card title="Claude's Reasoning" class="mb-6">
    <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->reasoning }}</div>
</x-card>

{{-- Section 3: Lessons from Yesterday --}}
@if($analysis->lessons_from_yesterday)
    <x-card title="Lessons from Yesterday" class="mb-6">
        <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->lessons_from_yesterday }}</div>
    </x-card>
@endif

{{-- Section 4: Risk Assessment --}}
@if($analysis->risk_assessment)
    <div class="rounded-lg border border-[#f59e0b]/30 bg-[#1a1d29] p-6 mb-6">
        <h3 class="text-lg font-semibold text-[#f59e0b] mb-3">Risk Assessment</h3>
        <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->risk_assessment }}</div>
    </div>
@endif

{{-- Section 5: Planned Trades --}}
@if($analysis->analysisTrades->count() > 0)
    <x-card title="Planned Trades" class="mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Direction</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3 text-right">Entry</th>
                        <th class="px-4 py-3 text-right">Stop Loss</th>
                        <th class="px-4 py-3 text-right">SL%</th>
                        <th class="px-4 py-3 text-right">Take Profit</th>
                        <th class="px-4 py-3 text-right">TP%</th>
                        <th class="px-4 py-3">Urgency</th>
                        <th class="px-4 py-3">Confidence</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Reentry</th>
                        <th class="px-4 py-3">Reasoning</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2d3a]">
                    @foreach($analysis->analysisTrades as $trade)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ url('/strategies/' . $analysis->id . '/trade/' . $trade->id . '/chart') }}"
                                   class="text-white font-medium hover:text-[#3b82f6] transition-colors">
                                    {{ $trade->symbol }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $direction = $trade->direction ?? ($trade->action === 'buy' ? 'long' : 'short');
                                @endphp
                                <x-badge :color="$direction === 'long' ? 'green' : 'red'" :text="strtoupper($direction)" />
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ ucfirst($trade->order_type) }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $trade->quantity }}</td>
                            <td class="px-4 py-3 text-right text-gray-300">
                                @if($trade->entry_price_cents)
                                    ${{ number_format($trade->entry_price_cents / 100, 2) }}
                                @else
                                    Market
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#ef4444]">
                                @if($trade->stop_loss_cents)
                                    ${{ number_format($trade->stop_loss_cents / 100, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#ef4444]">
                                @if($trade->stop_loss_pct)
                                    {{ number_format($trade->stop_loss_pct, 2) }}%
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#22c55e]">
                                @if($trade->take_profit_cents)
                                    ${{ number_format($trade->take_profit_cents / 100, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#22c55e]">
                                @if($trade->take_profit_pct)
                                    {{ number_format($trade->take_profit_pct, 2) }}%
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-badge
                                    :color="$trade->urgency === 'immediate' ? 'red' : ($trade->urgency === 'wait_for_dip' ? 'amber' : 'gray')"
                                    :text="ucfirst(str_replace('_', ' ', $trade->urgency))"
                                />
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ number_format($trade->confidence * 100, 0) }}%</td>
                            <td class="px-4 py-3">
                                <x-badge
                                    :color="$trade->status === 'executed' ? 'green' : ($trade->status === 'pending' ? 'amber' : ($trade->status === 'failed' ? 'red' : 'gray'))"
                                    :text="ucfirst($trade->status)"
                                />
                            </td>
                            <td class="px-4 py-3 text-gray-300 text-xs">
                                {{ ($trade->reentry_allowed ?? false) ? 'Yes (' . ($trade->max_entries ?? 1) . 'x)' : 'No' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ url('/strategies/' . $analysis->id . '/trade/' . $trade->id . '/chart') }}"
                                       class="inline-flex items-center text-xs text-[#3b82f6] hover:text-blue-400 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                        Chart
                                    </a>
                                    <details class="text-sm">
                                        <summary class="text-[#3b82f6] cursor-pointer hover:text-blue-400">View</summary>
                                        <p class="mt-2 text-gray-400 text-xs max-w-xs">{{ $trade->reasoning }}</p>
                                        @if($trade->skip_reason)
                                            <p class="mt-1 text-[#f59e0b] text-xs">Skipped: {{ $trade->skip_reason }}</p>
                                        @endif
                                    </details>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@endif

{{-- Section 6: Positions to Close --}}
@if($analysis->analysisCloses->count() > 0)
    <x-card title="Positions to Close" class="mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Urgency</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2d3a]">
                    @foreach($analysis->analysisCloses as $close)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-white font-medium">{{ $close->symbol }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $close->reason }}</td>
                            <td class="px-4 py-3">
                                <x-badge
                                    :color="$close->urgency === 'immediate' ? 'red' : 'amber'"
                                    :text="ucfirst(str_replace('_', ' ', $close->urgency))"
                                />
                            </td>
                            <td class="px-4 py-3">
                                <x-badge
                                    :color="$close->status === 'closed' ? 'green' : ($close->status === 'pending' ? 'amber' : 'gray')"
                                    :text="ucfirst($close->status)"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@endif

{{-- Section 7: Watchlist Alerts --}}
@if($analysis->analysisAlerts->count() > 0)
    <x-card title="Watchlist Alerts" class="mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Condition</th>
                        <th class="px-4 py-3">Triggered</th>
                        <th class="px-4 py-3">Triggered At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2d3a]">
                    @foreach($analysis->analysisAlerts as $alert)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-white font-medium">{{ $alert->symbol }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $alert->condition_text }}</td>
                            <td class="px-4 py-3">
                                @if($alert->triggered)
                                    <span class="inline-flex items-center text-[#22c55e]">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                {{ $alert->triggered_at ? \Carbon\Carbon::parse($alert->triggered_at)->format('H:i:s') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@endif

{{-- Section 8: Execution Results --}}
@php
    $executedTrades = $analysis->analysisTrades->where('status', 'executed')->filter(fn($t) => $t->executedOrder);
@endphp
@if($executedTrades->count() > 0)
    <x-card title="Execution Results" class="mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-[#0f1117]">
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Side</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3 text-right">Planned Price</th>
                        <th class="px-4 py-3 text-right">Fill Price</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2d3a]">
                    @foreach($executedTrades as $trade)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-white font-medium">{{ $trade->symbol }}</td>
                            <td class="px-4 py-3">
                                <x-badge :color="$trade->action === 'buy' ? 'green' : 'red'" :text="strtoupper($trade->action)" />
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ $trade->quantity }}</td>
                            <td class="px-4 py-3 text-right text-gray-400">
                                @if($trade->entry_price_cents)
                                    ${{ number_format($trade->entry_price_cents / 100, 2) }}
                                @else
                                    Market
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-gray-300">
                                @if($trade->executedOrder && $trade->executedOrder->price_cents)
                                    ${{ number_format($trade->executedOrder->price_cents / 100, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-badge color="green" text="Executed" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="mt-4 pt-4 border-t border-[#2a2d3a] flex items-center justify-between">
            <span class="text-sm text-gray-400">
                {{ $executedTrades->count() }} of {{ $analysis->trades_planned }} trades executed
                @if($analysis->analysisTrades->where('status', 'skipped')->count() > 0)
                    &middot; {{ $analysis->analysisTrades->where('status', 'skipped')->count() }} skipped
                @endif
                @if($analysis->analysisTrades->where('status', 'failed')->count() > 0)
                    &middot; {{ $analysis->analysisTrades->where('status', 'failed')->count() }} failed
                @endif
            </span>
            <span class="text-lg font-bold {{ $analysis->total_pnl_cents >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                Total: {{ $analysis->total_pnl_cents >= 0 ? '+' : '' }}${{ number_format(abs($analysis->total_pnl_cents) / 100, 2) }}
            </span>
        </div>
    </x-card>
@endif

{{-- Section 9: Raw Claude I/O --}}
<x-card class="mb-6">
    <details>
        <summary class="text-lg font-semibold text-white cursor-pointer hover:text-[#3b82f6] transition-colors">
            View Raw Prompt & Response
        </summary>
        <div class="mt-4 space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-400 mb-2">Prompt Sent to Claude</h4>
                <pre class="bg-[#0f1117] border border-[#2a2d3a] rounded-lg p-4 text-xs text-gray-300 overflow-x-auto max-h-96 whitespace-pre-wrap">{{ $analysis->prompt_sent }}</pre>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-400 mb-2">Claude's Raw JSON Response</h4>
                <pre class="bg-[#0f1117] border border-[#2a2d3a] rounded-lg p-4 text-xs text-gray-300 overflow-x-auto max-h-96 whitespace-pre-wrap">{{ $analysis->raw_response }}</pre>
            </div>
        </div>
    </details>
</x-card>

<div class="mb-6">
    <a href="{{ url('/strategies') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Strategy History</a>
</div>
@endsection
