<?php

declare(strict_types=1);

namespace App\Agents;

use App\Agents\Models\McpToken;
use App\Support\Clock\Clock;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Creates and revokes MCP tokens (D-63) for the command line and the Settings screen (D-75). A token's secret is
 * returned once, by create, and only its SHA-256 is stored.
 */
final readonly class Tokens
{
    public function __construct(private Clock $clock) {}

    /** @throws InvalidArgumentException when the name is malformed or already has an active token */
    public function create(string $name): string
    {
        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $name) !== 1 || strlen($name) > 40) {
            throw new InvalidArgumentException('A token name is lowercase letters, digits and hyphens, at most 40 characters.');
        }

        if (McpToken::query()->where('name', $name)->whereNull('revoked_at')->exists()) {
            throw new InvalidArgumentException("{$name} already has a token; revoke it first.");
        }

        $secret = 'tx_'.Str::random(40);
        McpToken::query()->create(['name' => $name, 'token_hash' => hash('sha256', $secret), 'created_at' => $this->clock->now()]);

        return $secret;
    }

    /** @throws InvalidArgumentException when the name has no active token */
    public function revoke(string $name): void
    {
        $revoked = McpToken::query()->where('name', $name)->whereNull('revoked_at')->update(['revoked_at' => $this->clock->now()]);

        if ($revoked === 0) {
            throw new InvalidArgumentException("{$name} has no active token.");
        }
    }
}
