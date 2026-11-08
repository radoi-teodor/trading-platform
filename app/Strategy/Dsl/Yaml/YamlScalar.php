<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Yaml;

use App\Strategy\Dsl\Position;

/** A scalar exactly as written (without its quotes): numbers are never turned into floats. */
final readonly class YamlScalar implements YamlValue
{
    public function __construct(public string $text, public Position $at, public bool $quoted = false) {}

    public function position(): Position
    {
        return $this->at;
    }
}
