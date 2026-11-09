<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\E2eSeeder;

uses(RefreshDatabase::class);

it('refuses to seed its fixed operator into any database but the end-to-end one', function (): void {
    web()->seed(E2eSeeder::class);
})->throws(LogicException::class, 'The end-to-end seeder runs only on a database whose name ends in _e2e.');
