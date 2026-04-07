<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claude_analysis_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claude_analysis_id')->constrained('claude_analyses')->cascadeOnDelete();
            $table->string('symbol');
            $table->string('condition_text');
            $table->json('parsed_condition');
            $table->boolean('triggered')->default(false);
            $table->dateTime('triggered_at')->nullable();
            $table->integer('result_order_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claude_analysis_alerts');
    }
};
