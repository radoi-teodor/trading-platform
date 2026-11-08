<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Yaml;

use App\Strategy\Dsl\Position;

/** A value of a strategy document: a scalar kept as its exact text, a list or a map. */
interface YamlValue
{
    public function position(): Position;
}
