<?php

declare(strict_types=1);

namespace App\Agents\Console;

use App\Agents\Tokens;
use Illuminate\Console\Command;
use InvalidArgumentException;

/** Revokes a name's MCP token at once (D-63). */
final class RevokeMcpToken extends Command
{
    protected $signature = 'mcp:revoke {name : The token name}';

    protected $description = 'Revoke an MCP token';

    public function handle(Tokens $tokens): int
    {
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        try {
            $tokens->revoke($name);
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $this->info("Revoked the token of {$name}.");

        return self::SUCCESS;
    }
}
