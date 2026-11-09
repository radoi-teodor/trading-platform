<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Yaml;

use App\Strategy\Dsl\Position;

/** A mapping in document order, with where each key was written. */
final readonly class YamlMap implements YamlValue
{
    /**
     * @param  array<string, YamlScalar|YamlList|YamlMap>  $entries
     * @param  array<string, Position>  $keys
     */
    public function __construct(public array $entries, public array $keys, public Position $at) {}

    public function position(): Position
    {
        return $this->at;
    }

    public function keyPosition(string $key): ?Position
    {
        return $this->keys[$key] ?? null;
    }
}
