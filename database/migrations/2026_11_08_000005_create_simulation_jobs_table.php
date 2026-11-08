<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Backtests and grids run in the background (D-62). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_jobs', function (Blueprint $table): void {
            $table->id();
            $table->string('kind', 20);
            $table->jsonb('arguments');
            $table->string('status', 20)->index();
            $table->jsonb('summary')->nullable();
            $table->text('error')->nullable();
            $table->string('author', 80)->index();
            $table->timestampTz('created_at');
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_jobs');
    }
};
