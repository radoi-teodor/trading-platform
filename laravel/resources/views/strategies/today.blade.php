@extends('layouts.app')

@section('title', "Today's Strategy")

@section('content')
<meta http-equiv="refresh" content="60">

@if(isset($analysis) && $analysis)
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h2 class="text-2xl font-bold text-white">Today's Strategy</h2>
                <x-sentiment-badge :sentiment="$analysis->market_outlook ?? 'neutral'" />
                <x-status-dot :status="$analysis->status === 'active' ? 'running' : ($analysis->status === 'failed' ? 'error' : 'stopped')" />
                <span class="text-sm text-gray-400">{{ ucfirst($analysis->status ?? 'pending') }}</span>
            </div>
            <p class="text-sm text-gray-400">
                Confidence: <span class="text-white font-medium">{{ number_format(($analysis->confidence ?? 0) * 100, 0) }}%</span>
                &middot; Progress: <span class="text-white font-medium">{{ $analysis->trades_executed ?? 0 }}/{{ $analysis->trades_planned ?? 0 }} trades executed</span>
            </p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-4">
            <div class="text-right">
                <span class="text-3xl font-bold {{ ($analysis->total_pnl_cents ?? 0) >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                    {{ ($analysis->total_pnl_cents ?? 0) >= 0 ? '+' : '' }}${{ number_format(abs($analysis->total_pnl_cents ?? 0) / 100, 2) }}
                </span>
                <p class="text-sm text-gray-500">Live P&L</p>
            </div>
            <form method="POST" action="{{ route('strategies.trigger') }}">
                @csrf
                <x-button type="submit" color="amber" size="sm">Re-run Analysis</x-button>
            </form>
        </div>
    </div>

    {{-- Claude's Reasoning --}}
    <x-card title="Claude's Reasoning" class="mb-6">
        <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->reasoning ?? 'No reasoning provided.' }}</div>
    </x-card>

    {{-- Lessons from Yesterday --}}
    @if($analysis->lessons_from_yesterday)
        <x-card title="Lessons from Yesterday" class="mb-6">
            <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->lessons_from_yesterday }}</div>
        </x-card>
    @endif

    {{-- Risk Assessment --}}
    @if($analysis->risk_assessment)
        <div class="rounded-lg border border-[#f59e0b]/30 bg-[#1a1d29] p-6 mb-6">
            <h3 class="text-lg font-semibold text-[#f59e0b] mb-3">Risk Assessment</h3>
            <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $analysis->risk_assessment }}</div>
        </div>
    @endif

    {{-- Planned Trades --}}
    @if($analysis->analysisTrades->count() > 0)
        <x-card title="Planned Trades" class="mb-6">
            <x-data-table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3 text-right">Entry</th>
                        <th class="px-4 py-3 text-right">Stop Loss</th>
                        <th class="px-4 py-3 text-right">Take Profit</th>
                        <th class="px-4 py-3">Urgency</th>
                        <th class="px-4 py-3">Confidence</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Reasoning</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                    @foreach($analysis->analysisTrades as $trade)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-white font-medium">{{ $trade->symbol }}</td>
                            <td class="px-4 py-3">
                                <x-badge :color="$trade->action === 'buy' ? 'green' : 'red'" :text="strtoupper($trade->action)" />
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ ucfirst($trade->order_type ?? 'market') }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $trade->quantity }}</td>
                            <td class="px-4 py-3 text-right text-gray-300">
                                @if($trade->entry_price_cents)
                                    ${{ number_format($trade->entry_price_cents / 100, 2) }}
                                @else
                                    Market
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#ef4444]">
                                @if($trade->stop_loss_cents) ${{ number_format($trade->stop_loss_cents / 100, 2) }} @else — @endif
                            </td>
                            <td class="px-4 py-3 text-right text-[#22c55e]">
                                @if($trade->take_profit_cents) ${{ number_format($trade->take_profit_cents / 100, 2) }} @else — @endif
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
                                    :text="ucfirst($trade->status ?? 'pending')"
                                />
                            </td>
                            <td class="px-4 py-3">
                                <details class="text-sm">
                                    <summary class="text-[#3b82f6] cursor-pointer hover:text-blue-400">View</summary>
                                    <p class="mt-2 text-gray-400 text-xs max-w-xs">{{ $trade->reasoning ?? 'No reasoning.' }}</p>
                                    @if($trade->skip_reason)
                                        <p class="mt-1 text-[#f59e0b] text-xs">Skipped: {{ $trade->skip_reason }}</p>
                                    @endif
                                </details>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        </x-card>
    @endif

    {{-- Positions to Close --}}
    @if($analysis->analysisCloses->count() > 0)
        <x-card title="Positions to Close" class="mb-6">
            <x-data-table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Urgency</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
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
                                    :text="ucfirst($close->status ?? 'pending')"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        </x-card>
    @endif

    {{-- Watchlist Alerts --}}
    @if($analysis->analysisAlerts->count() > 0)
        <x-card title="Watchlist Alerts" class="mb-6">
            <x-data-table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3">Symbol</th>
                        <th class="px-4 py-3">Condition</th>
                        <th class="px-4 py-3">Triggered</th>
                        <th class="px-4 py-3">Triggered At</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                    @foreach($analysis->analysisAlerts as $alert)
                        <tr class="hover:bg-[#0f1117]/50 transition-colors">
                            <td class="px-4 py-3 text-white font-medium">{{ $alert->symbol }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $alert->condition_text }}</td>
                            <td class="px-4 py-3">
                                @if($alert->triggered)
                                    <span class="text-[#22c55e]">Yes</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                {{ $alert->triggered_at ? $alert->triggered_at->format('H:i:s') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        </x-card>
    @endif

    {{-- Raw Claude I/O --}}
    <x-card class="mb-6">
        <details>
            <summary class="text-lg font-semibold text-white cursor-pointer hover:text-[#3b82f6] transition-colors">
                View Raw Prompt & Response
            </summary>
            <div class="mt-4 space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-400 mb-2">Prompt Sent to Claude</h4>
                    <pre class="bg-[#0f1117] border border-[#2a2d3a] rounded-lg p-4 text-xs text-gray-300 overflow-x-auto max-h-96 whitespace-pre-wrap">{{ $analysis->prompt_sent ?? 'No prompt data available.' }}</pre>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-400 mb-2">Claude's Raw Response</h4>
                    <pre class="bg-[#0f1117] border border-[#2a2d3a] rounded-lg p-4 text-xs text-gray-300 overflow-x-auto max-h-96 whitespace-pre-wrap">{{ $analysis->raw_response ?? 'No response data available.' }}</pre>
                </div>
            </div>
        </details>
    </x-card>

@else
    {{-- No analysis today --}}
    <x-card class="text-center py-12">
        <div class="max-w-md mx-auto">
            <x-icons.sparkles class="w-12 h-12 text-gray-600 mx-auto mb-4" />
            <h3 class="text-xl font-semibold text-white mb-2">No Analysis Today</h3>
            <p class="text-gray-500 mb-6">Claude hasn't analyzed the market yet today. Run an analysis to get trading recommendations.</p>
            <form method="POST" action="{{ route('strategies.trigger') }}">
                @csrf
                <x-button type="submit">Run Analysis Now</x-button>
            </form>
        </div>
    </x-card>
@endif
@endsection
