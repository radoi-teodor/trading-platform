<?php

declare(strict_types=1);

namespace App\Execution\Data;

/** A command for the engine, applied at the next event after the venue is reconciled (docs/engine.md, section 9). */
final readonly class EngineCommand
{
    /** @param ?string $strategy the strategy's label for pause and resume_strategy */
    public function __construct(public CommandKind $kind, public ?string $strategy, public string $by) {}

    public function describe(): string
    {
        return $this->kind->value.($this->strategy === null ? '' : " {$this->strategy}")." by {$this->by}";
    }
}
