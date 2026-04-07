<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\WatchlistItem;
use App\Models\StockPrice;
use Illuminate\Http\Request;
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

        return back()->with('success', "{$symbol} added to watchlist.");
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
