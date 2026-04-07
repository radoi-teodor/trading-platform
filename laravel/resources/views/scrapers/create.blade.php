@extends('layouts.app')

@section('title', 'Add Scraper')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('scrapers.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Scrapers</a>
    </div>

    <x-card title="Add New Scraper">
        <form method="POST" action="{{ route('scrapers.store') }}">
            @csrf

            <x-form-input name="name" label="Name" placeholder="e.g. Reuters Finance" :required="true" />
            <x-form-input name="url" label="URL" type="url" placeholder="https://example.com/feed" :required="true" />

            <x-form-select name="type" label="Type" :options="['html' => 'HTML', 'rss' => 'RSS']" :selected="old('type', 'rss')" />

            {{-- CSS Selectors --}}
            <div class="mt-6 mb-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <h4 class="text-sm font-medium text-gray-300 mb-1">CSS Selectors</h4>
                <p class="text-xs text-gray-500 mb-4">Only used for HTML type scrapers. Leave empty for RSS feeds.</p>

                <x-form-input name="title_selector" label="Title Selector" placeholder="e.g. h1.article-title" :value="old('title_selector')" />
                <x-form-input name="content_selector" label="Content Selector" placeholder="e.g. div.article-body" :value="old('content_selector')" />
                <x-form-input name="date_selector" label="Date Selector" placeholder="e.g. time.published" :value="old('date_selector')" />
                <x-form-input name="link_selector" label="Link Selector" placeholder="e.g. a.article-link" :value="old('link_selector')" />
            </div>

            <x-form-select name="schedule" label="Schedule" :options="[
                'every_15m' => 'Every 15 Minutes',
                'every_30m' => 'Every 30 Minutes',
                'hourly' => 'Hourly',
                'every_4h' => 'Every 4 Hours',
                'daily' => 'Daily',
                'manual' => 'Manual Only',
            ]" :selected="old('schedule', 'hourly')" />

            <x-form-input name="keyword_filters" label="Keyword Filters" placeholder="Comma-separated: earnings, fed, inflation" :value="old('keyword_filters')" />
            <x-form-input name="associated_symbols" label="Associated Symbols" placeholder="Comma-separated: AAPL, MSFT, GOOGL" :value="old('associated_symbols')" />

            <div class="mt-6">
                <x-button type="submit">Create Scraper</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
