<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The research universe month by month (D-89): who was in the top N by 30-day quote volume on the first day of each
 * month, so a backtest sees only the members of its time. Also drops candles_unpartitioned, kept as a fallback by the
 * partitioning migration until the next step's suite passed (D-90).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universe_members', function (Blueprint $table): void {
            $table->date('month');
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('rank');
            $table->decimal('quote_volume_30d', 36, 18);
            $table->primary(['month', 'instrument_id']);
        });

        DB::statement('DROP TABLE IF EXISTS candles_unpartitioned');
    }

    public function down(): void
    {
        Schema::dropIfExists('universe_members');
    }
};
