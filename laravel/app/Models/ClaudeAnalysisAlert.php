<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaudeAnalysisAlert extends Model
{
    protected $fillable = [
        'claude_analysis_id',
        'result_order_id',
        'symbol',
        'condition_text',
        'parsed_condition',
        'triggered',
        'triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'parsed_condition' => 'array',
            'triggered' => 'boolean',
            'triggered_at' => 'datetime',
        ];
    }

    public function claudeAnalysis(): BelongsTo
    {
        return $this->belongsTo(ClaudeAnalysis::class);
    }

    public function resultOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'result_order_id');
    }
}
