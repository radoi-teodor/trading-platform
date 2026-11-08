<?php

declare(strict_types=1);

namespace App\Agents\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * One MCP call (D-63): the token's name, the method and tool, a hash of the arguments, how long it took and how it
 * ended.
 *
 * @property int $id
 * @property string $token_name
 * @property string $method
 * @property string|null $tool
 * @property string $arguments_hash
 * @property int $duration_ms
 * @property string $outcome
 * @property CarbonImmutable $created_at
 */
final class McpCall extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['token_name', 'method', 'tool', 'arguments_hash', 'duration_ms', 'outcome', 'created_at'];

    protected function casts(): array
    {
        return ['duration_ms' => 'integer', 'created_at' => 'immutable_datetime'];
    }
}
