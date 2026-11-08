<?php

declare(strict_types=1);

namespace App\Risk\Data;

/** Why an intent was not acted on (docs/engine.md, section 5). */
enum RejectionReason: string
{
    case Halted = 'halted';
    case PositionOpen = 'position_open';
    case DuplicateIntent = 'duplicate_intent';
    case NoPosition = 'no_position';
    case NoStop = 'no_stop';
    case InvalidStop = 'invalid_stop';
    case InvalidTarget = 'invalid_target';
    case MaxPositions = 'max_positions';
    case OpenRisk = 'open_risk';
    case BelowMinimum = 'below_minimum';
    case StopWidening = 'stop_widening';
    case Warmup = 'warmup';
    case VenueRejected = 'venue_rejected';
}
