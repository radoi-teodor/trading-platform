<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** The daily report can be turned off (D-77). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table): void {
            $table->boolean('notify_daily_report')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table): void {
            $table->dropColumn('notify_daily_report');
        });
    }
};
