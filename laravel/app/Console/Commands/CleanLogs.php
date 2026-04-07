<?php

namespace App\Console\Commands;

use App\Models\BotLog;
use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanLogs extends Command
{
    protected $signature = 'logs:clean {--days=90 : Delete logs older than this many days}';

    protected $description = 'Delete old bot logs and notification logs';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $this->info("Cleaning logs older than {$days} days (before {$cutoff->toDateString()})...");

        $botLogsDeleted = BotLog::where('created_at', '<', $cutoff)->delete();
        $this->info("Deleted {$botLogsDeleted} bot log entries.");

        $notifLogsDeleted = NotificationLog::where('sent_at', '<', $cutoff)->delete();
        $this->info("Deleted {$notifLogsDeleted} notification log entries.");

        $total = $botLogsDeleted + $notifLogsDeleted;
        $this->info("Total: {$total} records cleaned up.");

        return self::SUCCESS;
    }
}
