<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioSnapshot extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'portfolio_id',
        'date',
        'total_value_cents',
        'cash_cents',
        'positions_value_cents',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_value_cents' => 'integer',
            'cash_cents' => 'integer',
            'positions_value_cents' => 'integer',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
