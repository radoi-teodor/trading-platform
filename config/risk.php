<?php

declare(strict_types=1);

// Account risk limits, as fractions of equity (docs/risk-policy.md, section 1). These are the defaults; raising one
// is a code change with a D-number and never goes above the ceilings in App\Risk\RiskPolicy (D-19).
// tests/Feature/Risk/RiskConfigTest.php keeps this file, the ceilings and the policy document in step.

return [
    'account' => [
        'risk_per_trade' => '0.005',
        'total_open_risk' => '0.03',
        'notional_per_instrument' => '0.25',
        'max_open_positions' => 6,
        'daily_loss' => '0.02',
        'weekly_loss' => '0.05',
        'drawdown_halt' => '0.10',
        'drawdown_flatten' => '0.15',
        'gross_exposure' => '1.50',
        'net_exposure' => '1.00',
        // Section 2, perpetual futures.
        'perpetual_leverage' => 2,
        'carry_extra_margin' => '0.50',
        // Not in the policy document: the share of cash kept free for slippage and gaps when sizing an entry.
        'cash_buffer' => '0.01',
    ],
];
