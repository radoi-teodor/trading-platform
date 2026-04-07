@extends('layouts.app')

@section('title', 'Performance')

@section('content')
{{-- Performance Metrics --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card
        label="Total Return"
        :value="number_format($totalReturn ?? 0, 2) . '%'"
        :changeType="($totalReturn ?? 0) >= 0 ? 'positive' : 'negative'"
    />
    <x-stat-card
        label="Win Rate"
        :value="number_format($winRate ?? 0, 1) . '%'"
    />
    <x-stat-card
        label="Avg Win / Avg Loss"
        :value="'$' . number_format(abs($avgWin ?? 0) / 100, 2) . ' / $' . number_format(abs($avgLoss ?? 0) / 100, 2)"
    />
    <x-stat-card
        label="Max Drawdown"
        :value="number_format($maxDrawdown ?? 0, 2) . '%'"
        changeType="negative"
    />
</div>

{{-- Daily P&L Chart --}}
<x-card title="Daily P&L (Last 30 Days)" class="mb-6">
    <div style="height: 300px;">
        <canvas id="dailyPnlChart"></canvas>
    </div>
</x-card>

{{-- Equity Curve --}}
<x-card title="Equity Curve" class="mb-6">
    <div style="height: 300px;">
        <canvas id="equityCurve"></canvas>
    </div>
</x-card>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily P&L Bar Chart
    var dailyPnl = @json($dailyPnlData ?? ['labels' => [], 'values' => []]);
    if (dailyPnl.labels && dailyPnl.labels.length > 0) {
        var ctx1 = document.getElementById('dailyPnlChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: dailyPnl.labels,
                datasets: [{
                    label: 'P&L',
                    data: dailyPnl.values,
                    backgroundColor: dailyPnl.values.map(function(v) {
                        return v >= 0 ? 'rgba(34, 197, 94, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                    }),
                    borderColor: dailyPnl.values.map(function(v) {
                        return v >= 0 ? '#22c55e' : '#ef4444';
                    }),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#9ca3af', maxTicksLimit: 10 }, grid: { color: '#2a2d3a' } },
                    y: { ticks: { color: '#9ca3af', callback: function(v) { return '$' + v.toLocaleString(); } }, grid: { color: '#2a2d3a' } }
                }
            }
        });
    }

    // Equity Curve
    var equityData = @json($equityData ?? ['labels' => [], 'values' => []]);
    if (equityData.labels && equityData.labels.length > 0) {
        var ctx2 = document.getElementById('equityCurve').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: equityData.labels,
                datasets: [{
                    label: 'Portfolio Value',
                    data: equityData.values,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#9ca3af', maxTicksLimit: 10 }, grid: { color: '#2a2d3a' } },
                    y: { ticks: { color: '#9ca3af', callback: function(v) { return '$' + v.toLocaleString(); } }, grid: { color: '#2a2d3a' } }
                }
            }
        });
    }
});
</script>
@endsection
