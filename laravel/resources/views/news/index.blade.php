@extends('layouts.app')

@section('title', 'News')

@section('content')
{{-- Filters --}}
<x-card class="mb-6">
    <form method="GET" action="{{ route('news.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Symbol</label>
            <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="e.g. AAPL"
                   class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">Sentiment</label>
            <select name="sentiment"
                    class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
                <option value="">All</option>
                <option value="bullish" {{ request('sentiment') === 'bullish' ? 'selected' : '' }}>Bullish</option>
                <option value="bearish" {{ request('sentiment') === 'bearish' ? 'selected' : '' }}>Bearish</option>
                <option value="neutral" {{ request('sentiment') === 'neutral' ? 'selected' : '' }}>Neutral</option>
            </select>
        </div>
        <x-button type="submit" size="sm">Filter</x-button>
        <a href="{{ route('news.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors py-2">Clear</a>
    </form>
</x-card>

<x-card title="News Articles">
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Source</th>
                <th class="px-4 py-3">Sentiment</th>
                <th class="px-4 py-3">Symbols</th>
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
                    <td class="px-4 py-3">
                        @if($article->symbols && is_array($article->symbols) && count($article->symbols) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($article->symbols as $symbol)
                                    <x-badge color="blue" :text="trim($symbol)" />
                                @endforeach
                            </div>
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
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No articles found.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $articles->links('partials._pagination') }}
    </div>
</x-card>
@endsection
