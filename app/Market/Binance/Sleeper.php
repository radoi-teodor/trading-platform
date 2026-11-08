<?php

declare(strict_types=1);

namespace App\Market\Binance;

interface Sleeper
{
    public function sleep(int $seconds): void;
}
