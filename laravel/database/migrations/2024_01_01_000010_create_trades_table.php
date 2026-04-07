<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->enum('side', ['buy', 'sell']);
            $table->decimal('quantity', 16, 8);
            $table->bigInteger('price_cents');
            $table->bigInteger('pnl_cents')->default(0);
            $table->bigInteger('fee_cents')->default(0);
            $table->dateTime('executed_at');
            $table->timestamps();

            $table->index(['portfolio_id', 'symbol', 'executed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
