<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Market data tables (docs/market-data.md, section 2; D-31). Amounts are numeric(36,18), times timestamptz.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table): void {
            $table->id();
            $table->string('exchange', 20);
            $table->string('market', 10);
            $table->string('symbol', 40);
            $table->string('venue_symbol', 40);
            $table->string('base', 20);
            $table->string('quote', 20);
            $table->string('settle', 20)->nullable();
            $table->string('status', 20);
            $table->timestampTz('first_seen_at');
            $table->timestampTz('last_seen_at');
            $table->timestampsTz();
            $table->unique(['exchange', 'market', 'venue_symbol']);
            $table->unique(['exchange', 'market', 'symbol']);
        });

        Schema::create('instrument_filters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('valid_from');
            $table->decimal('tick_size', 36, 18);
            $table->decimal('step_size', 36, 18);
            $table->decimal('min_qty', 36, 18);
            $table->decimal('max_qty', 36, 18)->nullable();
            $table->decimal('min_notional', 36, 18)->nullable();
            $table->jsonb('raw');
            $table->timestampTz('created_at')->nullable();
            $table->unique(['instrument_id', 'valid_from']);
        });

        Schema::create('candles', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->string('timeframe', 4);
            $table->timestampTz('open_time');
            $table->decimal('open', 36, 18);
            $table->decimal('high', 36, 18);
            $table->decimal('low', 36, 18);
            $table->decimal('close', 36, 18);
            $table->decimal('volume', 36, 18);
            $table->decimal('quote_volume', 36, 18);
            $table->unsignedBigInteger('trade_count');
            $table->decimal('taker_buy_volume', 36, 18);
            $table->smallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'timeframe', 'open_time']);
        });

        DB::statement(<<<'SQL'
            ALTER TABLE candles ADD CONSTRAINT candles_consistent CHECK (
                low > 0
                AND low <= open AND low <= close AND low <= high
                AND high >= open AND high >= close
                AND volume >= 0 AND quote_volume >= 0 AND trade_count >= 0
                AND taker_buy_volume >= 0 AND taker_buy_volume <= volume
            )
            SQL);

        Schema::create('funding_rates', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('funding_time');
            $table->decimal('rate', 36, 18);
            $table->smallInteger('interval_hours');
            $table->decimal('mark_price', 36, 18)->nullable();
            $table->smallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'funding_time']);
        });

        Schema::create('heartbeats', function (Blueprint $table): void {
            $table->string('process', 100)->primary();
            $table->timestampTz('beat_at');
            $table->jsonb('details');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heartbeats');
        Schema::dropIfExists('funding_rates');
        Schema::dropIfExists('candles');
        Schema::dropIfExists('instrument_filters');
        Schema::dropIfExists('instruments');
    }
};
