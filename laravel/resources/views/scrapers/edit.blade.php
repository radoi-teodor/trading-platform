@extends('layouts.app')

@section('title', 'Edit Scraper')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('scrapers.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Scrapers</a>
    </div>

    <x-card title="Edit Scraper" :subtitle="$scraper->name">
        <form method="POST" action="{{ route('scrapers.update', $scraper) }}">
            @csrf
            @method('PUT')

            <x-form-input name="name" label="Name" :value="$scraper->name" :required="true" />
            <x-form-input name="url" label="URL" type="url" :value="$scraper->url" :required="true" />

            <x-form-select name="type" label="Type" :options="['html' => 'HTML', 'rss' => 'RSS']" :selected="old('type', $scraper->type)" />

            {{-- CSS Selectors --}}
            <div class="mt-6 mb-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <h4 class="text-sm font-medium text-gray-300 mb-1">CSS Selectors</h4>
                <p class="text-xs text-gray-500 mb-4">Only used for HTML type scrapers. Leave empty for RSS feeds.</p>

                <x-form-input name="title_selector" label="Title Selector" :value="old('title_selector', $scraper->selector_config['title'] ?? '')" placeholder="e.g. h1.article-title" />
                <x-form-input name="content_selector" label="Content Selector" :value="old('content_selector', $scraper->selector_config['content'] ?? '')" placeholder="e.g. div.article-body" />
                <x-form-input name="date_selector" label="Date Selector" :value="old('date_selector', $scraper->selector_config['date'] ?? '')" placeholder="e.g. time.published" />
                <x-form-input name="link_selector" label="Link Selector" :value="old('link_selector', $scraper->selector_config['link'] ?? '')" placeholder="e.g. a.article-link" />
            </div>

            <x-form-select name="schedule" label="Schedule" :options="[
                'every_15m' => 'Every 15 Minutes',
                'every_30m' => 'Every 30 Minutes',
                'hourly' => 'Hourly',
                'every_4h' => 'Every 4 Hours',
                'daily' => 'Daily',
                'manual' => 'Manual Only',
            ]" :selected="old('schedule', $scraper->schedule)" />

            <x-form-input name="keyword_filters" label="Keyword Filters" :value="old('keyword_filters', implode(', ', $scraper->keyword_filters ?? []))" placeholder="Comma-separated: earnings, fed, inflation" />
            <x-form-input name="associated_symbols" label="Associated Symbols" :value="old('associated_symbols', implode(', ', $scraper->associated_symbols ?? []))" placeholder="Comma-separated: AAPL, MSFT, GOOGL" />

            <div class="mt-6">
                <x-button type="submit">Update Scraper</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
