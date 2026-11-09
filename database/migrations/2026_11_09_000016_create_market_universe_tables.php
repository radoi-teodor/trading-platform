<?php

declare(strict_types=1);

use App\Market\Instruments\Universe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The universe in the database, seeded once from config/market.php (D-80), and the history imports started from
 * the Settings screen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_universe', function (Blueprint $table): void {
            $table->id();
            $table->string('market', 8);
            $table->string('symbol', 40);
            $table->boolean('streamed')->default(true);
            $table->boolean('scalping')->default(false);
            $table->decimal('large_notional', 36, 18)->nullable();
            $table->timestampTz('created_at');
            $table->unique(['market', 'symbol']);
        });

        Schema::create('market_jobs', function (Blueprint $table): void {
            $table->id();
            $table->string('symbol', 40);
            $table->date('from');
            $table->boolean('candles');
            $table->boolean('scalping');
            $table->string('status', 20)->index();
            $table->text('output')->nullable();
            $table->timestampTz('created_at');
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
        });

        app(Universe::class)->seedFromConfig();
    }

    public function down(): void
    {
        Schema::dropIfExists('market_jobs');
        Schema::dropIfExists('market_universe');
    }
};
