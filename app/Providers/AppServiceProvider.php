<?php

declare(strict_types=1);

namespace App\Providers;

use App\Agents\Models\McpToken;
use App\Market\Archive\BinanceArchive;
use App\Market\Binance\BinanceEndpoints;
use App\Market\Binance\Sleeper;
use App\Market\Binance\SystemSleeper;
use App\Market\Contracts\CandleFeed;
use App\Market\Feed\DatabaseCandleFeed;
use App\Market\Instruments\StoredInstruments;
use App\Market\Stream\PhrityTransport;
use App\Market\Stream\StreamTransport;
use App\Risk\RiskPolicy;
use App\Simulation\SimulationQueue;
use App\Simulation\StrategyLibrary;
use App\Support\Clock\Clock;
use App\Support\Clock\WallClock;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Clock::class, WallClock::class);
        $this->app->bind(StrategyLibrary::class, fn (): StrategyLibrary => new StrategyLibrary(
            $this->app->make(Clock::class),
            new StoredInstruments,
            RiskPolicy::account(Config::array('risk.account')),
        ));
        $this->app->bind(CandleFeed::class, fn (): CandleFeed => new DatabaseCandleFeed($this->app->make(ConnectionInterface::class)));
        $this->app->bind(SimulationQueue::class, fn (): SimulationQueue => new SimulationQueue(
            $this->app->make(Dispatcher::class),
            $this->app->make(Clock::class),
            Config::string('simulation.queue_connection'),
        ));
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
        RateLimiter::for('mcp', function (Request $request): Limit {
            $token = $request->attributes->get('mcp_token');

            return Limit::perMinute(Config::integer('mcp.calls_per_minute'))
                ->by($token instanceof McpToken ? 'mcp:'.$token->name : 'mcp-ip:'.$request->ip());
        });
    }
}
