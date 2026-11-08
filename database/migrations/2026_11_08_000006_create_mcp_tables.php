<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MCP tokens, stored only as hashes, and every call they made (D-63). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcp_tokens', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 40);
            $table->char('token_hash', 64)->unique();
            $table->timestampTz('created_at');
            $table->timestampTz('last_used_at')->nullable();
            $table->timestampTz('revoked_at')->nullable();
            $table->index(['name', 'revoked_at']);
        });

        Schema::create('mcp_calls', function (Blueprint $table): void {
            $table->id();
            $table->string('token_name', 40)->index();
            $table->string('method', 60);
            $table->string('tool', 60)->nullable();
            $table->char('arguments_hash', 64);
            $table->unsignedInteger('duration_ms');
            $table->string('outcome', 20);
            $table->timestampTz('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcp_calls');
        Schema::dropIfExists('mcp_tokens');
    }
};
