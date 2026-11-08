<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per backtest: what was run, a summary, and where the full report is (D-42).
        Schema::create('backtest_runs', function (Blueprint $table): void {
            $table->id();
            $table->string('strategy', 64);
            $table->string('strategy_key', 8);
            $table->jsonb('definition');
            $table->string('definition_hash', 64);
            $table->string('report_hash', 64)->index();
            $table->string('data_fingerprint', 64);
            $table->timestampTz('window_from');
            $table->timestampTz('window_to');
            $table->decimal('net_return', 36, 18);
            $table->decimal('max_drawdown', 36, 18);
            $table->decimal('sharpe', 36, 18)->nullable();
            $table->unsignedInteger('trades');
            $table->string('artifact');
            $table->timestampTz('started_at');
            $table->timestampTz('finished_at');
            $table->unsignedInteger('duration_ms');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backtest_runs');
    }
};
