<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Positioning of a perpetual every 5 minutes (D-91): the global long/short account ratio, the top traders' position
 * ratio and the taker buy/sell volume ratio, from the metrics archives and the same REST data live. Written once,
 * like the other data for scalping (D-84); a ratio Binance did not give is null.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positioning', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('time');
            $table->decimal('long_short_ratio', 36, 18)->nullable();
            $table->decimal('top_trader_ratio', 36, 18)->nullable();
            $table->decimal('taker_ratio', 36, 18)->nullable();
            $table->smallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positioning');
    }
};
