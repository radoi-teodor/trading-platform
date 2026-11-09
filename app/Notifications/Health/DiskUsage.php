<?php

declare(strict_types=1);

namespace App\Notifications\Health;

/** Free and total bytes of the disk that holds the database and storage (D-78). */
interface DiskUsage
{
    public function free(): int;

    public function total(): int;
}
