<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentHealth extends Model
{
    protected $table = 'agent_health';

    public $timestamps = false;

    protected $fillable = [
        'agent_type',
        'agent_id',
        'user_id',
        'status',
        'last_heartbeat_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'last_heartbeat_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
