<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// No default account and no default password: the first user is created on purpose (docs/security.md, section 3).

it('creates no user when the database is seeded', function (): void {
    $this->seed();

    expect(User::query()->count())->toBe(0);
});
