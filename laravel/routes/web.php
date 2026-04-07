<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClaudeStrategyController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\StrategySettingsController;
use App\Http\Controllers\BrokerSettingsController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// Auth routes (guest only for login/register)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Strategies
    Route::get('/strategies', [ClaudeStrategyController::class, 'index'])->name('strategies.index');
    Route::get('/strategies/today', [ClaudeStrategyController::class, 'today'])->name('strategies.today');
    Route::post('/strategies/trigger', [ClaudeStrategyController::class, 'trigger'])->name('strategies.trigger');
    Route::post('/strategies/{id}/cancel', [ClaudeStrategyController::class, 'cancel'])->name('strategies.cancel');
    Route::get('/strategies/{id}', [ClaudeStrategyController::class, 'show'])->name('strategies.show');
    Route::get('/strategies/{analysisId}/trade/{tradeId}/chart', [ClaudeStrategyController::class, 'tradeChart'])->name('strategies.trade.chart');

    // Planned Trades (all strategies)
    Route::get('/trades/planned', [ClaudeStrategyController::class, 'plannedTrades'])->name('trades.planned');

    // Portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/positions', [PortfolioController::class, 'positions'])->name('portfolio.positions');
    Route::get('/portfolio/trades', [PortfolioController::class, 'trades'])->name('portfolio.trades');
    Route::get('/portfolio/performance', [PortfolioController::class, 'performance'])->name('portfolio.performance');

    // Scrapers
    Route::get('/scrapers', [ScraperController::class, 'index'])->name('scrapers.index');
    Route::get('/scrapers/create', [ScraperController::class, 'create'])->name('scrapers.create');
    Route::post('/scrapers', [ScraperController::class, 'store'])->name('scrapers.store');
    Route::get('/scrapers/{scraper}', [ScraperController::class, 'show'])->name('scrapers.show');
    Route::get('/scrapers/{scraper}/edit', [ScraperController::class, 'edit'])->name('scrapers.edit');
    Route::put('/scrapers/{scraper}', [ScraperController::class, 'update'])->name('scrapers.update');
    Route::delete('/scrapers/{scraper}', [ScraperController::class, 'destroy'])->name('scrapers.destroy');
    Route::post('/scrapers/{scraper}/toggle', [ScraperController::class, 'toggle'])->name('scrapers.toggle');
    Route::post('/scrapers/{scraper}/test', [ScraperController::class, 'test'])->name('scrapers.test');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    Route::get('/watchlist/{symbol}/chart', [WatchlistController::class, 'chart'])->name('watchlist.chart');

    // Settings
    Route::get('/settings/strategy', [StrategySettingsController::class, 'edit'])->name('settings.strategy');
    Route::post('/settings/strategy', [StrategySettingsController::class, 'update'])->name('settings.strategy.update');

    Route::get('/settings/broker', [BrokerSettingsController::class, 'edit'])->name('settings.broker');
    Route::post('/settings/broker', [BrokerSettingsController::class, 'update'])->name('settings.broker.update');

    Route::get('/settings/notifications', [NotificationSettingsController::class, 'edit'])->name('settings.notifications');
    Route::post('/settings/notifications', [NotificationSettingsController::class, 'update'])->name('settings.notifications.update');
    Route::post('/settings/notifications/{channel}/test', [NotificationSettingsController::class, 'test'])->name('settings.notifications.test');

    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('settings.profile');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/claude', [LogController::class, 'claude'])->name('logs.claude');
});
