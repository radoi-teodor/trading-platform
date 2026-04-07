@extends('layouts.app')

@section('title', 'Article Detail')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('news.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to News</a>
    </div>

    <x-card>
        <h2 class="text-2xl font-bold text-white mb-4">{{ $article->title }}</h2>

        <div class="flex flex-wrap items-center gap-3 mb-6 pb-4 border-b border-[#2a2d3a]">
            <span class="text-sm text-gray-400">{{ $article->source }}</span>
            <span class="text-gray-600">&middot;</span>
            <span class="text-sm text-gray-400">{{ $article->published_at ? $article->published_at->format('M d, Y \a\t H:i') : 'Unknown date' }}</span>

            @if($article->sentiment_label)
                <x-sentiment-badge :sentiment="$article->sentiment_label" />
            @endif

            @if($article->symbols && is_array($article->symbols))
                @foreach($article->symbols as $symbol)
                    <x-badge color="blue" :text="trim($symbol)" />
                @endforeach
            @endif
        </div>

        <div class="text-gray-300 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $article->content ?? 'No content available.' }}</div>

        @if($article->url)
            <div class="mt-6 pt-4 border-t border-[#2a2d3a]">
                <a href="{{ $article->url }}" target="_blank" rel="noopener noreferrer"
                   class="text-sm text-[#3b82f6] hover:text-blue-400 transition-colors">
                    View Original Article &rarr;
                </a>
            </div>
        @endif
    </x-card>
</div>
@endsection
