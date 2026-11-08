<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->beforeEach(fn () => Http::preventStrayRequests())
    ->in('Feature', 'Golden');

/** A recorded Binance response from tests/Fixtures/binance (provenance in its README). */
function binanceFixture(string $name): string
{
    $contents = file_get_contents(__DIR__.'/Fixtures/binance/'.$name);

    if ($contents === false) {
        throw new RuntimeException("Missing fixture {$name}.");
    }

    return $contents;
}
