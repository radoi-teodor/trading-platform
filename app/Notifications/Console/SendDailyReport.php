<?php

declare(strict_types=1);

namespace App\Notifications\Console;

use App\Execution\Models\Account;
use App\Notifications\DailyReport;
use App\Notifications\SendTelegram;
use App\Notifications\TelegramSettings;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Config\Repository;

/** Sends each account's daily report to Telegram through the notifications queue (D-77). */
final class SendDailyReport extends Command
{
    protected $signature = 'report:daily {--print : Print the reports instead of sending them}';

    protected $description = 'Send the daily report of every account to Telegram';

    public function handle(DailyReport $report, TelegramSettings $settings, Dispatcher $bus, Repository $config): int
    {
        if (! $this->option('print') && ! $settings->wants('daily_report')) {
            $this->line('The daily report is off (Settings, Notifications).');

            return self::SUCCESS;
        }

        $connection = $config->get('services.telegram.queue_connection');

        foreach (Account::query()->orderBy('name')->get() as $account) {
            $text = $report->forAccount($account);

            if ($this->option('print')) {
                $this->line($text."\n");

                continue;
            }

            $bus->dispatch((new SendTelegram($text))->onConnection(is_string($connection) ? $connection : null)->onQueue('notifications'));
            $this->line("Queued the report of {$account->name}.");
        }

        return self::SUCCESS;
    }
}
