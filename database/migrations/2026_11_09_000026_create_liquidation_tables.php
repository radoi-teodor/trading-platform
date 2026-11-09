<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Liquidations per perpetual and minute, collected live from !forceOrder@arr (D-91); Binance publishes no history.
 * The stream sends at most one per symbol per second, so they are a lower bound. liquidation_coverage holds every
 * minute the stream listened to whole: a covered minute without a row had none, an uncovered one is unknown.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidation_minutes', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('open_time');
            $table->unsignedInteger('long_count');
            $table->decimal('long_notional', 36, 18);
            $table->unsignedInteger('short_count');
            $table->decimal('short_notional', 36, 18);
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'open_time']);
        });

        Schema::create('liquidation_coverage', function (Blueprint $table): void {
            $table->timestampTz('minute')->primary();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidation_coverage');
        Schema::dropIfExists('liquidation_minutes');
    }
};
