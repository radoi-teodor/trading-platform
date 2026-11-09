<?php

declare(strict_types=1);

namespace App\Simulation\Metrics;

use App\Support\Number\Decimal;

/** The metrics of one account over a backtest (docs/simulation.md, section 6). Fractions, not percentages. */
final readonly class PerformanceMetrics
{
    /** @param list<array{month: string, return: Decimal}> $monthlyReturns */
    public function __construct(
        public Decimal $initialEquity,
        public Decimal $finalEquity,
        public Decimal $netProfit,
        public Decimal $return,
        public ?Decimal $cagr,
        public Decimal $maxDrawdown,
        public int $maxDrawdownSeconds,
        public ?Decimal $sharpe,
        public ?Decimal $sortino,
        public ?Decimal $calmar,
        public ?Decimal $timeInMarket,
        public Decimal $turnover,
        public array $monthlyReturns,
        public ?Decimal $positiveMonthsShare,
        public TradeStatistics $trades,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'initial_equity' => $this->initialEquity,
            'final_equity' => $this->finalEquity,
            'net_profit' => $this->netProfit,
            'return' => $this->return,
            'cagr' => $this->cagr,
            'max_drawdown' => $this->maxDrawdown,
            'max_drawdown_seconds' => $this->maxDrawdownSeconds,
            'sharpe' => $this->sharpe,
            'sortino' => $this->sortino,
            'calmar' => $this->calmar,
            'time_in_market' => $this->timeInMarket,
            'turnover' => $this->turnover,
            'monthly_returns' => $this->monthlyReturns,
            'positive_months_share' => $this->positiveMonthsShare,
            'trades' => $this->trades->toArray(),
        ];
    }
}
