<?php

declare(strict_types=1);

// The platform has one operator and no public pages until the UI phases (D-5, docs/brand.md).

it('answers the health check', function (): void {
    $this->get('/up')->assertOk();
});

it('has no public home page', function (): void {
    $this->get('/')->assertNotFound();
});

it('has no registration route', function (): void {
    $this->get('/register')->assertNotFound();
    $this->post('/register')->assertNotFound();
});
