<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'risk_tolerance',
        'max_daily_trades',
        'max_position_pct',
        'max_daily_loss_pct',
        'preferred_markets',
        'custom_instructions',
        'analysis_schedule',
        'auto_execute',
        'timezone',
    ];

    protected function casts(): array
    {
        return [
            'preferred_markets' => 'array',
            'auto_execute' => 'boolean',
            'max_position_pct' => 'integer',
            'max_daily_loss_pct' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
