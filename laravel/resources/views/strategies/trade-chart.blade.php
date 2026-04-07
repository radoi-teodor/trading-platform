@extends('layouts.app')

@section('title', $symbol . ' Chart')

@section('content')
<meta http-equiv="refresh" content="60">

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ url('/strategies/' . $analysis->id) }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-3xl font-bold text-white">{{ $symbol }}</h2>
        <x-badge
            :color="$trade->action === 'buy' ? 'green' : 'red'"
            :text="strtoupper($trade->action)"
        />
        <x-badge
            :color="$trade->status === 'executed' ? 'green' : ($trade->status === 'pending' ? 'amber' : ($trade->status === 'failed' ? 'red' : 'gray'))"
            :text="ucfirst($trade->status)"
        />
    </div>
    <div class="mt-3 md:mt-0 text-sm text-gray-400">
        Strategy: {{ $analysis->date->format('M d, Y') }}
        &middot; Auto-refreshes every 60s
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
    {{-- Chart area: spans 3 columns on xl --}}
    <div class="xl:col-span-3 space-y-0">
        {{-- Candlestick chart --}}
        <div class="rounded-t-lg border border-[#2a2d3a] bg-[#1a1d29] p-2">
            <div id="candlestick-chart" style="height: 600px;"></div>
        </div>
        {{-- Volume chart --}}
        <div class="rounded-b-lg border border-t-0 border-[#2a2d3a] bg-[#1a1d29] p-2">
            <div id="volume-chart" style="height: 150px;"></div>
        </div>
    </div>

    {{-- Trade details panel --}}
    <div class="xl:col-span-1">
        <div class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-6 space-y-5">
            <h3 class="text-lg font-semibold text-white">Trade Details</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Symbol</span>
                    <span class="text-white font-medium">{{ $trade->symbol }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Action</span>
                    <x-badge :color="$trade->action === 'buy' ? 'green' : 'red'" :text="strtoupper($trade->action)" />
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Order Type</span>
                    <span class="text-gray-300">{{ ucfirst($trade->order_type) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Quantity</span>
                    <span class="text-gray-300">{{ $trade->quantity }}</span>
                </div>
            </div>

            <div class="border-t border-[#2a2d3a] pt-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Entry Price</span>
                    <span class="text-[#3b82f6] font-medium">
                        @if($trade->entry_price_cents)
                            ${{ number_format($trade->entry_price_cents / 100, 2) }}
                        @else
                            Market
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Stop Loss</span>
                    <span class="text-[#ef4444] font-medium">
                        @if($trade->stop_loss_cents)
                            ${{ number_format($trade->stop_loss_cents / 100, 2) }}
                        @else
                            &mdash;
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Take Profit</span>
                    <span class="text-[#22c55e] font-medium">
                        @if($trade->take_profit_cents)
                            ${{ number_format($trade->take_profit_cents / 100, 2) }}
                        @else
                            &mdash;
                        @endif
                    </span>
                </div>
            </div>

            <div class="border-t border-[#2a2d3a] pt-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Confidence</span>
                    <span class="text-white font-medium">{{ number_format($trade->confidence * 100, 0) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Urgency</span>
                    <x-badge
                        :color="$trade->urgency === 'immediate' ? 'red' : ($trade->urgency === 'wait_for_dip' ? 'amber' : 'gray')"
                        :text="ucfirst(str_replace('_', ' ', $trade->urgency))"
                    />
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status</span>
                    <x-badge
                        :color="$trade->status === 'executed' ? 'green' : ($trade->status === 'pending' ? 'amber' : ($trade->status === 'failed' ? 'red' : 'gray'))"
                        :text="ucfirst($trade->status)"
                    />
                </div>
                @if($trade->re_entry_allowed !== null)
                <div class="flex justify-between">
                    <span class="text-gray-400">Re-entry</span>
                    <span class="text-gray-300">{{ $trade->re_entry_allowed ? 'Yes' : 'No' }}</span>
                </div>
                @endif
                @if($trade->max_entries)
                <div class="flex justify-between">
                    <span class="text-gray-400">Max Entries</span>
                    <span class="text-gray-300">{{ $trade->max_entries }}</span>
                </div>
                @endif
            </div>

            @if($trade->skip_reason)
                <div class="border-t border-[#2a2d3a] pt-4">
                    <p class="text-xs text-gray-400 mb-1">Skip Reason</p>
                    <p class="text-sm text-[#f59e0b]">{{ $trade->skip_reason }}</p>
                </div>
            @endif

            @if($trade->reasoning)
                <div class="border-t border-[#2a2d3a] pt-4">
                    <p class="text-xs text-gray-400 mb-2">Claude's Reasoning</p>
                    <p class="text-sm text-gray-300 leading-relaxed" style="white-space: pre-wrap;">{{ $trade->reasoning }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ url('/strategies/' . $analysis->id) }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Strategy Detail</a>
</div>

<script src="https://unpkg.com/lightweight-charts@4.1.0/dist/lightweight-charts.standalone.production.js"></script>
<script>
    (function () {
        const chartData = @json($chartData);

        // --- Candlestick chart ---
        const candleContainer = document.getElementById('candlestick-chart');
        const candleChart = LightweightCharts.createChart(candleContainer, {
            layout: {
                background: { color: '#1a1d29' },
                textColor: '#9ca3af',
            },
            grid: {
                vertLines: { color: '#2a2d3a' },
                horzLines: { color: '#2a2d3a' },
            },
            crosshair: { mode: LightweightCharts.CrosshairMode.Normal },
            rightPriceScale: { borderColor: '#2a2d3a' },
            timeScale: { borderColor: '#2a2d3a', timeVisible: false },
        });

        const candleSeries = candleChart.addCandlestickSeries({
            upColor: '#22c55e',
            downColor: '#ef4444',
            borderUpColor: '#22c55e',
            borderDownColor: '#ef4444',
            wickUpColor: '#22c55e',
            wickDownColor: '#ef4444',
        });

        const ohlcData = chartData.map(function (d) {
            return { time: d.time, open: d.open, high: d.high, low: d.low, close: d.close };
        });
        candleSeries.setData(ohlcData);

        // Price lines
        @if($trade->entry_price_cents)
            candleSeries.createPriceLine({
                price: {{ $trade->entry_price_cents / 100 }},
                color: '#3b82f6',
                lineWidth: 2,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'ENTRY ${{ number_format($trade->entry_price_cents / 100, 2) }}',
            });
        @endif

        @if($trade->stop_loss_cents)
            candleSeries.createPriceLine({
                price: {{ $trade->stop_loss_cents / 100 }},
                color: '#ef4444',
                lineWidth: 2,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'STOP LOSS ${{ number_format($trade->stop_loss_cents / 100, 2) }}',
            });
        @endif

        @if($trade->take_profit_cents)
            candleSeries.createPriceLine({
                price: {{ $trade->take_profit_cents / 100 }},
                color: '#22c55e',
                lineWidth: 2,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'TAKE PROFIT ${{ number_format($trade->take_profit_cents / 100, 2) }}',
            });
        @endif

        candleChart.timeScale().fitContent();

        // --- Volume chart ---
        const volumeContainer = document.getElementById('volume-chart');
        const volumeChart = LightweightCharts.createChart(volumeContainer, {
            layout: {
                background: { color: '#1a1d29' },
                textColor: '#9ca3af',
            },
            grid: {
                vertLines: { color: '#2a2d3a' },
                horzLines: { color: '#2a2d3a' },
            },
            rightPriceScale: { borderColor: '#2a2d3a' },
            timeScale: { borderColor: '#2a2d3a', timeVisible: false },
        });

        const volumeSeries = volumeChart.addHistogramSeries({
            priceFormat: { type: 'volume' },
            priceScaleId: '',
        });

        volumeSeries.setData(chartData.map(function (d) {
            return {
                time: d.time,
                value: d.volume,
                color: d.close >= d.open ? 'rgba(34,197,94,0.5)' : 'rgba(239,68,68,0.5)',
            };
        }));

        volumeChart.timeScale().fitContent();

        // Sync visible range between charts
        candleChart.timeScale().subscribeVisibleLogicalRangeChange(function (range) {
            if (range) volumeChart.timeScale().setVisibleLogicalRange(range);
        });
        volumeChart.timeScale().subscribeVisibleLogicalRangeChange(function (range) {
            if (range) candleChart.timeScale().setVisibleLogicalRange(range);
        });

        // Sync crosshair
        candleChart.subscribeCrosshairMove(function (param) {
            if (param.time) {
                volumeChart.setCrosshairPosition(undefined, param.time, volumeSeries);
            } else {
                volumeChart.clearCrosshairPosition();
            }
        });
        volumeChart.subscribeCrosshairMove(function (param) {
            if (param.time) {
                candleChart.setCrosshairPosition(undefined, param.time, candleSeries);
            } else {
                candleChart.clearCrosshairPosition();
            }
        });

        // Resize
        function resize() {
            candleChart.applyOptions({ width: candleContainer.clientWidth, height: 600 });
            volumeChart.applyOptions({ width: volumeContainer.clientWidth, height: 150 });
        }
        resize();
        window.addEventListener('resize', resize);
    })();
</script>
@endsection
