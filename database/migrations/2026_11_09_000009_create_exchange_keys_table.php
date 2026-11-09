<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** An account's exchange key and secret, encrypted with the application key (D-20). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_keys', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->unique()->constrained();
            $table->text('api_key');
            $table->text('api_secret');
            $table->timestampTz('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_keys');
    }
};
