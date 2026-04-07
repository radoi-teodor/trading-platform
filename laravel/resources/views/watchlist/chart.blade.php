@extends('layouts.app')

@section('title', $symbol . ' Chart')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
        <div class="flex items-center space-x-3">
            <a href="{{ url('/watchlist') }}" class="text-gray-500 hover:text-gray-300 transition-colors">&larr;</a>
            <h2 class="text-2xl font-bold text-white">{{ $symbol }}</h2>
            @if($latestPrice)
                <span class="text-xl text-gray-200">${{ number_format($latestPrice->close / 100, 2) }}</span>
                <span class="text-sm {{ $change >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                </span>
            @endif
        </div>
        @if($latestPrice)
            <p class="text-sm text-gray-500 mt-1">
                O: ${{ number_format($latestPrice->open / 100, 2) }}
                &middot; H: ${{ number_format($latestPrice->high / 100, 2) }}
                &middot; L: ${{ number_format($latestPrice->low / 100, 2) }}
                &middot; C: ${{ number_format($latestPrice->close / 100, 2) }}
                &middot; Vol: {{ number_format($latestPrice->volume) }}
            </p>
        @endif
    </div>
</div>

{{-- Candlestick Chart --}}
<div class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-4 mb-6">
    <div id="candlestick-chart" style="height: 500px;"></div>
</div>

{{-- Volume Chart --}}
<div class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-4 mb-6">
    <h3 class="text-sm font-medium text-gray-400 mb-3">Volume</h3>
    <div id="volume-chart" style="height: 150px;"></div>
</div>

{{-- Price History Table --}}
<x-card title="Price History" class="mb-6">
    <div class="overflow-x-auto max-h-96 overflow-y-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-[#0f1117] sticky top-0">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Open</th>
                    <th class="px-4 py-3 text-right">High</th>
                    <th class="px-4 py-3 text-right">Low</th>
                    <th class="px-4 py-3 text-right">Close</th>
                    <th class="px-4 py-3 text-right">Volume</th>
                    <th class="px-4 py-3 text-right">Change</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2a2d3a]">
                @php $prevClose = null; @endphp
                @foreach(array_reverse($chartData) as $day)
                    @php
                        $dayChange = $prevClose ? (($day['close'] - $prevClose) / $prevClose) * 100 : 0;
                        $prevClose = $day['close'];
                    @endphp
                    <tr class="hover:bg-[#0f1117]/50">
                        <td class="px-4 py-2 text-gray-300">{{ $day['date'] }}</td>
                        <td class="px-4 py-2 text-right text-gray-300">${{ number_format($day['open'], 2) }}</td>
                        <td class="px-4 py-2 text-right text-gray-300">${{ number_format($day['high'], 2) }}</td>
                        <td class="px-4 py-2 text-right text-gray-300">${{ number_format($day['low'], 2) }}</td>
                        <td class="px-4 py-2 text-right text-white font-medium">${{ number_format($day['close'], 2) }}</td>
                        <td class="px-4 py-2 text-right text-gray-400">{{ number_format($day['volume']) }}</td>
                        <td class="px-4 py-2 text-right {{ $dayChange >= 0 ? 'text-[#22c55e]' : 'text-[#ef4444]' }}">
                            {{ $dayChange >= 0 ? '+' : '' }}{{ number_format($dayChange, 2) }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>

{{-- TradingView Lightweight Charts (free, open source) --}}
<script src="https://unpkg.com/lightweight-charts@4.1.0/dist/lightweight-charts.standalone.production.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rawData = @json($chartData);

    if (!rawData || rawData.length === 0) {
        document.getElementById('candlestick-chart').innerHTML = '<p class="text-gray-500 text-center py-20">No price data available. Add this symbol to your watchlist to load data.</p>';
        return;
    }

    // Candlestick chart
    var chartContainer = document.getElementById('candlestick-chart');
    var chart = LightweightCharts.createChart(chartContainer, {
        layout: {
            background: { color: '#1a1d29' },
            textColor: '#9ca3af',
        },
        grid: {
            vertLines: { color: '#2a2d3a' },
            horzLines: { color: '#2a2d3a' },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
        },
        rightPriceScale: {
            borderColor: '#2a2d3a',
        },
        timeScale: {
            borderColor: '#2a2d3a',
            timeVisible: false,
        },
    });

    var candleSeries = chart.addCandlestickSeries({
        upColor: '#22c55e',
        downColor: '#ef4444',
        borderUpColor: '#22c55e',
        borderDownColor: '#ef4444',
        wickUpColor: '#22c55e',
        wickDownColor: '#ef4444',
    });

    var candles = rawData.map(function(d) {
        return {
            time: d.date,
            open: d.open,
            high: d.high,
            low: d.low,
            close: d.close,
        };
    });
    candleSeries.setData(candles);

    // Executed trade markers
    var executedTrades = @json($executedTrades ?? []);
    var markers = [];
    executedTrades.forEach(function(t) {
        markers.push({
            time: t.time,
            position: t.side === 'buy' ? 'belowBar' : 'aboveBar',
            color: t.side === 'buy' ? '#22c55e' : '#ef4444',
            shape: t.side === 'buy' ? 'arrowUp' : 'arrowDown',
            text: t.side.toUpperCase() + ' ' + t.quantity,
        });
    });
    if (markers.length > 0) {
        candleSeries.setMarkers(markers);
    }

    // Planned trade price lines (entry, SL, TP)
    var plannedTrades = @json($plannedTrades ?? []);
    plannedTrades.forEach(function(t) {
        if (t.entry_price) {
            candleSeries.createPriceLine({
                price: t.entry_price,
                color: '#3b82f6',
                lineWidth: 2,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'ENTRY ' + t.action.toUpperCase() + ' $' + t.entry_price.toFixed(2),
            });
        }
        if (t.stop_loss) {
            candleSeries.createPriceLine({
                price: t.stop_loss,
                color: '#ef4444',
                lineWidth: 1,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'SL $' + t.stop_loss.toFixed(2),
            });
        }
        if (t.take_profit) {
            candleSeries.createPriceLine({
                price: t.take_profit,
                color: '#22c55e',
                lineWidth: 1,
                lineStyle: LightweightCharts.LineStyle.Dashed,
                axisLabelVisible: true,
                title: 'TP $' + t.take_profit.toFixed(2),
            });
        }
    });

    chart.timeScale().fitContent();

    // Volume chart
    var volumeContainer = document.getElementById('volume-chart');
    var volumeChart = LightweightCharts.createChart(volumeContainer, {
        layout: {
            background: { color: '#1a1d29' },
            textColor: '#9ca3af',
        },
        grid: {
            vertLines: { color: '#2a2d3a' },
            horzLines: { color: '#2a2d3a' },
        },
        rightPriceScale: {
            borderColor: '#2a2d3a',
        },
        timeScale: {
            borderColor: '#2a2d3a',
            timeVisible: false,
        },
    });

    var volumeSeries = volumeChart.addHistogramSeries({
        priceFormat: { type: 'volume' },
        priceScaleId: '',
    });

    var volumes = rawData.map(function(d) {
        return {
            time: d.date,
            value: d.volume,
            color: d.close >= d.open ? 'rgba(34, 197, 94, 0.5)' : 'rgba(239, 68, 68, 0.5)',
        };
    });
    volumeSeries.setData(volumes);
    volumeChart.timeScale().fitContent();

    // Sync time scales
    chart.timeScale().subscribeVisibleLogicalRangeChange(function(range) {
        if (range) volumeChart.timeScale().setVisibleLogicalRange(range);
    });
    volumeChart.timeScale().subscribeVisibleLogicalRangeChange(function(range) {
        if (range) chart.timeScale().setVisibleLogicalRange(range);
    });

    // Resize on window resize
    window.addEventListener('resize', function() {
        chart.applyOptions({ width: chartContainer.clientWidth });
        volumeChart.applyOptions({ width: volumeContainer.clientWidth });
    });
});
</script>
@endsection
