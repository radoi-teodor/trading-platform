<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claude_analysis_closes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claude_analysis_id')->constrained('claude_analyses')->cascadeOnDelete();
            $table->string('symbol');
            $table->text('reason');
            $table->enum('urgency', ['immediate', 'end_of_day']);
            $table->enum('status', ['pending', 'closed', 'skipped'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claude_analysis_closes');
    }
};
