<?php

declare(strict_types=1);

use App\Models\User;
use Pest\TestSuite;
use Tests\TestCase;

// The operator and the running test for the web tests (Feature/Web).
const OPERATOR_SECRET = 'JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP';

function web(): TestCase
{
    $test = TestSuite::getInstance()->test;

    return $test instanceof TestCase ? $test : throw new LogicException('Runs inside a test.');
}

function operator(): User
{
    $user = new User(['name' => 'Operator', 'email' => 'op@example.test', 'password' => 'a long test password']);
    $user->forceFill(['two_factor_secret' => encrypt(OPERATOR_SECRET), 'two_factor_recovery_codes' => encrypt('[]'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}
