<?php

declare(strict_types=1);

namespace App\Portfolio;

use App\Support\Number\Decimal;
use App\Support\Side;

/**
 * The isolated-margin arithmetic of docs/engine.md, section 8, in one place: the ledger and the simulated venue use
 * these same functions, so that their books can be compared to the last digit. Divisions truncate at 18 decimals.
 */
final class Perpetual
{
    public const int SCALE = 18;

    public static function initialMargin(Decimal $quantity, Decimal $price, int $leverage): Decimal
    {
        return $quantity->times($price)->dividedBy(Decimal::of($leverage), self::SCALE);
    }

    /** The share of a position's margin that reducing it by $quantity of $held gives back. */
    public static function released(Decimal $margin, Decimal $quantity, Decimal $held): Decimal
    {
        return $quantity->equals($held) ? $margin : $margin->times($quantity)->dividedBy($held, self::SCALE);
    }

    public static function averageEntry(Decimal $entry, Decimal $held, Decimal $price, Decimal $added): Decimal
    {
        return $entry->times($held)->plus($price->times($added))->dividedBy($held->plus($added), self::SCALE);
    }

    /** side × quantity × (price - entry): positive when the price moved the position's way. */
    public static function pnl(Side $side, Decimal $quantity, Decimal $entry, Decimal $price): Decimal
    {
        $move = $price->minus($entry)->times($quantity);

        return $side === Side::Buy ? $move : $move->negate();
    }

    /** Where maintenance margin m equals what is left of the isolated margin (fees ignored). */
    public static function liquidationPrice(Side $side, Decimal $quantity, Decimal $entry, Decimal $margin, Decimal $maintenanceRate): Decimal
    {
        $one = Decimal::of(1);

        return $side === Side::Buy
            ? $quantity->times($entry)->minus($margin)->dividedBy($quantity->times($one->minus($maintenanceRate)), self::SCALE)
            : $quantity->times($entry)->plus($margin)->dividedBy($quantity->times($one->plus($maintenanceRate)), self::SCALE);
    }

    /** Funding a position pays (negative) or receives (positive): -side × quantity × mark × rate. */
    public static function funding(Side $side, Decimal $quantity, Decimal $mark, Decimal $rate): Decimal
    {
        $payment = $quantity->times($mark)->times($rate);

        return $side === Side::Buy ? $payment->negate() : $payment;
    }
}
