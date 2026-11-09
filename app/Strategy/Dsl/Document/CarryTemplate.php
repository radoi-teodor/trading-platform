<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;

/** The funding_carry template's parameters (docs/strategy/configurator.md, section 3; D-48, D-56). */
final readonly class CarryTemplate
{
    public function __construct(
        public Symbol $spot,
        public Symbol $perpetual,
        public int $entryPeriods,
        public Decimal $entry,
        public int $exitPeriods,
        public Decimal $exit,
        public int $maxLeverage,
        public Decimal $marginBuffer,
        public Decimal $maxPosition,
        public Timeframe $timeframe,
    ) {}

    /** @return array<string, string> the parameters of the coded FundingCarry (App\Strategy\Baselines) */
    public function parameters(): array
    {
        return [
            'entry' => (string) $this->entry, 'entry_periods' => (string) $this->entryPeriods,
            'exit' => (string) $this->exit, 'exit_periods' => (string) $this->exitPeriods,
            'margin_buffer' => (string) $this->marginBuffer, 'max_leverage' => (string) $this->maxLeverage,
            'max_position' => (string) $this->maxPosition, 'timeframe' => $this->timeframe->value,
        ];
    }
}
