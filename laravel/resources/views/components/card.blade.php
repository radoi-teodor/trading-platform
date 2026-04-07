@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-6']) }}>
    @if($title)
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
