<?php

declare(strict_types=1);

namespace App\Providers;

use App\Market\Binance\BinanceEndpoints;
use App\Market\Binance\Sleeper;
use App\Market\Binance\SystemSleeper;
use App\Support\Clock\Clock;
use App\Support\Clock\WallClock;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Clock::class, WallClock::class);
        $this->app->singleton(Sleeper::class, SystemSleeper::class);
        $this->app->singleton(BinanceEndpoints::class, fn (): BinanceEndpoints => new BinanceEndpoints(
            spotRest: Config::string('exchanges.binance.spot.rest'),
            perpRest: Config::string('exchanges.binance.perp.rest'),
            spotStream: Config::string('exchanges.binance.spot.stream'),
            perpStream: Config::string('exchanges.binance.perp.stream'),
            archive: Config::string('exchanges.binance.archive'),
            timeoutSeconds: Config::integer('exchanges.binance.timeout_seconds'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
