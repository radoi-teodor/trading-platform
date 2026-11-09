<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The operator's approvals of live accounts, effective 24 hours after they are given (D-82, D-19), and each
 * deployment's stage and size multiplier on the ladder (D-83); existing deployments are paper at full size.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_approvals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->string('approved_by', 40);
            $table->timestampTz('approved_at');
            $table->timestampTz('effective_at');
            $table->timestampTz('withdrawn_at')->nullable();
        });

        Schema::table('deployments', function (Blueprint $table): void {
            $table->string('stage', 20)->default('paper');
            $table->decimal('size_multiplier', 36, 18)->default('1');
        });
    }

    public function down(): void
    {
        Schema::table('deployments', function (Blueprint $table): void {
            $table->dropColumn(['stage', 'size_multiplier']);
        });
        Schema::dropIfExists('live_approvals');
    }
};
