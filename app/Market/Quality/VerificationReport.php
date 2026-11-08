<?php

declare(strict_types=1);

namespace App\Market\Quality;

final readonly class VerificationReport
{
    /**
     * @param  list<string>  $sampledTimes  ISO 8601 open times that were compared
     * @param  list<string>  $mismatches  one line per candle that differs or is missing at the exchange
     */
    public function __construct(public int $sampled, public array $sampledTimes, public array $mismatches) {}
}
