<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Contracts\InstrumentDirectory;
use App\Support\Symbol;

/** A fixed list of tradable instruments, for tests. */
final readonly class KnownInstruments implements InstrumentDirectory
{
    /** @param list<string> $symbols */
    public function __construct(private array $symbols) {}

    public function isTradable(Symbol $symbol): bool
    {
        return in_array((string) $symbol, $this->symbols, true);
    }
}
