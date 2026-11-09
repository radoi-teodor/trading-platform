<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Models\User;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Hashing\Hasher;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

/**
 * Re-authentication on the command line (docs/security.md, section 3): the operator's password and a fresh code from
 * their authenticator, asked without echo, as the web asks before anything that raises risk.
 */
trait ConfirmsOperator
{
    private function operatorConfirms(TwoFactorAuthenticationProvider $totp, Hasher $hasher, Encrypter $encrypter): bool
    {
        $password = $this->secret('Password (hidden)');
        $code = $this->ask('Code from your authenticator');
        $user = User::query()->orderBy('id')->first();
        $secret = $user !== null && is_string($user->two_factor_secret) ? $encrypter->decrypt($user->two_factor_secret) : null;

        if ($user === null || ! is_string($password) || ! $hasher->check($password, $user->password) || ! is_string($secret) || ! is_string($code) || ! $totp->verify($secret, $code)) {
            $this->error('The password or the code does not match; nothing was done.');

            return false;
        }

        return true;
    }
}
