<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Support\Number\Decimal;
use InvalidArgumentException;

/**
 * How the simulated venue prices what it cannot see (D-37): slippage of market and stop fills as half an estimated
 * spread plus a linear impact on the share of the minute's volume an order takes, and the share of a minute's
 * volume a resting limit may fill. A new calibration is a new version.
 */
final readonly class CostModel
{
    public function __construct(
        public int $version,
        public Decimal $halfSpread,
        public Decimal $impact,
        public Decimal $limitVolumeShare,
    ) {
        if ($halfSpread->isNegative() || $impact->isNegative() || $limitVolumeShare->isNegative()) {
            throw new InvalidArgumentException('Cost model parameters cannot be negative.');
        }
    }

    /** Slippage as a fraction of the reference price. Participation is capped at 1, which a minute without volume reaches. */
    public function slippage(Decimal $quantity, Decimal $minuteVolume): Decimal
    {
        $one = Decimal::of(1);
        $participation = $minuteVolume->isPositive() ? $quantity->dividedBy($minuteVolume, 18) : $one;

        if ($participation->isGreaterThan($one)) {
            $participation = $one;
        }

        return $this->halfSpread->plus($this->impact->times($participation));
    }

    public function limitCapacity(Decimal $minuteVolume): Decimal
    {
        return $minuteVolume->times($this->limitVolumeShare);
    }
}
