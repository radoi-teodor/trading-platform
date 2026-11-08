<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Strategy\Dsl\Types\Kind;
use App\Support\Number\Decimal;

/**
 * A declared parameter (D-51): a number or a percent (kept as a fraction), its default, and the range and step a
 * search may move it in. $whole when every value it can take is a whole number from 1, so it can be a period.
 */
final readonly class ParameterSpec
{
    public function __construct(
        public string $name,
        public Kind $kind,
        public Decimal $default,
        public ?Decimal $min,
        public ?Decimal $max,
        public ?Decimal $step,
        public bool $whole,
    ) {}
}
