@props(['color' => 'blue', 'text'])

@php
    $colors = [
        'blue' => 'bg-blue-500/20 text-blue-400',
        'green' => 'bg-green-500/20 text-green-400',
        'red' => 'bg-red-500/20 text-red-400',
        'amber' => 'bg-amber-500/20 text-amber-400',
        'gray' => 'bg-gray-500/20 text-gray-400',
    ];
    $classes = $colors[$color] ?? $colors['gray'];
@endphp

<span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full {{ $classes }}">
    {{ $text }}
</span>
