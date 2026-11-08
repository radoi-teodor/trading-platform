<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

/** One parameter of a DSL function; $default is DSL source, null when the parameter is required. */
final readonly class ParamSpec
{
    public function __construct(public string $name, public ParamKind $kind, public ?string $default = null) {}
}
