<?php

declare(strict_types=1);

namespace App\Portfolio\Data;

use App\Portfolio\Perpetual;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/** A strategy's isolated position on a perpetual (docs/engine.md, section 8). */
final readonly class PerpPosition
{
    public function __construct(
        public string $strategy,
        public Symbol $symbol,
        public Side $side,
        public int $leverage,
        public CarbonImmutable $openedAt,
        public Decimal $quantity,
        public Decimal $entryPrice,
        public Decimal $margin,
        public Decimal $enteredQuantity,
        public Decimal $exitedQuantity,
        public Decimal $exitNotional,
        public Decimal $realized,
        public Decimal $fees,
        public Decimal $funding,
        public Decimal $slippage,
        public ?Price $initialStop,
        public ?Price $highest,
        public ?Price $lowest,
        public ?ExitReason $exitReason,
    ) {}

    public static function open(Fill $fill, int $leverage): self
    {
        $zero = Decimal::zero();

        return (new self(
            $fill->strategy, $fill->symbol, $fill->side, $leverage, $fill->time, $zero, $zero, $zero, $zero, $zero,
            $zero, $zero, $zero, $zero, $zero, $fill->initialStop, null, null, null,
        ))->withIncrease($fill);
    }

    public function withIncrease(Fill $fill): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->side, $this->leverage, $this->openedAt,
            $this->quantity->plus($fill->quantity),
            $this->quantity->isZero() ? $fill->price->value : Perpetual::averageEntry($this->entryPrice, $this->quantity, $fill->price->value, $fill->quantity),
            $this->margin->plus(Perpetual::initialMargin($fill->quantity, $fill->price->value, $this->leverage)),
            $this->enteredQuantity->plus($fill->quantity),
            $this->exitedQuantity, $this->exitNotional, $this->realized,
            $this->fees->plus($fill->feeValue), $this->funding, $this->slippage->plus($fill->slippage),
            $this->initialStop ?? $fill->initialStop, $this->highest, $this->lowest, $this->exitReason,
        );
    }

    /** @return array{self, Decimal, Decimal} the reduced position, the realized profit and the released margin */
    public function withReduction(Fill $fill): array
    {
        $realized = Perpetual::pnl($this->side, $fill->quantity, $this->entryPrice, $fill->price->value);
        $released = Perpetual::released($this->margin, $fill->quantity, $this->quantity);

        return [new self(
            $this->strategy, $this->symbol, $this->side, $this->leverage, $this->openedAt,
            $this->quantity->minus($fill->quantity), $this->entryPrice, $this->margin->minus($released),
            $this->enteredQuantity, $this->exitedQuantity->plus($fill->quantity),
            $this->exitNotional->plus($fill->notional()), $this->realized->plus($realized),
            $this->fees->plus($fill->feeValue), $this->funding, $this->slippage->plus($fill->slippage),
            $this->initialStop, $this->highest, $this->lowest, $fill->exitReason ?? $this->exitReason,
        ), $realized, $released];
    }

    /** @param Decimal $marginChange zero, or the negative part of the funding the wallet could not pay */
    public function withFunding(Decimal $amount, Decimal $marginChange): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->side, $this->leverage, $this->openedAt, $this->quantity,
            $this->entryPrice, $this->margin->plus($marginChange), $this->enteredQuantity, $this->exitedQuantity, $this->exitNotional,
            $this->realized, $this->fees, $this->funding->plus($amount), $this->slippage, $this->initialStop,
            $this->highest, $this->lowest, $this->exitReason,
        );
    }

    public function withExtremes(Price $high, Price $low): self
    {
        return new self(
            $this->strategy, $this->symbol, $this->side, $this->leverage, $this->openedAt, $this->quantity,
            $this->entryPrice, $this->margin, $this->enteredQuantity, $this->exitedQuantity, $this->exitNotional,
            $this->realized, $this->fees, $this->funding, $this->slippage, $this->initialStop,
            $this->highest === null || $high->value->isGreaterThan($this->highest->value) ? $high : $this->highest,
            $this->lowest === null || $low->value->isLessThan($this->lowest->value) ? $low : $this->lowest,
            $this->exitReason,
        );
    }

    /** The whole margin lost at $price: the position is gone. */
    public function liquidated(Price $price): self
    {
        $zero = Decimal::zero();

        return new self(
            $this->strategy, $this->symbol, $this->side, $this->leverage, $this->openedAt, $zero, $this->entryPrice,
            $zero, $this->enteredQuantity, $this->exitedQuantity->plus($this->quantity),
            $this->exitNotional->plus($this->quantity->times($price->value)), $this->realized->minus($this->margin),
            $this->fees, $this->funding, $this->slippage, $this->initialStop, $this->highest, $this->lowest,
            ExitReason::Liquidation,
        );
    }

    public function unrealized(Decimal $mark): Decimal
    {
        return Perpetual::pnl($this->side, $this->quantity, $this->entryPrice, $mark);
    }

    public function close(CarbonImmutable $at): RoundTrip
    {
        $average = $this->entryPrice;
        $net = $this->realized->minus($this->fees)->plus($this->funding);
        $perUnitRisk = $this->initialStop === null ? null : Perpetual::pnl($this->side, Decimal::of(1), $this->initialStop->value, $average);
        $risk = $perUnitRisk !== null && $perUnitRisk->isPositive() ? $perUnitRisk->times($this->enteredQuantity) : null;
        $adverse = $this->side === Side::Buy
            ? ($this->lowest === null ? null : $average->minus($this->lowest->value))
            : ($this->highest?->value->minus($average));
        $favourable = $this->side === Side::Buy
            ? $this->highest?->value->minus($average)
            : ($this->lowest === null ? null : $average->minus($this->lowest->value));

        return new RoundTrip(
            strategy: $this->strategy,
            symbol: $this->symbol,
            openedAt: $this->openedAt,
            closedAt: $at,
            quantity: $this->enteredQuantity,
            entryPrice: $average,
            exitPrice: $this->exitedQuantity->isZero() ? null : $this->exitNotional->dividedBy($this->exitedQuantity, Perpetual::SCALE),
            grossProfit: $this->realized,
            fees: $this->fees,
            slippage: $this->slippage,
            netProfit: $net,
            initialRisk: $risk,
            rMultiple: $risk === null ? null : $net->dividedBy($risk, Perpetual::SCALE),
            maximumAdverseExcursion: self::excursion($adverse, $this->enteredQuantity),
            maximumFavourableExcursion: self::excursion($favourable, $this->enteredQuantity),
            exitReason: $this->exitReason,
            dust: Decimal::zero(),
            side: $this->side,
            funding: $this->funding,
        );
    }

    private static function excursion(?Decimal $perUnit, Decimal $quantity): Decimal
    {
        return $perUnit !== null && $perUnit->isPositive() ? $perUnit->times($quantity) : Decimal::zero();
    }
}
