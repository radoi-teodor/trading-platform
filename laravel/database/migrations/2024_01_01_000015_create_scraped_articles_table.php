<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraped_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraper_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('url');
            $table->string('source');
            $table->dateTime('published_at');
            $table->float('sentiment_score')->default(0);
            $table->enum('sentiment_label', ['bullish', 'bearish', 'neutral'])->default('neutral');
            $table->json('symbols');
            $table->boolean('fed_to_claude')->default(false);
            $table->timestamps();

            $table->unique('url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_articles');
    }
};
