<?php

namespace App\Console\Commands;

use App\Models\NotificationChannel;
use App\Models\Portfolio;
use App\Models\PortfolioSnapshot;
use App\Models\Trade;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailySummary extends Command
{
    protected $signature = 'notifications:daily-summary';

    protected $description = 'Send a daily trading summary to all users with active notification channels';

    public function handle(NotificationService $service): int
    {
        $usersWithChannels = User::whereHas('notificationChannels', function ($q) {
            $q->where('is_active', true);
        })->get();

        if ($usersWithChannels->isEmpty()) {
            $this->info('No users with active notification channels.');
            return self::SUCCESS;
        }

        $today = Carbon::today();
        $count = 0;

        foreach ($usersWithChannels as $user) {
            $portfolio = Portfolio::where('user_id', $user->id)->first();

            if (!$portfolio) {
                continue;
            }

            $todaySnapshot = PortfolioSnapshot::where('portfolio_id', $portfolio->id)
                ->where('date', $today)
                ->first();

            $yesterdaySnapshot = PortfolioSnapshot::where('portfolio_id', $portfolio->id)
                ->where('date', $today->copy()->subDay())
                ->first();

            $todayTrades = Trade::where('portfolio_id', $portfolio->id)
                ->whereDate('executed_at', $today)
                ->count();

            $totalValue = $todaySnapshot ? $todaySnapshot->total_value_cents : $portfolio->balance_cents;
            $yesterdayValue = $yesterdaySnapshot ? $yesterdaySnapshot->total_value_cents : $portfolio->initial_balance_cents;
            $dailyPnl = $totalValue - $yesterdayValue;

            $pnlFormatted = number_format($dailyPnl / 100, 2);
            $valueFormatted = number_format($totalValue / 100, 2);
            $pnlSign = $dailyPnl >= 0 ? '+' : '';

            $message = "Daily Summary ({$today->format('M d, Y')})\n"
                . "Portfolio Value: \${$valueFormatted}\n"
                . "Daily P&L: {$pnlSign}\${$pnlFormatted}\n"
                . "Trades Today: {$todayTrades}";

            try {
                $service->dispatch($user, 'daily_summary', [
                    'message' => $message,
                ]);
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed for {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info("Daily summary sent to {$count} user(s).");

        return self::SUCCESS;
    }
}
