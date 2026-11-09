<?php

declare(strict_types=1);

namespace App\Market\Data;

use Carbon\CarbonImmutable;

/** The 1-minute candle still forming, as the stream last received it (D-87). For display only. */
final readonly class FormingCandle
{
    public function __construct(public Candle $candle, public CarbonImmutable $receivedAt) {}
}
