<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * A strategy's holding of one instrument, from its first entry fill on. $quantity is what it holds now, net of
 * fees charged in the base asset; the bought and sold totals keep the averages and the round trip exact.
 */
final readonly class Position
{
    public const int SCALE = 18;

    public function __construct(
        public string $strategy,
        public Symbol $symbol,
        public CarbonImmutable $openedAt,
        public Decimal $quantity,
        public Decimal $boughtQuantity,
        public Decimal $boughtNotional,
        public Decimal $soldQuantity,
        public Decimal $soldNotional,
        public Decimal $spent,
        public Decimal $received,
        public Decimal $fees,
        public Decimal $slippage,
        public ?Price $initialStop,
        public ?Price $highest,
        public ?Price $lowest,
        public ?ExitReason $exitReason,
        public ?CarbonImmutable $lastSellAt = null,
    ) {}

    public static function open(Fill $entry): self
    {
        $zero = Decimal::zero();

        return new self(
            $entry->strategy, $entry->symbol, $entry->time, $zero, $zero, $zero, $zero, $zero, $zero, $zero, $zero,
            $zero, $entry->initialStop, null, null, null,
        );
    }

    /** Average price paid per unit bought, before fees. */
    public function entryPrice(): Decimal
    {
        return $this->boughtNotional->dividedBy($this->boughtQuantity, self::SCALE);
    }

    public function withBuy(Fill $fill, Decimal $netQuantity, Decimal $spent): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->openedAt,
            $this->quantity->plus($netQuantity),
            $this->boughtQuantity->plus($fill->quantity),
            $this->boughtNotional->plus($fill->notional()),
            $this->soldQuantity, $this->soldNotional,
            $this->spent->plus($spent),
            $this->received,
            $this->fees->plus($fill->feeValue),
            $this->slippage->plus($fill->slippage),
            $this->initialStop ?? $fill->initialStop,
            $this->highest, $this->lowest, $this->exitReason, $this->lastSellAt,
        );
    }

    public function withSell(Fill $fill, Decimal $quantityOut, Decimal $received): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->openedAt,
            $this->quantity->minus($quantityOut),
            $this->boughtQuantity, $this->boughtNotional,
            $this->soldQuantity->plus($fill->quantity),
            $this->soldNotional->plus($fill->notional()),
            $this->spent,
            $this->received->plus($received),
            $this->fees->plus($fill->feeValue),
            $this->slippage->plus($fill->slippage),
            $this->initialStop, $this->highest, $this->lowest,
            $fill->exitReason ?? $this->exitReason,
            $fill->time,
        );
    }

    public function withExtremes(Price $high, Price $low): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->openedAt, $this->quantity, $this->boughtQuantity,
            $this->boughtNotional, $this->soldQuantity, $this->soldNotional, $this->spent, $this->received,
            $this->fees, $this->slippage, $this->initialStop,
            $this->highest === null || $high->value->isGreaterThan($this->highest->value) ? $high : $this->highest,
            $this->lowest === null || $low->value->isLessThan($this->lowest->value) ? $low : $this->lowest,
            $this->exitReason,
            $this->lastSellAt,
        );
    }

    public function close(CarbonImmutable $at): RoundTrip
    {
        $entry = $this->entryPrice();
        $net = $this->received->minus($this->spent);
        $risk = $this->initialStop === null ? null : $this->boughtQuantity->times($entry->minus($this->initialStop->value));
        $risk = $risk !== null && $risk->isPositive() ? $risk : null;

        return new RoundTrip(
            strategy: $this->strategy,
            symbol: $this->symbol,
            openedAt: $this->openedAt,
            closedAt: $at,
            quantity: $this->boughtQuantity,
            entryPrice: $entry,
            exitPrice: $this->soldQuantity->isZero() ? null : $this->soldNotional->dividedBy($this->soldQuantity, self::SCALE),
            grossProfit: $net->plus($this->fees),
            fees: $this->fees,
            slippage: $this->slippage,
            netProfit: $net,
            initialRisk: $risk,
            rMultiple: $risk === null ? null : $net->dividedBy($risk, self::SCALE),
            maximumAdverseExcursion: $this->excursion($this->lowest === null ? null : $entry->minus($this->lowest->value)),
            maximumFavourableExcursion: $this->excursion($this->highest?->value->minus($entry)),
            exitReason: $this->exitReason,
            dust: $this->quantity,
        );
    }

    private function excursion(?Decimal $perUnit): Decimal
    {
        return $perUnit !== null && $perUnit->isPositive() ? $perUnit->times($this->boughtQuantity) : Decimal::zero();
    }
}
