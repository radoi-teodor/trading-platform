<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPrice extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'symbol',
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'open' => 'integer',
            'high' => 'integer',
            'low' => 'integer',
            'close' => 'integer',
            'volume' => 'integer',
        ];
    }
}
