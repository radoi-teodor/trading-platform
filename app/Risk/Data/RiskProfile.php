<?php

declare(strict_types=1);

namespace App\Risk\Data;

enum RiskProfile: string
{
    /** The account policy of docs/risk-policy.md, section 1. */
    case Account = 'account';

    /** All free quote in one position, no stop, no halts: comparisons in backtests only (D-40). */
    case Benchmark = 'benchmark';
}
