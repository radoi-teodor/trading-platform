<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Risk\RiskPolicy;

/** The account policy with the defaults of docs/risk-policy.md, for tests that change one limit. */
final class Policies
{
    public const array DEFAULTS = [
        'risk_per_trade' => '0.005', 'total_open_risk' => '0.03', 'notional_per_instrument' => '0.25',
        'max_open_positions' => 6, 'daily_loss' => '0.02', 'weekly_loss' => '0.05', 'drawdown_halt' => '0.10',
        'drawdown_flatten' => '0.15', 'cash_buffer' => '0.01', 'gross_exposure' => '1.50', 'net_exposure' => '1.00',
        'perpetual_leverage' => 2, 'carry_extra_margin' => '0.50',
    ];

    /** @param array<string, string|int> $overrides */
    public static function account(array $overrides = []): RiskPolicy
    {
        return RiskPolicy::account([...self::DEFAULTS, ...$overrides]);
    }
}
