<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Types;

/** The type of an expression: its kind and, for numbers, what it measures. */
final readonly class Type
{
    public function __construct(public Kind $kind, public Unit $unit = Unit::Any) {}

    public static function series(Unit $unit): self
    {
        return new self(Kind::Series, $unit);
    }

    public static function of(Kind $kind): self
    {
        return new self($kind);
    }

    public function isNumeric(): bool
    {
        return $this->kind === Kind::Series || $this->kind === Kind::Number || $this->kind === Kind::Percent;
    }

    /** How the type reads in an error message: "a price series", "a percent". */
    public function describe(): string
    {
        return match ($this->kind) {
            Kind::Series => $this->unit === Unit::Any ? 'a series' : "a {$this->unit->value} series",
            Kind::Number => 'a number',
            Kind::Percent => 'a percent',
            Kind::Duration => 'a duration',
            Kind::RMultiple => 'an R multiple',
            Kind::Text => 'a text',
            Kind::Condition => 'a condition',
            Kind::Stop => 'a stop level',
            Kind::Trail => 'a trailing stop',
            Kind::Order => 'an order type',
            Kind::None => 'none',
        };
    }
}
