<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claude_analyses', function (Blueprint $table) {
            $table->string('strategy_style', 50)->nullable()->after('next_review');
        });
    }

    public function down(): void
    {
        Schema::table('claude_analyses', function (Blueprint $table) {
            $table->dropColumn('strategy_style');
        });
    }
};
