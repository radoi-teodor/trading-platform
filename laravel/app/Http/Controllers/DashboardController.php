<?php

namespace App\Http\Controllers;

use App\Models\AgentHealth;
use App\Models\BotLog;
use App\Models\ClaudeAnalysis;
use App\Models\Portfolio;
use App\Models\Position;
use App\Models\PortfolioSnapshot;
use App\Models\Scraper;
use App\Models\StockPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $portfolio = Portfolio::where('user_id', $user->id)->first();

        $openPositions = $portfolio
            ? Position::where('portfolio_id', $portfolio->id)->open()->get()
            : collect();

        $positionsValue = $openPositions->sum(function ($pos) {
            return (int) round((float) $pos->quantity * $pos->current_price_cents);
        });

        $totalValue = $portfolio ? $portfolio->balance_cents + $positionsValue : 0;

        // Today's P&L from today's strategy or trades
        $todayStrategy = ClaudeAnalysis::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->latest()
            ->first();
        $todayPnl = $todayStrategy ? $todayStrategy->total_pnl_cents : 0;

        // Active agents
        $activeAgents = AgentHealth::where('user_id', $user->id)
            ->where('status', 'running')
            ->count();

        $activeScrapers = Scraper::where('user_id', $user->id)
            ->where('is_active', true)
            ->count();

        // Recent activity
        $recentActivity = BotLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Watchlist with latest prices
        $watchlist = $user->watchlists()->with('items')->first();
        $watchlistItems = $watchlist ? $watchlist->items : collect();

        $watchlistPrices = [];
        foreach ($watchlistItems as $item) {
            $latestPrice = StockPrice::where('symbol', $item->symbol)
                ->orderByDesc('date')
                ->first();
            $watchlistPrices[$item->symbol] = $latestPrice;
        }

        return view('dashboard.index', compact(
            'portfolio',
            'totalValue',
            'todayPnl',
            'openPositions',
            'activeAgents',
            'activeScrapers',
            'todayStrategy',
            'recentActivity',
            'watchlistItems',
            'watchlistPrices',
        ));
    }
}
