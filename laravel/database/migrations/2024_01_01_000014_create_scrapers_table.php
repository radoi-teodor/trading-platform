<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scrapers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('url');
            $table->enum('type', ['html', 'rss']);
            $table->json('selector_config');
            $table->string('schedule');
            $table->json('keyword_filters');
            $table->json('associated_symbols');
            $table->boolean('is_active')->default(false);
            $table->enum('agent_status', ['stopped', 'running', 'error'])->default('stopped');
            $table->dateTime('last_run_at')->nullable();
            $table->integer('articles_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scrapers');
    }
};
