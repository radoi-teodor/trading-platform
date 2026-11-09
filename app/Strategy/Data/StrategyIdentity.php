<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use InvalidArgumentException;

/**
 * Name, version and parameters of a strategy. Its key, eight hex characters of the SHA-256 of all three, names the
 * strategy in client order ids and in the ledger, so two parameter sets of one strategy never share positions.
 */
final readonly class StrategyIdentity
{
    /** @param array<string, string> $parameters */
    public function __construct(public string $name, public int $version, public array $parameters)
    {
        if (preg_match('/^[a-z0-9-]{1,40}$/', $name) !== 1) {
            throw new InvalidArgumentException("A strategy name is lower-case letters, digits and dashes, got '{$name}'.");
        }
    }

    public function key(): string
    {
        return substr(hash('sha256', $this->canonical()), 0, 8);
    }

    public function label(): string
    {
        return "{$this->name}@v{$this->version}";
    }

    public function canonical(): string
    {
        $parameters = $this->parameters;
        ksort($parameters, SORT_STRING);
        $pairs = array_map(fn (string $key, string $value): string => "{$key}={$value}", array_keys($parameters), $parameters);

        return "{$this->name}|{$this->version}|".implode(';', $pairs);
    }
}
