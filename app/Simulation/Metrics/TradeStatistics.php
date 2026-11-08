<?php

declare(strict_types=1);

namespace App\Simulation\Metrics;

use App\Support\Number\Decimal;

/** Closed round trips summed up (docs/simulation.md, section 6); profits and costs in quote. */
final readonly class TradeStatistics
{
    /**
     * @param  array{Decimal, Decimal}|null  $expectancyCi95
     * @param  array{Decimal, Decimal}|null  $expectancyRCi95
     */
    public function __construct(
        public int $count,
        public int $wins,
        public ?Decimal $winRate,
        public ?Decimal $averageWin,
        public ?Decimal $averageLoss,
        public ?Decimal $profitFactor,
        public ?Decimal $expectancy,
        public ?array $expectancyCi95,
        public ?Decimal $expectancyR,
        public ?array $expectancyRCi95,
        public Decimal $grossProfit,
        public Decimal $fees,
        public Decimal $slippage,
        public ?Decimal $costsShareOfGrossProfit,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'wins' => $this->wins,
            'win_rate' => $this->winRate,
            'average_win' => $this->averageWin,
            'average_loss' => $this->averageLoss,
            'profit_factor' => $this->profitFactor,
            'expectancy' => $this->expectancy,
            'expectancy_ci95' => $this->expectancyCi95,
            'expectancy_r' => $this->expectancyR,
            'expectancy_r_ci95' => $this->expectancyRCi95,
            'gross_profit' => $this->grossProfit,
            'fees' => $this->fees,
            'slippage' => $this->slippage,
            'costs_share_of_gross_profit' => $this->costsShareOfGrossProfit,
        ];
    }
}
