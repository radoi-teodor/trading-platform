<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Execution\Models\Casts\PlainDecimal;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * The account's equity at the close of each hour, as its engine published it (D-76).
 *
 * @property int $account_id
 * @property CarbonImmutable $at
 * @property string $equity
 */
final class AccountEquity extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'account_equity';

    protected $primaryKey = 'at';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['at' => 'immutable_datetime', 'equity' => PlainDecimal::class];
    }
}
