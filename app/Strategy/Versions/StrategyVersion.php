<?php

declare(strict_types=1);

namespace App\Strategy\Versions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

/**
 * One immutable version of a strategy document (D-61).
 *
 * @property int $id
 * @property string $strategy
 * @property int $version
 * @property string $document
 * @property string $sha256
 * @property string $author
 * @property int|null $parent_id
 * @property CarbonImmutable $created_at
 */
final class StrategyVersion extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['strategy', 'version', 'document', 'sha256', 'author', 'parent_id', 'created_at'];

    public function label(): string
    {
        return "{$this->strategy}@v{$this->version}";
    }

    /** @return HasMany<StrategyStage, $this> */
    public function stages(): HasMany
    {
        return $this->hasMany(StrategyStage::class);
    }

    /** The stage it is in now. */
    public function stage(): string
    {
        $stage = $this->stages()->orderByDesc('id')->value('stage');

        return is_string($stage) ? $stage : throw new LogicException("{$this->label()} has no stage.");
    }

    protected function casts(): array
    {
        return ['version' => 'integer', 'parent_id' => 'integer', 'created_at' => 'immutable_datetime'];
    }
}
