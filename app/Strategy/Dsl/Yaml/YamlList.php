<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Yaml;

use App\Strategy\Dsl\Position;

final readonly class YamlList implements YamlValue
{
    /** @param list<YamlScalar|YamlList|YamlMap> $items */
    public function __construct(public array $items, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }
}
