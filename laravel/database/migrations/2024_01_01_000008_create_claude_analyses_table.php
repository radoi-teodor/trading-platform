<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claude_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('market_outlook', ['bullish', 'bearish', 'neutral']);
            $table->float('confidence');
            $table->text('reasoning');
            $table->text('risk_assessment')->nullable();
            $table->text('lessons_from_yesterday')->nullable();
            $table->json('strategy_json');
            $table->enum('status', ['pending', 'active', 'completed', 'failed'])->default('pending');
            $table->integer('trades_planned')->default(0);
            $table->integer('trades_executed')->default(0);
            $table->bigInteger('total_pnl_cents')->default(0);
            $table->longText('prompt_sent');
            $table->longText('raw_response');
            $table->enum('next_review', ['midday', 'end_of_day', 'tomorrow'])->default('tomorrow');
            $table->dateTime('created_at');
            $table->dateTime('completed_at')->nullable();

            $table->index(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claude_analyses');
    }
};
