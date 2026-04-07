<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('claude_analysis_id')->nullable()->constrained('claude_analyses')->nullOnDelete();
            $table->string('symbol');
            $table->enum('type', ['market', 'limit', 'stop_loss', 'take_profit']);
            $table->enum('side', ['buy', 'sell']);
            $table->decimal('quantity', 16, 8);
            $table->bigInteger('price_cents')->nullable();
            $table->enum('status', ['pending', 'filled', 'partial', 'cancelled', 'rejected']);
            $table->dateTime('filled_at')->nullable();
            $table->timestamps();

            $table->index(['portfolio_id', 'symbol']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
