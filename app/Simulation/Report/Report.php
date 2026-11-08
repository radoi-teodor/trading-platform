<?php

declare(strict_types=1);

namespace App\Simulation\Report;

use App\Simulation\Metrics\PerformanceMetrics;

/** A backtest report: its content, identified by the SHA-256 of its canonical JSON (D-42), and the main metrics. */
final readonly class Report
{
    /** @param array<string, mixed> $content */
    public function __construct(public array $content, public PerformanceMetrics $metrics) {}

    public function json(): string
    {
        return CanonicalJson::encode($this->content);
    }

    public function hash(): string
    {
        return hash('sha256', $this->json());
    }
}
