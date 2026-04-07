<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaudeAnalysisClose extends Model
{
    protected $fillable = [
        'claude_analysis_id',
        'symbol',
        'reason',
        'urgency',
        'status',
    ];

    public function claudeAnalysis(): BelongsTo
    {
        return $this->belongsTo(ClaudeAnalysis::class);
    }
}
