<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// Timed jobs, run by `php artisan schedule:work` (docs/runbook.md, section 2).

$at = config('notifications.daily_report_at');
$zone = config('notifications.daily_report_timezone');

Schedule::command('report:daily')->dailyAt(is_string($at) ? $at : '08:00')->timezone(is_string($zone) ? $zone : 'Europe/Bucharest')->withoutOverlapping();
Schedule::command('platform:health --alert')->everyMinute()->withoutOverlapping();
