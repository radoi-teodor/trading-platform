<?php

namespace App\Http\Controllers;

use App\Models\ScrapedArticle;
use App\Models\Scraper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $scraperIds = Scraper::where('user_id', Auth::id())->pluck('id');

        $query = ScrapedArticle::whereIn('scraper_id', $scraperIds)
            ->with('scraper');

        if ($request->filled('symbol')) {
            $symbol = strtoupper($request->input('symbol'));
            $query->whereJsonContains('symbols', $symbol);
        }

        if ($request->filled('sentiment')) {
            $sentiment = $request->input('sentiment');
            match ($sentiment) {
                'positive' => $query->where('sentiment_score', '>', 0.2),
                'negative' => $query->where('sentiment_score', '<', -0.2),
                'neutral' => $query->whereBetween('sentiment_score', [-0.2, 0.2]),
                default => null,
            };
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('published_at', '<=', $request->input('to'));
        }

        $articles = $query->orderByDesc('published_at')->paginate(20)->withQueryString();

        return view('news.index', compact('articles'));
    }

    public function show($id)
    {
        $scraperIds = Scraper::where('user_id', Auth::id())->pluck('id');

        $article = ScrapedArticle::whereIn('scraper_id', $scraperIds)
            ->with('scraper')
            ->findOrFail($id);

        return view('news.show', compact('article'));
    }
}
