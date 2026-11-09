<?php

declare(strict_types=1);

namespace App\Execution\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The operator's approval of a live account (D-82): given after re-authentication, effective 24 hours later (D-19),
 * withdrawable at any time.
 *
 * @property int $id
 * @property int $account_id
 * @property string $approved_by ui or cli
 * @property CarbonImmutable $approved_at
 * @property CarbonImmutable $effective_at
 * @property CarbonImmutable|null $withdrawn_at
 */
final class LiveApproval extends Model
{
    public $timestamps = false;

    protected $fillable = ['account_id', 'approved_by', 'approved_at', 'effective_at', 'withdrawn_at'];

    protected function casts(): array
    {
        return ['approved_at' => 'immutable_datetime', 'effective_at' => 'immutable_datetime', 'withdrawn_at' => 'immutable_datetime'];
    }
}
