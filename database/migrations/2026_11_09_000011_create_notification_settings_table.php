<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** The operator's Telegram settings and optional notifications, one row; the token and chat id encrypted (D-75). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table): void {
            $table->id();
            $table->text('telegram_token')->nullable();
            $table->text('telegram_chat_id')->nullable();
            $table->timestampTz('telegram_set_at')->nullable();
            $table->boolean('notify_command')->default(true);
            $table->boolean('notify_volatility_shock')->default(false);
            $table->boolean('notify_price_sanity')->default(false);
            $table->timestampTz('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
