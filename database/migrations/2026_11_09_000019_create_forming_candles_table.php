<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The minute still forming, one row per instrument, rewritten by market:stream about once a second for the live
 * chart (D-87). Nothing that decides reads it: strategies, the engine and backtests use closed candles only.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forming_candles', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->primary()->constrained()->cascadeOnDelete();
            $table->timestampTz('open_time');
            $table->decimal('open', 36, 18);
            $table->decimal('high', 36, 18);
            $table->decimal('low', 36, 18);
            $table->decimal('close', 36, 18);
            $table->decimal('volume', 36, 18);
            $table->decimal('quote_volume', 36, 18);
            $table->unsignedInteger('trade_count');
            $table->decimal('taker_buy_volume', 36, 18);
            $table->timestampTz('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forming_candles');
    }
};
