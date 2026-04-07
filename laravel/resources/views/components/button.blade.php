@props(['type' => 'submit', 'color' => 'blue', 'size' => 'md'])

@php
    $colorClasses = [
        'blue' => 'bg-[#3b82f6] hover:bg-blue-600 text-white',
        'green' => 'bg-[#22c55e] hover:bg-green-600 text-white',
        'red' => 'bg-[#ef4444] hover:bg-red-600 text-white',
        'amber' => 'bg-[#f59e0b] hover:bg-amber-600 text-white',
        'gray' => 'bg-gray-600 hover:bg-gray-700 text-white',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = ($colorClasses[$color] ?? $colorClasses['blue']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0f1117] focus:ring-[#3b82f6] $classes"]) }}>
    {{ $slot }}
</button>
