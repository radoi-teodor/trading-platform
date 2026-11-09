<?php

declare(strict_types=1);

use App\Http\Controllers\ReauthController;
use App\Livewire\AccountScreen;
use App\Livewire\ChartScreen;
use App\Livewire\SettingsScreen;
use App\Livewire\StrategiesScreen;
use Illuminate\Support\Facades\Route;

// Every screen needs the operator logged in with a TOTP code (docs/security.md, section 3). /up is in bootstrap/app.php.
Route::middleware('auth')->group(function (): void {
    Route::redirect('/', '/chart');
    Route::get('/chart', ChartScreen::class)->name('chart');
    Route::get('/strategies', StrategiesScreen::class)->name('strategies');
    Route::get('/account', AccountScreen::class)->name('account');
    Route::get('/settings', SettingsScreen::class)->name('settings');
    Route::get('/confirm', [ReauthController::class, 'show'])->name('reauth');
    Route::post('/confirm', [ReauthController::class, 'store'])->middleware('throttle:5,1');
});
