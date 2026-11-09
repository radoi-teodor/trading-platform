<?php

declare(strict_types=1);

use function Pest\Laravel\get;
use function Pest\Laravel\post;

// The platform has one operator and no public pages until the UI phases (D-5, docs/brand.md).

it('answers the health check', function (): void {
    get('/up')->assertOk();
});

it('has no public page: every screen sends a visitor to the login', function (): void {
    get('/')->assertRedirect('/login');
    get('/chart')->assertRedirect('/login');
});

it('has no registration route', function (): void {
    get('/register')->assertNotFound();
    post('/register')->assertNotFound();
});
