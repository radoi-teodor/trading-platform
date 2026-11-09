<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paper and testnet accounts and what their engine keeps (docs/engine.md, section 9): the strategies deployed on
 * them, the commands for the engine, the journal it writes and replays (D-68), and the snapshot the UI reads.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('mode', 10);
            $table->string('quote', 10);
            $table->decimal('spot_balance', 36, 18);
            $table->decimal('futures_balance', 36, 18);
            $table->json('symbols');
            $table->timestampTz('started_at');
            $table->timestampTz('created_at');
        });

        Schema::create('deployments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('strategy_version_id')->constrained('strategy_versions');
            $table->json('parameters');
            $table->boolean('active');
            $table->string('reason');
            $table->timestampTz('created_at');
            $table->timestampTz('ended_at')->nullable();
        });

        Schema::create('engine_commands', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->string('kind', 20);
            $table->string('strategy', 80)->nullable();
            $table->string('issued_by', 40);
            $table->timestampTz('created_at');
            $table->timestampTz('applied_at')->nullable();
            $table->timestampTz('applied_event_at')->nullable();
            $table->index(['account_id', 'applied_at']);
        });

        Schema::create('engine_journal', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->timestampTz('event_at');
            $table->string('kind', 10);
            $table->unsignedSmallInteger('format');
            $table->json('payload');
            $table->timestampTz('created_at');
            $table->index(['account_id', 'id']);
        });

        Schema::create('account_snapshots', function (Blueprint $table): void {
            $table->foreignId('account_id')->primary()->constrained();
            $table->timestampTz('minute');
            $table->unsignedBigInteger('quiet_decisions');
            $table->json('payload');
            $table->timestampTz('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_snapshots');
        Schema::dropIfExists('engine_journal');
        Schema::dropIfExists('engine_commands');
        Schema::dropIfExists('deployments');
        Schema::dropIfExists('accounts');
    }
};
