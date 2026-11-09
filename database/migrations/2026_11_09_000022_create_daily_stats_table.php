<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per instrument per UTC day (D-90): the daily candle of every perpetual, so rankings, screens and listing
 * studies never read minutes. Funding and open interest are filled from the stored series where they exist.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stats', function (Blueprint $table): void {
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->date('day');
            $table->decimal('open', 36, 18);
            $table->decimal('high', 36, 18);
            $table->decimal('low', 36, 18);
            $table->decimal('close', 36, 18);
            $table->decimal('volume', 36, 18);
            $table->decimal('quote_volume', 36, 18);
            $table->unsignedBigInteger('trades');
            $table->decimal('funding_rate', 36, 18)->nullable();
            $table->decimal('open_interest_value', 36, 18)->nullable();
            $table->primary(['instrument_id', 'day']);
            $table->index(['day', 'quote_volume']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stats');
    }
};
