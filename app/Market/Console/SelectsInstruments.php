<?php

declare(strict_types=1);

namespace App\Market\Console;

use App\Market\Data\MarketType;
use App\Market\Instruments\Universe;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/** The symbol or --universe argument and the --from / --to options shared by the market commands. */
trait SelectsInstruments
{
    /** @return list<Symbol> */
    private function selectedSymbols(Universe $universe): array
    {
        if ($this->option('universe')) {
            return [...$universe->symbols(MarketType::Spot), ...$universe->symbols(MarketType::Perp)];
        }

        $symbol = $this->argument('symbol');

        if (! is_string($symbol)) {
            throw new InvalidArgumentException('Give a symbol or --universe.');
        }

        return [Symbol::parse($symbol)];
    }

    /** YYYY-MM, YYYY-MM-DD or YYYY-MM-DD HH:MM in UTC; a bare month or day as --to means its last minute. */
    private function momentOption(string $name, ?CarbonImmutable $default = null): CarbonImmutable
    {
        $value = $this->option($name);

        if ($value === null && $default !== null) {
            return $default;
        }

        if (! is_string($value) || preg_match('/^\d{4}-\d{2}(-\d{2}( \d{2}:\d{2})?)?$/', $value) !== 1) {
            throw new InvalidArgumentException("--{$name} must be YYYY-MM, YYYY-MM-DD or YYYY-MM-DD HH:MM.");
        }

        $moment = CarbonImmutable::parse(strlen($value) === 7 ? "{$value}-01" : $value, 'UTC');

        return match (true) {
            $name === 'to' && strlen($value) === 7 => $moment->endOfMonth()->startOfMinute(),
            $name === 'to' && strlen($value) === 10 => $moment->endOfDay()->startOfMinute(),
            default => $moment,
        };
    }
}
