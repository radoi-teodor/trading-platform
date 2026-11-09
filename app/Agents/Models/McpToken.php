<?php

declare(strict_types=1);

namespace App\Agents\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * An MCP token (D-63): its name and the SHA-256 of its secret, never the secret.
 *
 * @property int $id
 * @property string $name
 * @property string $token_hash
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $last_used_at
 * @property CarbonImmutable|null $revoked_at
 */
final class McpToken extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['name', 'token_hash', 'created_at', 'last_used_at', 'revoked_at'];

    protected $hidden = ['token_hash'];

    /** The author recorded on what this token's agent writes: agent:<name>. */
    public function author(): string
    {
        return "agent:{$this->name}";
    }

    protected function casts(): array
    {
        return ['created_at' => 'immutable_datetime', 'last_used_at' => 'immutable_datetime', 'revoked_at' => 'immutable_datetime'];
    }
}
