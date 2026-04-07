@extends('layouts.app')

@section('title', 'Broker Settings')

@section('content')
@php
    $activeCredential = $credentials->firstWhere('is_active', true);
    $activeBroker = $activeCredential ? $activeCredential->broker : 'paper';
@endphp
<div class="max-w-2xl">
    <x-card title="Broker Settings" subtitle="Configure your trading broker connection">
        <form method="POST" action="{{ route('settings.broker.update') }}">
            @csrf

            <x-form-select name="active_broker" label="Active Broker" :options="[
                'paper' => 'Paper Trading (Simulated)',
                'alpaca' => 'Alpaca',
                'binance' => 'Binance',
                'xtb' => 'XTB',
            ]" :selected="old('active_broker', $activeBroker)" />

            {{-- Paper Trading --}}
            <div id="broker-paper" class="broker-fields mt-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <p class="text-sm text-gray-400">Paper trading requires no credentials. All trades are simulated.</p>
            </div>

            {{-- Alpaca --}}
            <div id="broker-alpaca" class="broker-fields mt-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg" style="display:none;">
                <h4 class="text-sm font-medium text-gray-300 mb-3">Alpaca Credentials</h4>
                <x-form-input name="alpaca_api_key" label="API Key" :value="old('alpaca_api_key')" placeholder="Your Alpaca API key" />
                <x-form-input name="alpaca_secret_key" label="Secret Key" type="password" :value="old('alpaca_secret_key')" placeholder="Your Alpaca secret key" />
                <x-form-input name="alpaca_base_url" label="Base URL" :value="old('alpaca_base_url', 'https://paper-api.alpaca.markets')" placeholder="https://paper-api.alpaca.markets" />
            </div>

            {{-- Binance --}}
            <div id="broker-binance" class="broker-fields mt-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg" style="display:none;">
                <h4 class="text-sm font-medium text-gray-300 mb-3">Binance Credentials</h4>
                <x-form-input name="binance_api_key" label="API Key" :value="old('binance_api_key')" placeholder="Your Binance API key" />
                <x-form-input name="binance_secret_key" label="Secret Key" type="password" :value="old('binance_secret_key')" placeholder="Your Binance secret key" />
                <div class="mb-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="binance_testnet" value="1"
                               {{ old('binance_testnet') ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <span class="text-sm text-gray-300">Use Testnet</span>
                    </label>
                </div>
            </div>

            {{-- XTB --}}
            <div id="broker-xtb" class="broker-fields mt-4 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg" style="display:none;">
                <h4 class="text-sm font-medium text-gray-300 mb-3">XTB Credentials</h4>
                <x-form-input name="xtb_user_id" label="User ID" :value="old('xtb_user_id')" placeholder="Your XTB user ID" />
                <x-form-input name="xtb_password" label="Password" type="password" :value="old('xtb_password')" placeholder="Your XTB password" />
                <div class="mb-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="xtb_demo" value="1"
                               {{ old('xtb_demo', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <span class="text-sm text-gray-300">Use Demo Account</span>
                    </label>
                </div>
            </div>

            <div class="mt-6">
                <x-button type="submit">Save Broker Settings</x-button>
            </div>
        </form>
    </x-card>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var brokerSelect = document.getElementById('active_broker');
    function showBrokerFields() {
        var allFields = document.querySelectorAll('.broker-fields');
        for (var i = 0; i < allFields.length; i++) {
            allFields[i].style.display = 'none';
        }
        var selected = document.getElementById('broker-' + brokerSelect.value);
        if (selected) selected.style.display = 'block';
    }
    brokerSelect.addEventListener('change', showBrokerFields);
    showBrokerFields();
});
</script>
@endsection
