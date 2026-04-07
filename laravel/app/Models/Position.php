<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Position extends Model
{
    protected $fillable = [
        'portfolio_id',
        'symbol',
        'side',
        'quantity',
        'avg_entry_price_cents',
        'current_price_cents',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'avg_entry_price_cents' => 'integer',
            'current_price_cents' => 'integer',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNull('closed_at');
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->whereNotNull('closed_at');
    }
}
