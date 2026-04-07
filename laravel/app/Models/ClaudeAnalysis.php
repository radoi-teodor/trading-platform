<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaudeAnalysis extends Model
{
    public $timestamps = false;

    protected $table = 'claude_analyses';

    protected $fillable = [
        'user_id',
        'date',
        'market_outlook',
        'confidence',
        'reasoning',
        'risk_assessment',
        'lessons_from_yesterday',
        'strategy_json',
        'status',
        'trades_planned',
        'trades_executed',
        'total_pnl_cents',
        'prompt_sent',
        'raw_response',
        'next_review',
        'created_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'strategy_json' => 'array',
            'date' => 'date',
            'confidence' => 'float',
            'trades_planned' => 'integer',
            'trades_executed' => 'integer',
            'total_pnl_cents' => 'integer',
            'created_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analysisTrades(): HasMany
    {
        return $this->hasMany(ClaudeAnalysisTrade::class);
    }

    public function analysisCloses(): HasMany
    {
        return $this->hasMany(ClaudeAnalysisClose::class);
    }

    public function analysisAlerts(): HasMany
    {
        return $this->hasMany(ClaudeAnalysisAlert::class);
    }
}
