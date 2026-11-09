<?php

declare(strict_types=1);

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Execution\Models\AccountEquity;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\JournalEntry;
use App\Notifications\DailyReport;
use App\Notifications\SendTelegram;
use App\Notifications\TelegramSettings;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;

uses(RefreshDatabase::class);

const REPORT_NOW = '2026-11-09 06:00:00';

beforeEach(fn () => app()->instance(Clock::class, new SimClock(CarbonImmutable::parse(REPORT_NOW, 'UTC'))));

function reportAccount(string $name = 'paper'): Account
{
    return Account::query()->create([
        'name' => $name, 'mode' => EngineMode::Paper, 'quote' => 'USDT', 'spot_balance' => '10000', 'futures_balance' => '0',
        'symbols' => ['BTC/USDT'], 'started_at' => '2026-11-01 00:00', 'created_at' => '2026-11-01 00:00',
    ]);
}

/** @param array<string, string> $fields */
function reportTrade(Account $account, int $seq, string $closedAt, array $fields): void
{
    AccountTrade::query()->create([
        'account_id' => $account->id, 'seq' => $seq, 'strategy' => 'abcd1234', 'label' => 'ema-cross@v1', 'symbol' => 'BTC/USDT', 'side' => 'buy',
        'opened_at' => '2026-11-08 01:00', 'closed_at' => $closedAt, 'quantity' => '0.01', 'entry_price' => '80000', 'exit_price' => '81000',
        'gross' => '10', 'fees' => '1', 'slippage' => '0', 'funding' => '0', 'net' => '9', 'r_multiple' => null, 'exit_reason' => 'signal', ...$fields,
    ]);
}

function reportEvent(Account $account, string $at, string $kind, string $detail, string $type = 'event'): void
{
    JournalEntry::query()->create([
        'account_id' => $account->id, 'event_at' => $at, 'kind' => $type, 'format' => 1,
        'payload' => ['time' => CarbonImmutable::parse($at, 'UTC')->toIso8601ZuluString(), 'kind' => $kind, 'detail' => $detail], 'created_at' => $at,
    ]);
}

it('sums up the last 24 hours of an account', function (): void {
    $account = reportAccount();
    AccountSnapshot::query()->create(['account_id' => $account->id, 'minute' => '2026-11-09 05:59', 'quiet_decisions' => 0, 'payload' => ['equity' => '10123.456'], 'updated_at' => REPORT_NOW]);
    AccountEquity::query()->insert([
        ['account_id' => $account->id, 'at' => '2026-11-08 05:00', 'equity' => '9900'],
        ['account_id' => $account->id, 'at' => '2026-11-08 06:00', 'equity' => '10000'],
        ['account_id' => $account->id, 'at' => '2026-11-09 05:00', 'equity' => '10100'],
    ]);
    reportTrade($account, 0, '2026-11-08 05:30', ['net' => '500']);
    reportTrade($account, 1, '2026-11-08 07:00', ['net' => '120.5', 'fees' => '2.25', 'funding' => '-0.5']);
    reportTrade($account, 2, '2026-11-09 04:00', ['net' => '-30', 'fees' => '1.5', 'funding' => '0.25']);
    reportEvent($account, '2026-11-08 05:00', 'drawdown_halt', 'older than a day');
    reportEvent($account, '2026-11-08 09:01', 'command', 'halt by operator');
    reportEvent($account, '2026-11-08 12:00', 'drawdown_halt', 'equity 9000');
    reportEvent($account, '2026-11-08 13:00', 'breaker_tripped', 'volatility_shock BTC/USDT: a range of 9');
    reportEvent($account, '2026-11-08 13:15', 'breaker_cleared', 'volatility_shock BTC/USDT: the pause is over');
    reportEvent($account, '2026-11-08 14:00', 'breaker_tripped', 'volatility_shock BTC/USDT: a range of 10');
    reportEvent($account, '2026-11-08 15:00', 'breaker_tripped', 'price_sanity ETH/USDT: close 1 against a median of 2');
    reportEvent($account, '2026-11-08 16:00', 'breaker_tripped', 'stale_data: the next minute closes at 2026-11-08T16:01:00Z', 'notice');
    reportEvent($account, '2026-11-08 17:00', 'reconciliation_mismatch', 'BTC 0.1 against 0.2');

    expect(app(DailyReport::class)->forAccount($account))->toBe(implode("\n", [
        'Trading X daily report: paper (paper), 2026-11-09 06:00 UTC',
        'Equity 10123.46 USDT, 24 hours +123.46 (+1.23 %)',
        'Trades closed: 2, 1 winning, net +90.50, fees 3.75, funding -0.25',
        'Halts and commands: 09:01 command: halt by operator; 12:00 drawdown halt: equity 9000',
        'Breakers: volatility_shock BTC/USDT 2, price_sanity ETH/USDT 1',
        'Stale data: 1. Reconciliation mismatches: 1.',
        'Last minute handled: 2026-11-09 05:59 UTC',
    ]));
});

it('says so when nothing happened, and when the engine has not run', function (): void {
    $quiet = reportAccount('quiet');
    AccountSnapshot::query()->create(['account_id' => $quiet->id, 'minute' => '2026-11-09 05:59', 'quiet_decisions' => 0, 'payload' => ['equity' => '10000'], 'updated_at' => REPORT_NOW]);
    $new = reportAccount('new');

    expect(app(DailyReport::class)->forAccount($quiet))->toBe(implode("\n", [
        'Trading X daily report: quiet (paper), 2026-11-09 06:00 UTC',
        'Equity 10000.00 USDT, 24 hours: no earlier point yet',
        'Trades closed: 0',
        'Halts and commands: none',
        'Breakers: none',
        'Stale data: 0. Reconciliation mismatches: 0.',
        'Last minute handled: 2026-11-09 05:59 UTC',
    ]))->and(app(DailyReport::class)->forAccount($new))->toBe("Trading X daily report: new (paper), 2026-11-09 06:00 UTC\nIts engine has not run yet.");
});

it('sends one message per account, unless the operator turned the report off', function (): void {
    reportAccount('alpha');
    reportAccount('beta');
    Bus::fake();

    expect(Artisan::call('report:daily'))->toBe(0);
    Bus::assertDispatchedTimes(SendTelegram::class, 2);
    Bus::assertDispatched(SendTelegram::class, fn (SendTelegram $job): bool => str_starts_with($job->text, 'Trading X daily report: alpha (paper)'));

    app(TelegramSettings::class)->setOptional('daily_report', false);
    Bus::fake();
    Artisan::call('report:daily');
    Bus::assertNothingDispatched();
    expect(Artisan::output())->toContain('The daily report is off');
});

it('runs every day at the configured time, in Bucharest by default', function (): void {
    $events = collect(app(Schedule::class)->events())->filter(fn (Event $e): bool => str_contains($e->command ?? '', 'report:daily'));

    expect($events)->toHaveCount(1)
        ->and($events->first()?->expression)->toBe('0 8 * * *')
        ->and($events->first()?->timezone)->toBe('Europe/Bucharest');
});
