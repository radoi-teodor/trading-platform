<?php

declare(strict_types=1);

namespace App\Execution\Data;

/** What the operator, a breaker or the CLI can ask an engine to do (docs/risk-policy.md, section 5). */
enum CommandKind: string
{
    case Halt = 'halt';
    case Resume = 'resume';
    case Flatten = 'flatten';
    case Pause = 'pause';
    case ResumeStrategy = 'resume_strategy';
}
