<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claude_analysis_trades', function (Blueprint $table) {
            $table->string('direction', 10)->nullable()->after('action');
            $table->float('stop_loss_pct')->nullable()->after('take_profit_cents');
            $table->float('take_profit_pct')->nullable()->after('stop_loss_pct');
            $table->boolean('reentry_allowed')->default(false)->after('take_profit_pct');
            $table->integer('max_entries')->default(1)->after('reentry_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('claude_analysis_trades', function (Blueprint $table) {
            $table->dropColumn(['direction', 'stop_loss_pct', 'take_profit_pct', 'reentry_allowed', 'max_entries']);
        });
    }
};
