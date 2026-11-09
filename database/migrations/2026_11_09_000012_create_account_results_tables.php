<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** What an engine publishes for reading (D-76): its closed trades and its equity each hour. Derived, rebuilt by replay. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_trades', function (Blueprint $table): void {
            $table->foreignId('account_id')->constrained();
            $table->unsignedInteger('seq');
            $table->string('strategy', 16);
            $table->string('label', 120);
            $table->string('symbol', 40);
            $table->string('side', 8);
            $table->timestampTz('opened_at');
            $table->timestampTz('closed_at');
            $table->decimal('quantity', 36, 18);
            $table->decimal('entry_price', 36, 18);
            $table->decimal('exit_price', 36, 18)->nullable();
            $table->decimal('gross', 36, 18);
            $table->decimal('fees', 36, 18);
            $table->decimal('slippage', 36, 18);
            $table->decimal('funding', 36, 18);
            $table->decimal('net', 36, 18);
            $table->decimal('r_multiple', 36, 18)->nullable();
            $table->string('exit_reason', 20)->nullable();
            $table->primary(['account_id', 'seq']);
            $table->index(['account_id', 'closed_at']);
        });

        Schema::create('account_equity', function (Blueprint $table): void {
            $table->foreignId('account_id')->constrained();
            $table->timestampTz('at');
            $table->decimal('equity', 36, 18);
            $table->primary(['account_id', 'at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_equity');
        Schema::dropIfExists('account_trades');
    }
};
