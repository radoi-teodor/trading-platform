<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scraper extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'type',
        'selector_config',
        'schedule',
        'keyword_filters',
        'associated_symbols',
        'is_active',
        'agent_status',
        'last_run_at',
        'articles_count',
    ];

    protected function casts(): array
    {
        return [
            'selector_config' => 'array',
            'keyword_filters' => 'array',
            'associated_symbols' => 'array',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(ScrapedArticle::class);
    }
}
