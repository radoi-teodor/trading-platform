<?php

namespace App\Http\Controllers;

use App\Models\BrokerCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrokerSettingsController extends Controller
{
    public function edit()
    {
        $credentials = BrokerCredential::where('user_id', Auth::id())->get();

        return view('settings.broker', compact('credentials'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'active_broker' => ['required', 'string', 'in:paper,alpaca,binance,xtb'],
            'alpaca_api_key' => ['nullable', 'string', 'max:255'],
            'alpaca_secret_key' => ['nullable', 'string', 'max:255'],
            'alpaca_base_url' => ['nullable', 'url', 'max:500'],
            'binance_api_key' => ['nullable', 'string', 'max:255'],
            'binance_secret_key' => ['nullable', 'string', 'max:255'],
            'binance_testnet' => ['nullable'],
            'xtb_user_id' => ['nullable', 'string', 'max:255'],
            'xtb_password' => ['nullable', 'string', 'max:255'],
            'xtb_demo' => ['nullable'],
        ]);

        $broker = $validated['active_broker'];
        $credentials = [];

        if ($broker === 'alpaca') {
            $credentials = [
                'api_key' => $validated['alpaca_api_key'] ?? '',
                'api_secret' => $validated['alpaca_secret_key'] ?? '',
                'base_url' => $validated['alpaca_base_url'] ?? 'https://paper-api.alpaca.markets',
            ];
        } elseif ($broker === 'binance') {
            $credentials = [
                'api_key' => $validated['binance_api_key'] ?? '',
                'api_secret' => $validated['binance_secret_key'] ?? '',
                'testnet' => $request->boolean('binance_testnet'),
            ];
        } elseif ($broker === 'xtb') {
            $credentials = [
                'user_id' => $validated['xtb_user_id'] ?? '',
                'password' => $validated['xtb_password'] ?? '',
                'demo' => $request->boolean('xtb_demo'),
            ];
        } elseif ($broker === 'paper') {
            $credentials = ['mode' => 'paper'];
        }

        // Deactivate all existing credentials for this user
        BrokerCredential::where('user_id', Auth::id())->update(['is_active' => false]);

        BrokerCredential::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'broker' => $broker,
            ],
            [
                'credentials' => $credentials,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Broker credentials saved securely.');
    }
}
