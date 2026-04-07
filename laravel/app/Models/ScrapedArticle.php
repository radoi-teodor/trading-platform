<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrapedArticle extends Model
{
    protected $fillable = [
        'scraper_id',
        'title',
        'content',
        'url',
        'source',
        'symbols',
        'sentiment_score',
        'sentiment_label',
        'fed_to_claude',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'symbols' => 'array',
            'fed_to_claude' => 'boolean',
            'sentiment_score' => 'float',
            'published_at' => 'datetime',
        ];
    }

    public function scraper(): BelongsTo
    {
        return $this->belongsTo(Scraper::class);
    }
}
