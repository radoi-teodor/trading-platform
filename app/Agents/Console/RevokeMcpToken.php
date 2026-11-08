<?php

declare(strict_types=1);

namespace App\Agents\Console;

use App\Agents\Models\McpToken;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;

/** Revokes a name's MCP token at once (D-63). */
final class RevokeMcpToken extends Command
{
    protected $signature = 'mcp:revoke {name : The token name}';

    protected $description = 'Revoke an MCP token';

    public function handle(Clock $clock): int
    {
        $name = $this->argument('name');
        $name = is_string($name) ? $name : '';
        $revoked = McpToken::query()->where('name', $name)->whereNull('revoked_at')->update(['revoked_at' => $clock->now()]);

        if ($revoked === 0) {
            $this->error("{$name} has no active token.");

            return self::FAILURE;
        }

        $this->info("Revoked the token of {$name}.");

        return self::SUCCESS;
    }
}
