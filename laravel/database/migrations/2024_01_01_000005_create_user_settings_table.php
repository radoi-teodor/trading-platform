<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('risk_tolerance', ['conservative', 'moderate', 'aggressive'])->default('moderate');
            $table->integer('max_daily_trades')->default(10);
            $table->integer('max_position_pct')->default(20);
            $table->integer('max_daily_loss_pct')->default(5);
            $table->json('preferred_markets')->default('["us_stocks"]');
            $table->text('custom_instructions')->nullable();
            $table->string('analysis_schedule')->default('premarket');
            $table->boolean('auto_execute')->default(true);
            $table->string('timezone')->default('America/New_York');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
