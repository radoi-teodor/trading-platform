<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** The data for scalping (D-79), with the source ranking of candles: archive above REST above stream. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_flow', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained();
            $table->timestampTz('open_time');
            $table->decimal('large_buy_volume', 36, 18);
            $table->decimal('large_sell_volume', 36, 18);
            $table->unsignedInteger('large_buy_count');
            $table->unsignedInteger('large_sell_count');
            $table->decimal('largest_notional', 36, 18);
            $table->decimal('threshold', 36, 18);
            $table->unsignedSmallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'open_time']);
        });

        Schema::create('open_interest', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained();
            $table->timestampTz('time');
            $table->decimal('contracts', 36, 18);
            $table->decimal('value', 36, 18);
            $table->unsignedSmallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'time']);
        });

        foreach (['mark_price_minutes', 'premium_index_minutes'] as $name) {
            Schema::create($name, function (Blueprint $table): void {
                $table->foreignId('instrument_id')->constrained();
                $table->timestampTz('open_time');
                $table->decimal('open', 36, 18);
                $table->decimal('high', 36, 18);
                $table->decimal('low', 36, 18);
                $table->decimal('close', 36, 18);
                $table->unsignedSmallInteger('source');
                $table->timestampTz('received_at');
                $table->primary(['instrument_id', 'open_time']);
            });
        }
    }

    public function down(): void
    {
        foreach (['premium_index_minutes', 'mark_price_minutes', 'open_interest', 'trade_flow'] as $name) {
            Schema::dropIfExists($name);
        }
    }
};
