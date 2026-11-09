<?php

declare(strict_types=1);

use App\Agents\Models\McpToken;
use App\Agents\Tokens;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 10:00', 'UTC'))));

it('creates a token whose secret is returned once and stored only as its hash, and revokes it', function (): void {
    $tokens = app(Tokens::class);
    $secret = $tokens->create('laptop');

    expect($secret)->toMatch('/^tx_[A-Za-z0-9]{40}$/')
        ->and(McpToken::query()->sole()->token_hash)->toBe(hash('sha256', $secret))
        ->and(json_encode(McpToken::query()->sole()->getAttributes()))->not->toContain($secret);

    $tokens->revoke('laptop');

    expect(McpToken::query()->sole()->revoked_at?->toIso8601ZuluString())->toBe('2026-11-09T10:00:00Z')
        ->and($tokens->create('laptop'))->not->toBe($secret);
});

it('refuses a bad name, a second active token and revoking what is not there', function (): void {
    $tokens = app(Tokens::class);
    $tokens->create('desk');

    expect(fn () => $tokens->create('My Laptop'))->toThrow(InvalidArgumentException::class, 'A token name is lowercase letters, digits and hyphens, at most 40 characters.')
        ->and(fn () => $tokens->create('desk'))->toThrow(InvalidArgumentException::class, 'desk already has a token; revoke it first.')
        ->and(fn () => $tokens->revoke('nobody'))->toThrow(InvalidArgumentException::class, 'nobody has no active token.');
});
