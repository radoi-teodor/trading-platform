<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Clock\Clock;
use App\Support\Clock\WallClock;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Clock::class, WallClock::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
