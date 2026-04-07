@extends('layouts.app')

@section('title', 'Logs')

@section('content')
{{-- Filters --}}
<x-card class="mb-6">
    <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Level</label>
            <select name="level"
                    class="px-3 py-2 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 text-sm focus:outline-none focus:border-[#3b82f6]">
                <option value="">All Levels</option>
                <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>Error</option>
                <option value="trade" {{ request('level') === 'trade' ? 'selected' : '' }}>Trade</option>
            </select>
        </div>
        <x-button type="submit" size="sm">Filter</x-button>
        <a href="{{ route('logs.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors py-2">Clear</a>
    </form>
</x-card>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold text-white">System Logs</h2>
    <a href="{{ route('logs.claude') }}" class="text-sm text-[#3b82f6] hover:text-blue-400 transition-colors">View Claude Logs &rarr;</a>
</div>

<x-card>
    <x-data-table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Time</th>
                <th class="px-4 py-3">Level</th>
                <th class="px-4 py-3">Message</th>
                <th class="px-4 py-3">Context</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($logs as $log)
                <tr class="hover:bg-[#0f1117]/50 transition-colors">
                    <td class="px-4 py-3 text-gray-400 text-sm whitespace-nowrap">{{ $log->created_at->format('M d H:i:s') }}</td>
                    <td class="px-4 py-3">
                        @php
                            $levelColor = match($log->level) {
                                'error' => 'red',
                                'warning' => 'amber',
                                'trade' => 'blue',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :color="$levelColor" :text="strtoupper($log->level)" />
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-sm">{{ $log->message }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs max-w-[200px] truncate" title="{{ json_encode($log->context) }}">
                        {{ $log->context ? Str::limit(json_encode($log->context), 80) : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No logs found.</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>

    <div class="mt-4">
        {{ $logs->links('partials._pagination') }}
    </div>
</x-card>
@endsection
