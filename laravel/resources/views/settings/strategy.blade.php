@extends('layouts.app')

@section('title', 'Strategy Settings')

@section('content')
<div class="max-w-2xl">
    <x-card title="Strategy Settings" subtitle="Configure how Claude analyzes markets and executes trades">
        <form method="POST" action="{{ route('settings.strategy.update') }}">
            @csrf

            <x-form-select name="risk_tolerance" label="Risk Tolerance" :options="[
                'conservative' => 'Conservative',
                'moderate' => 'Moderate',
                'aggressive' => 'Aggressive',
            ]" :selected="old('risk_tolerance', $settings->risk_tolerance ?? 'moderate')" />

            <x-form-input name="max_daily_trades" label="Max Daily Trades" type="number"
                          :value="old('max_daily_trades', $settings->max_daily_trades ?? 5)"
                          placeholder="e.g. 5" />

            <x-form-input name="max_position_pct" label="Max Position Size (%)" type="number"
                          :value="old('max_position_pct', $settings->max_position_pct ?? 10)"
                          placeholder="e.g. 10" />

            <x-form-input name="max_daily_loss_pct" label="Max Daily Loss (%)" type="number"
                          :value="old('max_daily_loss_pct', $settings->max_daily_loss_pct ?? 3)"
                          placeholder="e.g. 3" />

            {{-- Preferred Markets --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Preferred Markets</label>
                @php
                    $markets = ['us_stocks' => 'US Stocks', 'crypto' => 'Crypto', 'forex' => 'Forex', 'indices' => 'Indices', 'commodities' => 'Commodities'];
                    $selectedMarkets = old('preferred_markets', $settings->preferred_markets ?? []);
                    if (is_string($selectedMarkets)) $selectedMarkets = explode(',', $selectedMarkets);
                @endphp
                <div class="space-y-2">
                    @foreach($markets as $value => $label)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="preferred_markets[]" value="{{ $value }}"
                                   {{ in_array($value, $selectedMarkets) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                            <span class="text-sm text-gray-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('preferred_markets')
                    <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                @enderror
            </div>

            <x-form-select name="analysis_schedule" label="Analysis Schedule" :options="[
                '06:00' => '6:00 AM (Pre-Market)',
                '09:00' => '9:00 AM (Market Open)',
                '09:30' => '9:30 AM (US Market Open)',
                '12:00' => '12:00 PM (Midday)',
                '16:00' => '4:00 PM (Market Close)',
                'manual' => 'Manual Only',
            ]" :selected="old('analysis_schedule', $settings->analysis_schedule ?? '09:00')" />

            <x-form-select name="auto_execute" label="Auto-Execute Trades" :options="[
                '1' => 'Yes - Execute trades automatically',
                '0' => 'No - Require manual approval',
            ]" :selected="old('auto_execute', $settings->auto_execute ?? '0')" />

            <x-form-select name="timezone" label="Timezone" :options="[
                'America/New_York' => 'Eastern (ET)',
                'America/Chicago' => 'Central (CT)',
                'America/Denver' => 'Mountain (MT)',
                'America/Los_Angeles' => 'Pacific (PT)',
                'UTC' => 'UTC',
                'Europe/London' => 'London (GMT/BST)',
                'Europe/Berlin' => 'Central European (CET)',
                'Asia/Tokyo' => 'Japan (JST)',
                'Asia/Shanghai' => 'China (CST)',
                'Australia/Sydney' => 'Sydney (AEST)',
            ]" :selected="old('timezone', $settings->timezone ?? 'America/New_York')" />

            <x-form-textarea name="custom_instructions" label="Custom Instructions for Claude"
                             :value="old('custom_instructions', $settings->custom_instructions ?? '')"
                             :rows="6"
                             placeholder="Add any specific instructions for Claude's analysis. For example:
- Focus on tech sector momentum plays
- Avoid penny stocks under $5
- Prefer high-volume stocks with tight spreads
- Consider earnings dates before entering positions
- Weight recent Fed commentary heavily in analysis" />

            <div class="mt-6">
                <x-button type="submit">Save Settings</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
