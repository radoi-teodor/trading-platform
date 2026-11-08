<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** The hold-out of each version, used once (D-58), and the gate reports that promotion reads (docs/simulation.md, section 7). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_holdouts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('strategy_version_id')->unique()->constrained('strategy_versions');
            $table->json('parameters');
            $table->date('window_from');
            $table->date('window_to');
            $table->json('outcome');
            $table->timestampTz('created_at');
        });

        Schema::create('strategy_gate_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('strategy_version_id')->constrained('strategy_versions');
            $table->boolean('passed');
            $table->json('report');
            $table->timestampTz('created_at');
            $table->index(['strategy_version_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategy_gate_reports');
        Schema::dropIfExists('strategy_holdouts');
    }
};
