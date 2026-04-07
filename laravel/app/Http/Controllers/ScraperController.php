<?php

namespace App\Http\Controllers;

use App\Models\Scraper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ScraperController extends Controller
{
    public function index()
    {
        $scrapers = Scraper::where('user_id', Auth::id())
            ->withCount('articles')
            ->orderByDesc('created_at')
            ->get();

        return view('scrapers.index', compact('scrapers'));
    }

    public function create()
    {
        return view('scrapers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'type' => ['required', 'in:html,rss'],
            'title_selector' => ['nullable', 'string'],
            'content_selector' => ['nullable', 'string'],
            'date_selector' => ['nullable', 'string'],
            'link_selector' => ['nullable', 'string'],
            'keyword_filters' => ['nullable', 'string'],
            'associated_symbols' => ['nullable', 'string'],
            'schedule' => ['required', 'string', 'max:100'],
        ]);

        $selectorConfig = [];
        if ($request->filled('title_selector')) $selectorConfig['title'] = $validated['title_selector'];
        if ($request->filled('content_selector')) $selectorConfig['content'] = $validated['content_selector'];
        if ($request->filled('date_selector')) $selectorConfig['date'] = $validated['date_selector'];
        if ($request->filled('link_selector')) $selectorConfig['link'] = $validated['link_selector'];

        Scraper::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'url' => $validated['url'],
            'type' => $validated['type'],
            'selector_config' => $selectorConfig ?: [],
            'keyword_filters' => $request->filled('keyword_filters')
                ? array_map('trim', explode(',', $validated['keyword_filters']))
                : [],
            'associated_symbols' => $request->filled('associated_symbols')
                ? array_map('trim', explode(',', $validated['associated_symbols']))
                : [],
            'schedule' => $validated['schedule'],
            'is_active' => false,
            'agent_status' => 'stopped',
        ]);

        return redirect()->route('scrapers.index')->with('success', 'Scraper created successfully.');
    }

    public function show(Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        $articles = $scraper->articles()
            ->orderByDesc('published_at')
            ->paginate(20);

        return view('scrapers.show', compact('scraper', 'articles'));
    }

    public function edit(Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        return view('scrapers.edit', compact('scraper'));
    }

    public function update(Request $request, Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'type' => ['required', 'in:html,rss'],
            'title_selector' => ['nullable', 'string'],
            'content_selector' => ['nullable', 'string'],
            'date_selector' => ['nullable', 'string'],
            'link_selector' => ['nullable', 'string'],
            'keyword_filters' => ['nullable', 'string'],
            'associated_symbols' => ['nullable', 'string'],
            'schedule' => ['required', 'string', 'max:100'],
        ]);

        $selectorConfig = [];
        if ($request->filled('title_selector')) $selectorConfig['title'] = $validated['title_selector'];
        if ($request->filled('content_selector')) $selectorConfig['content'] = $validated['content_selector'];
        if ($request->filled('date_selector')) $selectorConfig['date'] = $validated['date_selector'];
        if ($request->filled('link_selector')) $selectorConfig['link'] = $validated['link_selector'];

        $scraper->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'type' => $validated['type'],
            'selector_config' => $selectorConfig ?: $scraper->selector_config,
            'keyword_filters' => $request->filled('keyword_filters')
                ? array_map('trim', explode(',', $validated['keyword_filters']))
                : $scraper->keyword_filters,
            'associated_symbols' => $request->filled('associated_symbols')
                ? array_map('trim', explode(',', $validated['associated_symbols']))
                : $scraper->associated_symbols,
            'schedule' => $validated['schedule'],
        ]);

        return redirect()->route('scrapers.show', $scraper)->with('success', 'Scraper updated successfully.');
    }

    public function destroy(Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        $scraper->articles()->delete();
        $scraper->delete();

        return redirect()->route('scrapers.index')->with('success', 'Scraper deleted successfully.');
    }

    public function toggle(Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        $scraper->update([
            'is_active' => !$scraper->is_active,
            'agent_status' => !$scraper->is_active ? 'running' : 'stopped',
        ]);

        $status = $scraper->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Scraper {$status} successfully.");
    }

    public function test(Scraper $scraper)
    {
        $this->authorizeAccess($scraper);

        try {
            $response = Http::timeout(15)->get($scraper->url);

            if ($response->successful()) {
                $contentLength = strlen($response->body());
                return back()->with('success', "Connection successful. Received {$contentLength} bytes from {$scraper->url}.");
            }

            return back()->with('error', "Failed to fetch URL. HTTP status: {$response->status()}");
        } catch (\Exception $e) {
            return back()->with('error', "Connection failed: {$e->getMessage()}");
        }
    }

    private function authorizeAccess(Scraper $scraper): void
    {
        if ($scraper->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
