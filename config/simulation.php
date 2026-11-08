<?php

declare(strict_types=1);
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\MovingAverageTrend;

// Backtests (docs/simulation.md). No secrets. Changing a cost or fee assumption changes every report; a new
// calibration of the cost model is a new version (D-37).

return [
    // The quote asset and amount each backtest account starts with.
    'quote' => 'USDT',
    'initial_equity' => '10000',

    // Regular (VIP 0) spot rates without the BNB discount, until an account is connected (D-28).
    'fees' => ['maker' => '0.001', 'taker' => '0.001'],

    // Cost model version 1 (D-37): slippage = half spread + impact × participation; limits fill at most this
    // share of a minute's volume.
    'cost_model' => ['version' => 1, 'half_spread' => '0.0002', 'impact' => '0.1', 'limit_volume_share' => '0.1'],

    // Compressed reports, named by their SHA-256 (D-42).
    'artifacts' => storage_path('app/simulations'),

    // Strategies backtest:run can build, by name: a BuildableStrategy class and the risk profile it runs under.
    // Strategies without a stop can only run with the benchmark profile (D-40).
    'strategies' => [
        'buy-and-hold' => ['class' => BuyAndHold::class, 'profile' => 'benchmark'],
        'ma-trend' => ['class' => MovingAverageTrend::class, 'profile' => 'benchmark'],
        'donchian-breakout' => ['class' => DonchianBreakout::class, 'profile' => 'account'],
    ],

    // Comparisons run in the same pass as every strategy (docs/simulation.md, section 11), by label.
    'benchmarks' => [
        'buy-and-hold' => ['strategy' => 'buy-and-hold', 'parameters' => []],
        'ma-trend-50-200' => ['strategy' => 'ma-trend', 'parameters' => ['fast' => '50', 'slow' => '200', 'timeframe' => '1d']],
    ],
];
