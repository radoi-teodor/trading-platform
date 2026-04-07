@props(['status'])

@php
    $colors = [
        'running' => 'bg-[#22c55e]',
        'stopped' => 'bg-[#ef4444]',
        'error' => 'bg-[#f59e0b]',
    ];
    $color = $colors[$status] ?? 'bg-gray-500';
@endphp

<span class="inline-block w-2.5 h-2.5 rounded-full {{ $color }}" title="{{ ucfirst($status) }}"></span>
