<?php

declare(strict_types=1);

namespace App\Market\Archive;

use Carbon\CarbonImmutable;

/** One Binance archive file's period: a calendar month or a day, in UTC. */
final readonly class ArchivePeriod
{
    private function __construct(public bool $monthly, public CarbonImmutable $start) {}

    public static function monthly(CarbonImmutable $moment): self
    {
        return new self(true, $moment->utc()->startOfMonth());
    }

    public static function daily(CarbonImmutable $moment): self
    {
        return new self(false, $moment->utc()->startOfDay());
    }

    /** The first moment after the period. */
    public function end(): CarbonImmutable
    {
        return $this->monthly ? $this->start->addMonth() : $this->start->addDay();
    }

    public function folder(): string
    {
        return $this->monthly ? 'monthly' : 'daily';
    }

    public function label(): string
    {
        return $this->start->format($this->monthly ? 'Y-m' : 'Y-m-d');
    }
}
