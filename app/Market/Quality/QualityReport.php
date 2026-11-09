<?php

declare(strict_types=1);

namespace App\Market\Quality;

use Carbon\CarbonImmutable;

final readonly class QualityReport
{
    /**
     * @param  list<array{from: CarbonImmutable, to: CarbonImmutable}>  $gaps
     * @param  list<array{from: CarbonImmutable, to: CarbonImmutable}>  $zeroVolumeRuns
     * @param  list<CarbonImmutable>  $outliers
     */
    public function __construct(
        public int $expectedMinutes,
        public int $storedMinutes,
        public int $missingMinutes,
        public array $gaps,
        public int $ohlcViolations,
        public array $zeroVolumeRuns,
        public array $outliers,
    ) {}

    public function hasErrors(): bool
    {
        return $this->missingMinutes > 0 || $this->ohlcViolations > 0;
    }

    public function hasWarnings(): bool
    {
        return $this->zeroVolumeRuns !== [] || $this->outliers !== [];
    }
}
