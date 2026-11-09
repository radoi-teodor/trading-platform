<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Order book depth every 5 minutes (D-91): the quote notional resting within 1, 2 and 5 % of the price on each side,
 * from the bookDepth archives and from the local order book live. Written once (D-84).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_depth', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('time');

            foreach (['bid_1', 'bid_2', 'bid_5', 'ask_1', 'ask_2', 'ask_5'] as $column) {
                $table->decimal($column, 36, 18);
            }

            $table->smallInteger('source');
            $table->timestampTz('received_at');
            $table->primary(['instrument_id', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_depth');
    }
};
