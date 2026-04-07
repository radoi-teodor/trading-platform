<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaudeAnalysisTrade extends Model
{
    protected $fillable = [
        'claude_analysis_id',
        'executed_order_id',
        'symbol',
        'action',
        'order_type',
        'quantity',
        'entry_price_cents',
        'stop_loss_cents',
        'take_profit_cents',
        'urgency',
        'confidence',
        'reasoning',
        'status',
        'skip_reason',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'entry_price_cents' => 'integer',
            'stop_loss_cents' => 'integer',
            'take_profit_cents' => 'integer',
            'confidence' => 'float',
        ];
    }

    public function claudeAnalysis(): BelongsTo
    {
        return $this->belongsTo(ClaudeAnalysis::class);
    }

    public function executedOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'executed_order_id');
    }
}
