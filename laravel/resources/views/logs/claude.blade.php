@extends('layouts.app')

@section('title', 'Claude Logs')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-white">Claude AI Logs</h2>
        <p class="text-sm text-gray-500 mt-1">History of all Claude analysis requests and responses</p>
    </div>
    <a href="{{ route('logs.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to System Logs</a>
</div>

<x-card>
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Outlook</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Prompt (truncated)</th>
                <th class="px-4 py-3">Response (truncated)</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($analyses as $log)
                <tr class="hover:bg-[#0f1117]/50 transition-colors">
                    <td class="px-4 py-3 text-gray-400 text-sm whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @if($log->market_outlook)
                            <x-sentiment-badge :sentiment="$log->market_outlook" />
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-badge
                            :color="$log->status === 'completed' ? 'green' : ($log->status === 'failed' ? 'red' : 'amber')"
                            :text="ucfirst($log->status ?? 'unknown')"
                        />
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs max-w-[200px] truncate" title="{{ $log->prompt_sent }}">
                        {{ Str::limit($log->prompt_sent ?? 'N/A', 80) }}
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs max-w-[200px] truncate" title="{{ $log->raw_response }}">
                        {{ Str::limit($log->raw_response ?? 'N/A', 80) }}
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('strategies.show', $log->id) }}" class="text-sm text-[#3b82f6] hover:text-blue-400 transition-colors">
                            View Full
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No Claude logs found.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $analyses->links('partials._pagination') }}
    </div>
</x-card>
@endsection
