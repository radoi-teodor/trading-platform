<?php

declare(strict_types=1);

namespace App\Web\Console;

use App\Models\User;
use App\Support\Clock\Clock;
use Illuminate\Console\Command;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

/**
 * Creates the one operator (D-5, docs/security.md section 3): the password is asked twice without echo, and the TOTP
 * secret is shown once, as a QR code with the key below it (D-86), and confirmed with a code from the authenticator
 * app before the account exists.
 */
final class CreateUser extends Command
{
    protected $signature = 'user:create {email : The operator\'s login}';

    protected $description = 'Create the operator, with a password and a TOTP authenticator';

    public function handle(TwoFactorAuthenticationProvider $totp, Clock $clock, TerminalQrCode $qrCode): int
    {
        $email = (string) $this->argument('email');

        if (User::query()->exists()) {
            $this->error('The operator already exists; Trading X has one (D-5).');

            return self::FAILURE;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error('The login is an email address.');

            return self::FAILURE;
        }

        $password = $this->secret('Password (hidden, at least 12 characters)');

        if (! is_string($password) || strlen($password) < 12 || $password !== $this->secret('The same password again')) {
            $this->error('The passwords are shorter than 12 characters or differ; nothing was created.');

            return self::FAILURE;
        }

        $secret = $totp->generateSecretKey();
        $this->line('Scan this code with your authenticator app (shown once):');
        $this->output->write($qrCode->render($totp->qrCodeUrl('Trading X', $email, $secret), $this->output->isDecorated()));
        $this->line('If the app cannot scan, type this key: '.$secret);
        $code = $this->ask('A code from the app');

        if (! is_string($code) || ! $totp->verify($secret, $code)) {
            $this->error('The code does not match; nothing was created.');

            return self::FAILURE;
        }

        $user = new User(['name' => 'Operator', 'email' => $email, 'password' => $password]);
        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([])),
            'two_factor_confirmed_at' => $clock->now(),
        ])->save();
        $this->info("The operator {$email} is created. Log in at /login.");

        return self::SUCCESS;
    }
}
