<?php

declare(strict_types=1);

use App\Support\Clock\Clock;
use App\Support\Clock\WallClock;

it('binds the wall clock in the container', function (): void {
    expect(app(Clock::class))->toBeInstanceOf(WallClock::class);
});
