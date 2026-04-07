<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claude_analysis_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claude_analysis_id')->constrained('claude_analyses')->cascadeOnDelete();
            $table->string('symbol');
            $table->enum('action', ['buy', 'sell']);
            $table->enum('order_type', ['market', 'limit']);
            $table->decimal('quantity', 16, 8);
            $table->bigInteger('entry_price_cents')->nullable();
            $table->bigInteger('stop_loss_cents')->nullable();
            $table->bigInteger('take_profit_cents')->nullable();
            $table->enum('urgency', ['immediate', 'wait_for_dip', 'end_of_day']);
            $table->float('confidence');
            $table->text('reasoning');
            $table->enum('status', ['pending', 'executed', 'skipped', 'failed'])->default('pending');
            $table->integer('executed_order_id')->nullable();
            $table->text('skip_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claude_analysis_trades');
    }
};
