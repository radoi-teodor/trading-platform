<?php

namespace App\Http\Controllers;

use App\Models\ClaudeAnalysis;
use App\Models\ClaudeAnalysisTrade;
use App\Models\StockPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class ClaudeStrategyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ClaudeAnalysis::where('user_id', $user->id)
            ->orderByDesc('date');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->input('to'));
        }

        if ($request->filled('outlook')) {
            $query->where('market_outlook', $request->input('outlook'));
        }

        $analyses = $query->paginate(15)->withQueryString();

        $settings = \App\Models\UserSetting::where('user_id', $user->id)->first();
        $portfolio = \App\Models\Portfolio::where('user_id', $user->id)->first();
        $openPositions = $portfolio
            ? \App\Models\Position::where('portfolio_id', $portfolio->id)->whereNull('closed_at')->get()
            : collect();
        $positionsValue = $openPositions->sum(fn($p) => (int) round((float) $p->quantity * $p->current_price_cents));
        $totalEquity = $portfolio ? $portfolio->balance_cents + $positionsValue : 0;
        $brokerCred = \App\Models\BrokerCredential::where('user_id', $user->id)->where('is_active', true)->first();

        return view('strategies.index', compact(
            'analyses', 'settings', 'portfolio', 'openPositions', 'totalEquity', 'brokerCred'
        ));
    }

    public function show($id)
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->with(['analysisTrades.executedOrder', 'analysisCloses', 'analysisAlerts.resultOrder'])
            ->findOrFail($id);

        return view('strategies.show', compact('analysis'));
    }

    public function today()
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->with(['analysisTrades.executedOrder', 'analysisCloses', 'analysisAlerts.resultOrder'])
            ->latest('created_at')
            ->first();

        return view('strategies.today', compact('analysis'));
    }

    public function trigger(Request $request)
    {
        $user = Auth::user();

        // Save strategy style preference
        $style = $request->input('strategy_style', 'day_trading');
        $customNotes = $request->input('custom_notes', '');
        $includeScraperData = $request->boolean('include_scraper_data', true);

        $settings = \App\Models\UserSetting::where('user_id', $user->id)->first();
        if ($settings) {
            $settings->update(['strategy_style' => $style]);
        }

        // Mark previous active/pending strategies as completed
        ClaudeAnalysis::where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->update(['status' => 'completed']);

        // Create pending analysis
        $analysis = ClaudeAnalysis::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'market_outlook' => 'neutral',
            'confidence' => 0,
            'reasoning' => '',
            'strategy_json' => '{}',
            'status' => 'pending',
            'strategy_style' => $style,
            'trades_planned' => 0,
            'prompt_sent' => 'Pending — ' . $style . ' analysis triggered.',
            'raw_response' => '',
            'created_at' => now(),
        ]);

        // Publish to Redis
        try {
            Redis::publish('trading:commands', json_encode([
                'command' => 'trigger_analysis',
                'user_id' => $user->id,
                'analysis_id' => $analysis->id,
                'strategy_style' => $style,
                'custom_notes' => $customNotes,
                'include_scraper_data' => $includeScraperData,
                'timestamp' => now()->toISOString(),
            ]));
        } catch (\Exception $e) {}

        return redirect('/strategies')->with('success', 'Analysis triggered (' . ucfirst(str_replace('_', ' ', $style)) . '). Refresh to see results.');
    }

    public function tradeChart($analysisId, $tradeId)
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->findOrFail($analysisId);

        $trade = ClaudeAnalysisTrade::where('claude_analysis_id', $analysis->id)
            ->findOrFail($tradeId);

        // Normalize symbol: BTCUSDT → BTC, ETH-USD → ETH (to match stock_prices)
        $symbol = $trade->symbol;
        $lookupSymbol = strtoupper($symbol);
        foreach (['USDT', 'USDC', 'BUSD', '-USD', '-USDT'] as $suffix) {
            if (str_ends_with($lookupSymbol, $suffix)) {
                $lookupSymbol = substr($lookupSymbol, 0, -strlen($suffix));
                break;
            }
        }

        // Try exact match first, then normalized
        $chartData = StockPrice::where('symbol', $symbol)
            ->where('date', '>=', Carbon::today()->subDays(90))
            ->orderBy('date')
            ->get();

        if ($chartData->isEmpty()) {
            $chartData = StockPrice::where('symbol', $lookupSymbol)
                ->where('date', '>=', Carbon::today()->subDays(90))
                ->orderBy('date')
                ->get();
        }

        $chartData = $chartData->map(fn ($p) => [
                'time'   => $p->date->format('Y-m-d'),
                'open'   => $p->open / 100,
                'high'   => $p->high / 100,
                'low'    => $p->low / 100,
                'close'  => $p->close / 100,
                'volume' => $p->volume,
            ])
            ->values()
            ->toArray();

        return view('strategies.trade-chart', compact('trade', 'analysis', 'chartData', 'symbol'));
    }

    public function plannedTrades()
    {
        // Get ALL pending/active trades from ALL strategies for this user
        $trades = ClaudeAnalysisTrade::whereHas('claudeAnalysis', function ($q) {
                $q->where('user_id', Auth::id())
                  ->whereIn('status', ['active', 'pending']);
            })
            ->with('claudeAnalysis')
            ->orderByDesc('created_at')
            ->get();

        return view('strategies.planned-trades', compact('trades'));
    }

    public function cancel($id)
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $analysis->update([
            'status' => 'failed',
            'reasoning' => 'Cancelled by user.',
        ]);

        return back()->with('success', 'Analysis cancelled.');
    }
}
