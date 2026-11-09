<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * An instrument's lifetime (D-89): when it was listed, when it stopped trading, and what the contract is on (COIN,
 * INDEX, ...). Minutes outside the lifetime are not gaps. Null means not known yet.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table): void {
            $table->timestampTz('listed_at')->nullable();
            $table->timestampTz('delisted_at')->nullable();
            $table->string('underlying_type', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table): void {
            $table->dropColumn(['listed_at', 'delisted_at', 'underlying_type']);
        });
    }
};
