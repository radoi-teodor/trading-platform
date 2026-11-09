<?php

declare(strict_types=1);

namespace App\Notifications\Health;

use RuntimeException;

/** The disk of a local directory, read from the operating system. */
final readonly class LocalDiskUsage implements DiskUsage
{
    public function __construct(private string $path) {}

    public function free(): int
    {
        $free = disk_free_space($this->path);

        return $free === false ? throw new RuntimeException("The free space of {$this->path} cannot be read.") : (int) $free;
    }

    public function total(): int
    {
        $total = disk_total_space($this->path);

        return $total === false ? throw new RuntimeException("The size of {$this->path} cannot be read.") : (int) $total;
    }
}
