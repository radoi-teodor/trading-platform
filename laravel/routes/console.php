<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('notifications:daily-summary')->dailyAt('17:00');
Schedule::command('logs:clean --days=90')->weekly();
Schedule::command('agents:health')->everyFiveMinutes();
