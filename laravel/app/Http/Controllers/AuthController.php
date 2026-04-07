<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\Portfolio;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        UserSetting::create([
            'user_id' => $user->id,
            'risk_tolerance' => 'moderate',
            'max_daily_trades' => 10,
            'max_position_pct' => 20,
            'max_daily_loss_pct' => 5,
            'preferred_markets' => ['us_stocks'],
            'analysis_schedule' => 'premarket',
            'auto_execute' => false,
            'timezone' => 'America/New_York',
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'name' => 'Default Portfolio',
            'balance_cents' => 10000000, // $100,000
            'initial_balance_cents' => 10000000,
        ]);

        Watchlist::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
