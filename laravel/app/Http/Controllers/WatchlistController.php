<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\WatchlistItem;
use App\Models\StockPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlist = Watchlist::where('user_id', Auth::id())
            ->with('items')
            ->first();

        $items = $watchlist ? $watchlist->items : collect();

        $prices = [];
        foreach ($items as $item) {
            $latestPrice = StockPrice::where('symbol', $item->symbol)
                ->orderByDesc('date')
                ->first();
            $prices[$item->symbol] = $latestPrice;
        }

        return view('watchlist.index', compact('watchlist', 'items', 'prices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'symbol' => ['required', 'string', 'max:10'],
        ]);

        $watchlist = Watchlist::where('user_id', Auth::id())->first();

        if (!$watchlist) {
            $watchlist = Watchlist::create([
                'user_id' => Auth::id(),
            ]);
        }

        $symbol = strtoupper(trim($validated['symbol']));

        $exists = WatchlistItem::where('watchlist_id', $watchlist->id)
            ->where('symbol', $symbol)
            ->exists();

        if ($exists) {
            return back()->with('error', "{$symbol} is already in your watchlist.");
        }

        WatchlistItem::create([
            'watchlist_id' => $watchlist->id,
            'symbol' => $symbol,
        ]);

        // Auto-backfill 90 days of price data if none exists
        $hasData = StockPrice::where('symbol', $symbol)->exists();
        if (!$hasData) {
            try {
                $projectRoot = dirname(base_path());
                $agentsDir = $projectRoot . DIRECTORY_SEPARATOR . 'agents';
                $script = $agentsDir . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'fetch_prices.py';
                $venvPython = $agentsDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR
                    . (PHP_OS_FAMILY === 'Windows' ? 'Scripts' : 'bin')
                    . DIRECTORY_SEPARATOR . (PHP_OS_FAMILY === 'Windows' ? 'python.exe' : 'python');
                $python = file_exists($venvPython) ? $venvPython : 'python';

                $cmd = '"' . $python . '" "' . $script . '" ' . $symbol . ' 90';
                $result = \Illuminate\Support\Facades\Process::timeout(120)->run($cmd);
                $output = $result->output();

                $jsonStart = strpos($output, '[');
                if ($jsonStart !== false) {
                    $data = json_decode(substr($output, $jsonStart), true);
                    if (is_array($data) && !empty($data)) {
                        $count = 0;
                        foreach ($data as $row) {
                            StockPrice::updateOrCreate(
                                ['symbol' => $symbol, 'date' => $row['date']],
                                [
                                    'open' => (int) round($row['open'] * 100),
                                    'high' => (int) round($row['high'] * 100),
                                    'low' => (int) round($row['low'] * 100),
                                    'close' => (int) round($row['close'] * 100),
                                    'volume' => (int) ($row['volume'] ?? 0),
                                ]
                            );
                            $count++;
                        }
                        return back()->with('success', "{$symbol} added. Loaded {$count} days of price data.");
                    }
                }
                return back()->with('success', "{$symbol} added.")->with('warning', 'Could not load historical data.');
            } catch (\Exception $e) {
                return back()->with('success', "{$symbol} added.")->with('warning', 'Price data: ' . $e->getMessage());
            }
        }

        return back()->with('success', "{$symbol} added to watchlist.");
    }

    public function chart($symbol)
    {
        $symbol = strtoupper($symbol);

        // Normalize symbol for lookup: BTCUSDT -> BTC (same as ClaudeStrategyController::tradeChart)
        $lookupSymbol = $symbol;
        foreach (['USDT', 'USDC', 'BUSD', '-USD', '-USDT'] as $suffix) {
            if (str_ends_with($lookupSymbol, $suffix)) {
                $lookupSymbol = substr($lookupSymbol, 0, -strlen($suffix));
                break;
            }
        }

        // Try exact match first, then normalized
        $prices = StockPrice::where('symbol', $symbol)
            ->orderBy('date')
            ->get();

        if ($prices->isEmpty() && $lookupSymbol !== $symbol) {
            $prices = StockPrice::where('symbol', $lookupSymbol)
                ->orderBy('date')
                ->get();
        }

        $chartData = $prices->map(function ($p) {
            return [
                'date' => $p->date->format('Y-m-d'),
                'open' => $p->open / 100,
                'high' => $p->high / 100,
                'low' => $p->low / 100,
                'close' => $p->close / 100,
                'volume' => $p->volume,
            ];
        })->values()->toArray();

        $latestPrice = $prices->last();
        $previousPrice = $prices->count() >= 2 ? $prices[$prices->count() - 2] : null;
        $change = $latestPrice && $previousPrice
            ? (($latestPrice->close - $previousPrice->close) / $previousPrice->close) * 100
            : 0;

        // Get executed trades for this symbol
        $executedTrades = \App\Models\Trade::where('symbol', $symbol)
            ->whereHas('portfolio', fn($q) => $q->where('user_id', Auth::id()))
            ->orderBy('executed_at')
            ->get()
            ->map(fn($t) => [
                'time' => $t->executed_at->format('Y-m-d'),
                'side' => $t->side,
                'price' => $t->price_cents / 100,
                'quantity' => $t->quantity,
            ])->values()->toArray();

        // Get planned trades for this symbol (from active strategies)
        $plannedTrades = \App\Models\ClaudeAnalysisTrade::where('symbol', $symbol)
            ->whereHas('claudeAnalysis', fn($q) => $q->where('user_id', Auth::id())->where('status', 'active'))
            ->where('status', 'pending')
            ->get()
            ->map(fn($t) => [
                'entry_price' => $t->entry_price_cents ? $t->entry_price_cents / 100 : null,
                'stop_loss' => $t->stop_loss_cents ? $t->stop_loss_cents / 100 : null,
                'take_profit' => $t->take_profit_cents ? $t->take_profit_cents / 100 : null,
                'action' => $t->action,
                'quantity' => $t->quantity,
            ])->values()->toArray();

        return view('watchlist.chart', compact('symbol', 'chartData', 'latestPrice', 'change', 'executedTrades', 'plannedTrades'));
    }

    public function destroy($id)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())->first();

        if (!$watchlist) {
            abort(404);
        }

        $item = WatchlistItem::where('watchlist_id', $watchlist->id)
            ->findOrFail($id);

        $symbol = $item->symbol;
        $item->delete();

        return back()->with('success', "{$symbol} removed from watchlist.");
    }
}
