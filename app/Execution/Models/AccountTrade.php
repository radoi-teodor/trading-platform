<?php

declare(strict_types=1);

namespace App\Execution\Models;

use App\Execution\Models\Casts\PlainDecimal;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * A closed round trip as the account's engine published it (D-76), numbered by its place in the ledger so a replay
 * writes the same row again.
 *
 * @property int $account_id
 * @property int $seq
 * @property string $strategy the strategy's identity key, as in the ledger
 * @property string $label the strategy's name and version, such as ema-cross@v2
 * @property string $symbol
 * @property string $side
 * @property CarbonImmutable $opened_at
 * @property CarbonImmutable $closed_at
 * @property string $quantity
 * @property string $entry_price
 * @property string|null $exit_price
 * @property string $gross
 * @property string $fees
 * @property string $slippage
 * @property string $funding
 * @property string $net
 * @property string|null $r_multiple
 * @property string|null $exit_reason
 */
final class AccountTrade extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'seq';

    protected $guarded = [];

    protected function casts(): array
    {
        $decimals = array_fill_keys(['quantity', 'entry_price', 'exit_price', 'gross', 'fees', 'slippage', 'funding', 'net', 'r_multiple'], PlainDecimal::class);

        return ['opened_at' => 'immutable_datetime', 'closed_at' => 'immutable_datetime', 'seq' => 'integer'] + $decimals;
    }
}
