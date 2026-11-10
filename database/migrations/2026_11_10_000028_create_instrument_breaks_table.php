<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Breaks in an instrument's trading (D-89): a perpetual that was delisted and listed again under the same symbol did
 * not trade from stopped_at up to resumed_at. Its archives hold flat candles for a part of that time and nothing for
 * the rest; neither is a gap, and neither is a price.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_breaks', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('stopped_at');
            $table->timestampTz('resumed_at');
            $table->primary(['instrument_id', 'stopped_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_breaks');
    }
};
