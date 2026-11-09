<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** An import can end before today (D-89): a coin is imported over the months it was in the research universe. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('market_jobs', function (Blueprint $table): void {
            $table->date('to')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('market_jobs', function (Blueprint $table): void {
            $table->dropColumn('to');
        });
    }
};
