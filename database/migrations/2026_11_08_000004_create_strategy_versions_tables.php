<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Immutable strategy versions and the stages each one went through (D-61, docs/strategy/configurator.md, section 6). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_versions', function (Blueprint $table): void {
            $table->id();
            $table->string('strategy', 40);
            $table->unsignedInteger('version');
            $table->text('document');
            $table->char('sha256', 64);
            $table->string('author', 80);
            $table->foreignId('parent_id')->nullable()->constrained('strategy_versions');
            $table->timestampTz('created_at');
            $table->unique(['strategy', 'version']);
        });

        Schema::create('strategy_stages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('strategy_version_id')->constrained('strategy_versions');
            $table->string('stage', 20);
            $table->string('reason', 200);
            $table->timestampTz('created_at');
            $table->index(['strategy_version_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategy_stages');
        Schema::dropIfExists('strategy_versions');
    }
};
