<?php

declare(strict_types=1);

namespace App\Market\Binance;

final readonly class SystemSleeper implements Sleeper
{
    public function sleep(int $seconds): void
    {
        sleep(max(0, $seconds));
    }
}
