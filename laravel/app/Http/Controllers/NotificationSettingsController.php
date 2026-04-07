<?php

namespace App\Http\Controllers;

use App\Models\NotificationChannel;
use App\Models\NotificationRule;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    public function edit()
    {
        $channels = NotificationChannel::where('user_id', Auth::id())->get();
        $rules = NotificationRule::where('user_id', Auth::id())->get();

        return view('settings.notifications', compact('channels', 'rules'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'channels' => ['nullable', 'array'],
            'channels.*.type' => ['required', 'in:email,telegram,discord'],
            'channels.*.config' => ['required', 'array'],
            'channels.*.is_active' => ['nullable'],
            'rules' => ['nullable', 'array'],
            'rules.*.event_type' => ['required', 'string', 'max:100'],
            'rules.*.channels' => ['required', 'array'],
            'rules.*.is_active' => ['nullable'],
        ]);

        $userId = Auth::id();

        // Sync channels
        NotificationChannel::where('user_id', $userId)->delete();

        if (!empty($validated['channels'])) {
            foreach ($validated['channels'] as $channelData) {
                NotificationChannel::create([
                    'user_id' => $userId,
                    'type' => $channelData['type'],
                    'config' => $channelData['config'],
                    'is_active' => !empty($channelData['is_active']),
                ]);
            }
        }

        // Sync rules
        NotificationRule::where('user_id', $userId)->delete();

        if (!empty($validated['rules'])) {
            foreach ($validated['rules'] as $ruleData) {
                NotificationRule::create([
                    'user_id' => $userId,
                    'event_type' => $ruleData['event_type'],
                    'channels' => $ruleData['channels'],
                    'is_active' => !empty($ruleData['is_active']),
                ]);
            }
        }

        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function test($channelId)
    {
        $channel = NotificationChannel::where('user_id', Auth::id())
            ->findOrFail($channelId);

        $service = app(NotificationService::class);

        try {
            $service->dispatch(Auth::user(), 'test', [
                'message' => 'This is a test notification from your Trading Platform.',
                'channel_override' => $channel,
            ]);

            return back()->with('success', "Test notification sent via {$channel->type}.");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to send test notification: {$e->getMessage()}");
        }
    }
}
