<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** The health check's state between runs (D-78), and the accounts it watches. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_checks', function (Blueprint $table): void {
            $table->string('name', 120)->primary();
            $table->timestampTz('failing_since');
            $table->timestampTz('alerted_at')->nullable();
            $table->text('detail');
        });

        Schema::table('accounts', function (Blueprint $table): void {
            $table->boolean('monitored')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table): void {
            $table->dropColumn('monitored');
        });
        Schema::dropIfExists('health_checks');
    }
};
