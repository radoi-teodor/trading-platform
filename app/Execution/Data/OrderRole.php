<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Portfolio\Data\ExitReason;

/** What an order is for; it names the order in its client id and becomes the exit reason of the fill. */
enum OrderRole: string
{
    case Entry = 'e';
    case Exit = 'x';
    case Stop = 's';
    case Target = 't';
    case Flatten = 'f';

    public function exitReason(): ?ExitReason
    {
        return match ($this) {
            self::Entry => null,
            self::Exit => ExitReason::Signal,
            self::Stop => ExitReason::Stop,
            self::Target => ExitReason::Target,
            self::Flatten => ExitReason::Flatten,
        };
    }

    public function isProtective(): bool
    {
        return $this === self::Stop || $this === self::Target;
    }
}
