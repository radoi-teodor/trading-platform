<?php

declare(strict_types=1);

namespace App\Providers;

use App\Market\Archive\BinanceArchive;
use App\Market\Binance\BinanceEndpoints;
use App\Market\Binance\Sleeper;
use App\Market\Binance\SystemSleeper;
use App\Market\Stream\PhrityTransport;
use App\Market\Stream\StreamTransport;
use App\Support\Clock\Clock;
use App\Support\Clock\WallClock;
use Illuminate\Http\Client\Factory as HttpFactory;
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
        $this->app->bind(StreamTransport::class, PhrityTransport::class);
        $this->app->singleton(BinanceEndpoints::class, fn (): BinanceEndpoints => new BinanceEndpoints(
            spotRest: Config::string('exchanges.binance.spot.rest'),
            perpRest: Config::string('exchanges.binance.perp.rest'),
            spotStream: Config::string('exchanges.binance.spot.stream'),
            perpStream: Config::string('exchanges.binance.perp.stream'),
            archive: Config::string('exchanges.binance.archive'),
            timeoutSeconds: Config::integer('exchanges.binance.timeout_seconds'),
        ));
        $this->app->bind(BinanceArchive::class, fn (): BinanceArchive => new BinanceArchive(
            http: $this->app->make(HttpFactory::class),
            baseUrl: Config::string('exchanges.binance.archive'),
            cacheDirectory: Config::string('exchanges.binance.archive_cache'),
            timeoutSeconds: 120,
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
