<?php

declare(strict_types=1);

namespace App\Agents\Console;

use App\Agents\Models\McpToken;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/** Creates an MCP token (D-63) and prints its secret once; only its SHA-256 is stored. */
final class CreateMcpToken extends Command
{
    protected $signature = 'mcp:token {name : Who uses it, such as laptop or desk; recorded as agent:<name> on what it writes}';

    protected $description = 'Create a token for the MCP endpoint, shown once';

    public function handle(Clock $clock): int
    {
        $name = $this->argument('name');
        $name = is_string($name) ? $name : '';

        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $name) !== 1 || strlen($name) > 40) {
            $this->error('A token name is lowercase letters, digits and hyphens, at most 40 characters.');

            return self::FAILURE;
        }

        if (McpToken::query()->where('name', $name)->whereNull('revoked_at')->exists()) {
            $this->error("{$name} already has a token; revoke it first.");

            return self::FAILURE;
        }

        $secret = 'tx_'.Str::random(40);
        McpToken::query()->create(['name' => $name, 'token_hash' => hash('sha256', $secret), 'created_at' => $clock->now()]);

        $this->info("Token for {$name}, shown once; it cannot be read back:");
        $this->line($secret);
        $this->line('Send it as "Authorization: Bearer <token>" to /mcp. Revoke it with php artisan mcp:revoke '.$name.'.');

        return self::SUCCESS;
    }
}
