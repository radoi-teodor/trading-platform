<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StrategySettingsController extends Controller
{
    public function edit()
    {
        $settings = UserSetting::where('user_id', Auth::id())->first();

        if (!$settings) {
            $settings = UserSetting::create([
                'user_id' => Auth::id(),
                'risk_tolerance' => 'moderate',
                'max_daily_trades' => 10,
                'max_position_pct' => 20,
                'max_daily_loss_pct' => 5,
                'preferred_markets' => ['us_stocks'],
                'analysis_schedule' => 'premarket',
                'auto_execute' => false,
                'timezone' => 'America/New_York',
            ]);
        }

        return view('settings.strategy', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'risk_tolerance' => ['required', 'in:conservative,moderate,aggressive'],
            'max_daily_trades' => ['required', 'integer', 'min:1', 'max:100'],
            'max_position_pct' => ['required', 'numeric', 'min:1', 'max:100'],
            'max_daily_loss_pct' => ['required', 'numeric', 'min:0.5', 'max:50'],
            'preferred_markets' => ['nullable', 'array'],
            'preferred_markets.*' => ['string'],
            'custom_instructions' => ['nullable', 'string', 'max:2000'],
            'analysis_schedule' => ['required', 'string'],
            'auto_execute' => ['nullable'],
            'timezone' => ['required', 'string', 'max:50'],
        ]);

        $settings = UserSetting::where('user_id', Auth::id())->first();

        $settings->update([
            'risk_tolerance' => $validated['risk_tolerance'],
            'max_daily_trades' => $validated['max_daily_trades'],
            'max_position_pct' => $validated['max_position_pct'],
            'max_daily_loss_pct' => $validated['max_daily_loss_pct'],
            'preferred_markets' => $validated['preferred_markets'] ?? [],
            'custom_instructions' => $validated['custom_instructions'] ?? null,
            'analysis_schedule' => $validated['analysis_schedule'],
            'auto_execute' => $request->boolean('auto_execute'),
            'timezone' => $validated['timezone'],
        ]);

        return back()->with('success', 'Strategy settings updated successfully.');
    }
}
