@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
<div class="max-w-2xl">
    <x-card title="Notification Settings" subtitle="Configure how you receive alerts and updates">

        {{-- Existing Channels --}}
        @if($channels->count() > 0)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-300 mb-3">Active Channels</h4>
                @foreach($channels as $channel)
                    <div class="flex items-center justify-between mb-2 p-3 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                        <div class="flex items-center space-x-3">
                            <x-badge :color="$channel->is_active ? 'green' : 'gray'" :text="ucfirst($channel->type)" />
                            <span class="text-sm text-gray-300">{{ $channel->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <form method="POST" action="{{ route('settings.notifications.test', $channel->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-[#3b82f6] hover:text-blue-400 transition-colors">Send Test</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('settings.notifications.update') }}">
            @csrf

            @php
                $emailChannel = $channels->firstWhere('type', 'email');
                $telegramChannel = $channels->firstWhere('type', 'telegram');
                $discordChannel = $channels->firstWhere('type', 'discord');
            @endphp

            {{-- Email Channel --}}
            <div class="mb-6 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-white">Email Notifications</h4>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="channels[email][is_active]" value="1"
                               {{ old('channels.email.is_active', $emailChannel && $emailChannel->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <span class="text-sm text-gray-300">Enabled</span>
                    </label>
                </div>
                <input type="hidden" name="channels[email][type]" value="email">
                <input type="hidden" name="channels[email][config][email]" value="{{ Auth::user()->email }}">
                <p class="text-xs text-gray-500">Notifications will be sent to your account email.</p>
            </div>

            {{-- Telegram Channel --}}
            <div class="mb-6 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-white">Telegram Notifications</h4>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="channels[telegram][is_active]" value="1"
                               {{ old('channels.telegram.is_active', $telegramChannel && $telegramChannel->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <span class="text-sm text-gray-300">Enabled</span>
                    </label>
                </div>
                <input type="hidden" name="channels[telegram][type]" value="telegram">
                <x-form-input name="channels[telegram][config][bot_token]" label="Bot Token" :value="old('channels.telegram.config.bot_token', $telegramChannel->config['bot_token'] ?? '')" placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" />
                <x-form-input name="channels[telegram][config][chat_id]" label="Chat ID" :value="old('channels.telegram.config.chat_id', $telegramChannel->config['chat_id'] ?? '')" placeholder="e.g. -1001234567890" />
            </div>

            {{-- Discord Channel --}}
            <div class="mb-6 p-4 bg-[#0f1117] border border-[#2a2d3a] rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-white">Discord Notifications</h4>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="channels[discord][is_active]" value="1"
                               {{ old('channels.discord.is_active', $discordChannel && $discordChannel->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#0f1117] border-[#2a2d3a] text-[#3b82f6] focus:ring-[#3b82f6]">
                        <span class="text-sm text-gray-300">Enabled</span>
                    </label>
                </div>
                <input type="hidden" name="channels[discord][type]" value="discord">
                <x-form-input name="channels[discord][config][webhook_url]" label="Webhook URL" :value="old('channels.discord.config.webhook_url', $discordChannel->config['webhook_url'] ?? '')" placeholder="https://discord.com/api/webhooks/..." />
            </div>

            <div class="mt-6">
                <x-button type="submit">Save Notification Settings</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
