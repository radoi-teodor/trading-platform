<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'order_id',
        'portfolio_id',
        'symbol',
        'side',
        'quantity',
        'price_cents',
        'pnl_cents',
        'fee_cents',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'price_cents' => 'integer',
            'pnl_cents' => 'integer',
            'fee_cents' => 'integer',
            'executed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
