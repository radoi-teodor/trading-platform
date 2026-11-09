<?php

declare(strict_types=1);

namespace App\Notifications;

/** Where a setting's value comes from (D-75): the screen names it, so the fallback to .env is never silent. */
enum SettingSource: string
{
    case App = 'set in the app';
    case Env = 'from .env';
    case None = 'not set';
}
