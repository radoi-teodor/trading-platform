@props(['sentiment'])

@php
    $config = [
        'bullish' => ['color' => 'green', 'label' => 'Bullish'],
        'bearish' => ['color' => 'red', 'label' => 'Bearish'],
        'neutral' => ['color' => 'gray', 'label' => 'Neutral'],
    ];
    $c = $config[strtolower($sentiment)] ?? $config['neutral'];
@endphp

<x-badge :color="$c['color']" :text="$c['label']" />
