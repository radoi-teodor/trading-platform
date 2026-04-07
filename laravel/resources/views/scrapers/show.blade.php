@extends('layouts.app')

@section('title', $scraper->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('scrapers.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Scrapers</a>
        <h2 class="text-xl font-bold text-white mt-2">{{ $scraper->name }}</h2>
    </div>
    <div class="flex items-center space-x-3">
        <x-badge :color="$scraper->type === 'rss' ? 'blue' : 'amber'" :text="strtoupper($scraper->type)" />
        <x-status-dot :status="$scraper->is_active ? 'running' : 'stopped'" />
        <span class="text-sm text-gray-400">{{ $scraper->is_active ? 'Active' : 'Inactive' }}</span>
        <a href="{{ route('scrapers.edit', $scraper) }}">
            <x-button type="button" size="sm">Edit</x-button>
        </a>
        <form method="POST" action="{{ route('scrapers.test', $scraper) }}" class="inline">
            @csrf
            <x-button type="submit" size="sm" color="amber">Test Connection</x-button>
        </form>
    </div>
</div>

{{-- Scraper Details --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card label="URL" :value="Str::limit($scraper->url, 30)" />
    <x-stat-card label="Schedule" :value="$scraper->schedule" />
    <x-stat-card label="Articles" :value="$scraper->articles_count ?? $articles->total()" />
    <x-stat-card label="Last Run" :value="$scraper->last_run_at ? $scraper->last_run_at->diffForHumans() : 'Never'" />
</div>

{{-- Articles --}}
<x-card title="Scraped Articles">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Source</th>
                <th class="px-4 py-3">Sentiment</th>
                <th class="px-4 py-3">Published</th>
                <th class="px-4 py-3 text-center">Fed to AI</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($articles as $article)
                <tr class="hover:bg-[#0f1117]/50 transition-colors cursor-pointer" onclick="window.location='{{ route('news.show', $article) }}'">
                    <td class="px-4 py-3 text-white font-medium max-w-[300px]">
                        <span class="line-clamp-2">{{ $article->title }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-sm">{{ $article->source }}</td>
                    <td class="px-4 py-3">
                        @if($article->sentiment_label)
                            <x-sentiment-badge :sentiment="$article->sentiment_label" />
                        @else
                            <span class="text-gray-500 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-sm whitespace-nowrap">
                        {{ $article->published_at ? $article->published_at->format('M d, H:i') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($article->fed_to_claude)
                            <span class="text-[#22c55e]">&#10003;</span>
                        @else
                            <span class="text-gray-600">&#10005;</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No articles scraped yet.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $articles->links('partials._pagination') }}
    </div>
</x-card>
@endsection
