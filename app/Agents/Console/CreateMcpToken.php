<?php

declare(strict_types=1);

namespace App\Agents\Console;

use App\Agents\Tokens;
use Illuminate\Console\Command;
use InvalidArgumentException;

/** Creates an MCP token (D-63) and prints its secret once; only its SHA-256 is stored. */
final class CreateMcpToken extends Command
{
    protected $signature = 'mcp:token {name : Who uses it, such as laptop or desk; recorded as agent:<name> on what it writes}';

    protected $description = 'Create a token for the MCP endpoint, shown once';

    public function handle(Tokens $tokens): int
    {
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        try {
            $secret = $tokens->create($name);
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $this->info("Token for {$name}, shown once; it cannot be read back:");
        $this->line($secret);
        $this->line('Send it as "Authorization: Bearer <token>" to /mcp. Revoke it with php artisan mcp:revoke '.$name.'.');

        return self::SUCCESS;
    }
}
