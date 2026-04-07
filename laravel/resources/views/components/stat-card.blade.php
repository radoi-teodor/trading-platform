@props(['label', 'value', 'change' => null, 'changeType' => null])

<div class="rounded-lg border border-[#2a2d3a] bg-[#1a1d29] p-5">
    <p class="text-sm text-gray-400 mb-1">{{ $label }}</p>
    <p class="text-2xl font-bold text-white">{{ $value }}</p>
    @if($change !== null)
        <p class="text-sm mt-1 {{ $changeType === 'positive' ? 'text-[#22c55e]' : ($changeType === 'negative' ? 'text-[#ef4444]' : 'text-gray-400') }}">
            {{ $changeType === 'positive' ? '+' : '' }}{{ $change }}
        </p>
    @endif
    {{ $slot }}
</div>
