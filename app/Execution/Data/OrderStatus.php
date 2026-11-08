<?php

declare(strict_types=1);

namespace App\Execution\Data;

enum OrderStatus: string
{
    case New = 'new';
    case PartiallyFilled = 'partially_filled';
    case Filled = 'filled';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function isWorking(): bool
    {
        return $this === self::New || $this === self::PartiallyFilled;
    }
}
