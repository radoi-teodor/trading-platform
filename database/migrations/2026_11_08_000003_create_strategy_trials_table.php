<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Every backtest of a strategy family, by anyone: the count and the Sharpe ratios feed the deflated Sharpe ratio (D-58). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_trials', function (Blueprint $table): void {
            $table->id();
            $table->string('family', 40)->index();
            $table->string('strategy', 60);
            $table->jsonb('parameters');
            $table->char('definition_hash', 64);
            $table->date('window_from');
            $table->date('window_to');
            $table->decimal('daily_sharpe', 40, 18)->nullable();
            $table->unsignedInteger('observations');
            $table->timestampTz('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategy_trials');
    }
};
