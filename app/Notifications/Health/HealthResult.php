<?php

declare(strict_types=1);

namespace App\Notifications\Health;

/** One check of the health of the machine: its name and, when it fails, why. */
final readonly class HealthResult
{
    public function __construct(public string $name, public ?string $failure = null) {}

    public function ok(): bool
    {
        return $this->failure === null;
    }
}
