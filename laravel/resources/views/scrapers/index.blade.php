@extends('layouts.app')

@section('title', 'Scrapers')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-white">News Scrapers</h2>
    <a href="{{ route('scrapers.create') }}">
        <x-button type="button">Add Scraper</x-button>
    </a>
</div>

<x-card>
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">URL</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Schedule</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Articles</th>
                <th class="px-4 py-3">Last Run</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($scrapers as $scraper)
                <tr class="hover:bg-[#0f1117]/50 transition-colors">
                    <td class="px-4 py-3 text-white font-medium">{{ $scraper->name }}</td>
                    <td class="px-4 py-3 text-gray-400 text-sm max-w-[200px] truncate" title="{{ $scraper->url }}">{{ $scraper->url }}</td>
                    <td class="px-4 py-3">
                        <x-badge :color="$scraper->type === 'rss' ? 'blue' : 'amber'" :text="strtoupper($scraper->type)" />
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-sm">{{ $scraper->schedule ?? 'Manual' }}</td>
                    <td class="px-4 py-3">
                        <x-status-dot :status="$scraper->is_active ? 'running' : 'stopped'" />
                        <span class="text-sm text-gray-400 ml-1">{{ $scraper->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-300">{{ $scraper->articles_count ?? 0 }}</td>
                    <td class="px-4 py-3 text-gray-400 text-sm whitespace-nowrap">
                        {{ $scraper->last_run_at ? $scraper->last_run_at->diffForHumans() : 'Never' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('scrapers.edit', $scraper) }}" class="text-sm text-[#3b82f6] hover:text-blue-400 transition-colors">Edit</a>

                            <form method="POST" action="{{ route('scrapers.toggle', $scraper) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm {{ $scraper->is_active ? 'text-[#f59e0b] hover:text-amber-400' : 'text-[#22c55e] hover:text-green-400' }} transition-colors">
                                    {{ $scraper->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('scrapers.destroy', $scraper) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-[#ef4444] hover:text-red-400 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this scraper?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">No scrapers configured.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>
</x-card>
@endsection
