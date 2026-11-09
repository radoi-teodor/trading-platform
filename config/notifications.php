<?php

declare(strict_types=1);

// When the daily report is sent (D-77): a time of day in the given time zone.

return [
    'daily_report_at' => env('DAILY_REPORT_AT', '08:00'),
    'daily_report_timezone' => env('DAILY_REPORT_TIMEZONE', 'Europe/Bucharest'),
];
