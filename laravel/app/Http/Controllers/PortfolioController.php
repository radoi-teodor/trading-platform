<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Position;
use App\Models\Trade;
use App\Models\PortfolioSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->first();

        if (!$portfolio) {
            return view('portfolio.index', ['portfolio' => null]);
        }

        $openPositions = Position::where('portfolio_id', $portfolio->id)->open()->get();

        $positionsValue = $openPositions->sum(function ($pos) {
            return (int) round((float) $pos->quantity * $pos->current_price_cents);
        });

        $totalValue = $portfolio->balance_cents + $positionsValue;

        // Calculate daily P&L from snapshots
        $todaySnapshot = PortfolioSnapshot::where('portfolio_id', $portfolio->id)
            ->where('date', Carbon::today())
            ->first();
        $yesterdaySnapshot = PortfolioSnapshot::where('portfolio_id', $portfolio->id)
            ->where('date', Carbon::yesterday())
            ->first();
        $todayValue = $todaySnapshot ? $todaySnapshot->total_value_cents : $totalValue;
        $yesterdayValue = $yesterdaySnapshot ? $yesterdaySnapshot->total_value_cents : $portfolio->initial_balance_cents;
        $dailyPnl = $todayValue - $yesterdayValue;

        return view('portfolio.index', compact('portfolio', 'totalValue', 'positionsValue', 'dailyPnl', 'openPositions'));
    }

    public function positions(Request $request)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->first();

        if (!$portfolio) {
            return view('portfolio.positions', ['positions' => collect()]);
        }

        $query = Position::where('portfolio_id', $portfolio->id)->open();

        $sortBy = $request->input('sort', 'symbol');
        $sortDir = $request->input('dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $positions = $query->get();

        return view('portfolio.positions', compact('positions', 'sortBy', 'sortDir'));
    }

    public function trades(Request $request)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->first();

        if (!$portfolio) {
            return view('portfolio.trades', ['trades' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20)]);
        }

        $query = Trade::where('portfolio_id', $portfolio->id)
            ->with('order');

        if ($request->filled('symbol')) {
            $query->where('symbol', strtoupper($request->input('symbol')));
        }

        if ($request->filled('side')) {
            $query->where('side', $request->input('side'));
        }

        if ($request->filled('from')) {
            $query->whereDate('executed_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('executed_at', '<=', $request->input('to'));
        }

        $trades = $query->orderByDesc('executed_at')->paginate(20)->withQueryString();

        return view('portfolio.trades', compact('trades'));
    }

    public function performance()
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->first();

        if (!$portfolio) {
            return view('portfolio.performance', [
                'portfolio' => null,
                'totalReturn' => 0,
                'winRate' => 0,
                'avgWin' => 0,
                'avgLoss' => 0,
                'maxDrawdown' => 0,
                'dailyPnlData' => ['labels' => [], 'values' => []],
                'equityData' => ['labels' => [], 'values' => []],
            ]);
        }

        $snapshots = PortfolioSnapshot::where('portfolio_id', $portfolio->id)
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->orderBy('date')
            ->get();

        // Calculate daily P&L as difference between consecutive snapshots
        $dailyPnlLabels = [];
        $dailyPnlValues = [];
        $prevValue = null;
        foreach ($snapshots as $snap) {
            $pnl = $prevValue !== null ? ($snap->total_value_cents - $prevValue) / 100 : 0;
            $dailyPnlLabels[] = $snap->date->format('M d');
            $dailyPnlValues[] = round($pnl, 2);
            $prevValue = $snap->total_value_cents;
        }
        $dailyPnlData = ['labels' => $dailyPnlLabels, 'values' => $dailyPnlValues];

        $equityData = [
            'labels' => $snapshots->map(fn($s) => $s->date->format('M d'))->toArray(),
            'values' => $snapshots->map(fn($s) => round($s->total_value_cents / 100, 2))->toArray(),
        ];

        // Calculate performance metrics from trades
        $trades = Trade::where('portfolio_id', $portfolio->id)->get();
        $winTrades = $trades->where('pnl_cents', '>', 0);
        $lossTrades = $trades->where('pnl_cents', '<', 0);
        $closedTrades = $trades->where('pnl_cents', '!=', 0);

        $totalReturn = $portfolio->initial_balance_cents > 0
            ? (($portfolio->balance_cents - $portfolio->initial_balance_cents) / $portfolio->initial_balance_cents) * 100
            : 0;

        $winRate = $closedTrades->count() > 0
            ? ($winTrades->count() / $closedTrades->count()) * 100
            : 0;

        $avgWin = $winTrades->count() > 0 ? $winTrades->avg('pnl_cents') : 0;
        $avgLoss = $lossTrades->count() > 0 ? $lossTrades->avg('pnl_cents') : 0;

        // Max drawdown from equity curve
        $maxDrawdown = 0;
        $peak = 0;
        foreach ($snapshots as $snap) {
            if ($snap->total_value_cents > $peak) $peak = $snap->total_value_cents;
            if ($peak > 0) {
                $dd = (($peak - $snap->total_value_cents) / $peak) * 100;
                if ($dd > $maxDrawdown) $maxDrawdown = $dd;
            }
        }

        return view('portfolio.performance', compact(
            'portfolio', 'totalReturn', 'winRate', 'avgWin', 'avgLoss',
            'maxDrawdown', 'dailyPnlData', 'equityData'
        ));
    }
}
