<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Binance\Sleeper;

final class RecordingSleeper implements Sleeper
{
    /** @var list<int> */
    public array $slept = [];

    public function sleep(int $seconds): void
    {
        $this->slept[] = $seconds;
    }
}
