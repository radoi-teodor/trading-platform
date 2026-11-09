<?php

declare(strict_types=1);

use App\Execution\Models\Account;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\ExchangeKey;
use App\Execution\Models\JournalEntry;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\PendingCommand;
use Pest\TestSuite;
use Tests\TestCase;

uses(RefreshDatabase::class);

const TESTNET_NOW = '2026-10-05 00:00';

function testnetAccount(bool $keys = true): Account
{
    backtestFixture(days: 3);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse(TESTNET_NOW, 'UTC')));
    Artisan::call('account:create', ['name' => 'testnet', '--mode' => 'testnet']);
    $account = Account::query()->sole();

    if ($keys) {
        ExchangeKey::query()->create(['account_id' => $account->id, 'api_key' => 'testkeyonlyabcdef123', 'api_secret' => 'testsecretonlyabcdef456', 'created_at' => TESTNET_NOW]);
    }

    return $account;
}

/**
 * @param  list<int>  $skews  Binance's clock minus ours, one per look at it
 * @param  list<array<string, mixed>>  $openOrders
 */
function fakeTestnet(array $skews = [0], array $openOrders = []): void
{
    $times = Http::sequence();

    foreach ($skews as $skew) {
        $times->push(['serverTime' => CarbonImmutable::parse(TESTNET_NOW, 'UTC')->getTimestampMs() + $skew]);
    }

    Http::fake([
        'testnet.binance.vision/api/v3/time' => $times,
        'testnet.binance.vision/api/v3/openOrders*' => Http::response($openOrders),
        'testnet.binance.vision/api/v3/account*' => Http::response(['balances' => [['asset' => 'USDT', 'free' => '10000', 'locked' => '0'], ['asset' => 'BTC', 'free' => '1', 'locked' => '0']]]),
    ]);
}

it('needs a stored key, a clean account, and a clock within a second of Binance\'s', function (): void {
    testnetAccount(keys: false);

    expect(Artisan::call('engine:run', ['account' => 'testnet', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain('has no key: store one with php artisan account:keys testnet');

    ExchangeKey::query()->create(['account_id' => Account::query()->sole()->id, 'api_key' => 'testkeyonlyabcdef123', 'api_secret' => 'testsecretonlyabcdef456', 'created_at' => TESTNET_NOW]);
    fakeTestnet(openOrders: [['clientOrderId' => 'foreign', 'side' => 'BUY', 'origQty' => '1', 'executedQty' => '0', 'price' => '1', 'stopPrice' => '0', 'status' => 'NEW']]);

    expect(Artisan::call('engine:run', ['account' => 'testnet', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain('has open orders or positions on testnet');
});

it('starts from the exchange as it is, and halts when the clocks disagree', function (): void {
    testnetAccount();
    fakeTestnet(skews: [0, 1500]);

    expect(Artisan::call('engine:run', ['account' => 'testnet', '--once' => true]))->toBe(0);

    expect(Artisan::call('engine:run', ['account' => 'testnet', '--once' => true]))->toBe(1)
        ->and(EngineCommandRow::query()->sole()->issued_by)->toBe('engine')
        ->and(JournalEntry::query()->where('kind', 'notice')->sole()->payload['kind'])->toBe('clock_skew');
});

it('stores a key asked without echo, encrypted', function (): void {
    testnetAccount(keys: false);

    $test = TestSuite::getInstance()->test;
    $command = $test instanceof TestCase ? $test->artisan('account:keys', ['account' => 'testnet']) : throw new LogicException('Runs inside a test.');

    if (! $command instanceof PendingCommand) {
        throw new LogicException('Expected a pending command.');
    }

    $command->expectsQuestion('API key (hidden)', 'FAKEKEYFAKEKEY1234')
        ->expectsQuestion('API secret (hidden)', 'qrstuvwxyzABCDEF5678')
        ->assertSuccessful()
        ->run();

    $raw = DB::table('exchange_keys')->sole();

    expect(ExchangeKey::query()->sole()->api_secret)->toBe('qrstuvwxyzABCDEF5678')
        ->and(json_encode($raw))->not->toContain('qrstuvwxyzABCDEF5678')
        ->and(ExchangeKey::query()->sole()->toArray())->not->toHaveKey('api_secret');
});
