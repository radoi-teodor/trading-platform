<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

/**
 * Re-authentication for actions that change what an account may do (docs/security.md, section 3): the password and a
 * fresh TOTP code, valid for 10 minutes in this session.
 */
final class ReauthController
{
    public const string SESSION_KEY = 'reauth_at';

    public const int SECONDS = 600;

    public function show(): View
    {
        return view('auth.confirm');
    }

    public function store(Request $request, TwoFactorAuthenticationProvider $totp): RedirectResponse
    {
        $user = $request->user();
        $password = $request->input('password');
        $code = $request->input('code');
        $secret = $user instanceof User && is_string($user->two_factor_secret) ? decrypt($user->two_factor_secret) : null;

        if (! $user instanceof User || ! is_string($password) || ! Hash::check($password, $user->password) || ! is_string($secret) || ! is_string($code) || ! $totp->verify($secret, $code)) {
            throw ValidationException::withMessages(['password' => 'The password or the code does not match.']);
        }

        $request->session()->put(self::SESSION_KEY, time());

        return redirect()->intended(route('account'));
    }

    public static function fresh(): bool
    {
        $at = session()->get(self::SESSION_KEY);

        return is_int($at) && time() - $at < self::SECONDS;
    }
}
