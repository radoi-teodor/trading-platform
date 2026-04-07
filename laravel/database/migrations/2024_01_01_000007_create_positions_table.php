<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->decimal('quantity', 16, 8);
            $table->bigInteger('avg_entry_price_cents');
            $table->bigInteger('current_price_cents');
            $table->enum('side', ['long', 'short']);
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();

            $table->index(['portfolio_id', 'symbol']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
