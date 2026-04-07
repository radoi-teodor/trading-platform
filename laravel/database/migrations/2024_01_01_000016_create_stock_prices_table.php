<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->bigInteger('open');
            $table->bigInteger('high');
            $table->bigInteger('low');
            $table->bigInteger('close');
            $table->bigInteger('volume');
            $table->date('date');

            $table->unique(['symbol', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
    }
};
