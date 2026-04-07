<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'portfolio_id',
        'claude_analysis_id',
        'symbol',
        'type',
        'side',
        'quantity',
        'price_cents',
        'status',
        'filled_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'price_cents' => 'integer',
            'filled_at' => 'datetime',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function claudeAnalysis(): BelongsTo
    {
        return $this->belongsTo(ClaudeAnalysis::class);
    }

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }
}
