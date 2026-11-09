<?php

declare(strict_types=1);

use App\Execution\Models\Account;
use App\Execution\Models\Deployment;
use App\Market\Data\CandleSource;
use App\Market\Data\PriceMinute;
use App\Market\ScalpingRepository;
use App\Simulation\StrategyLibrary;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

const MARK_STRATEGY = <<<'YAML'
    strategy: mark-watch
    title: Mark price watch
    family: trend
    hypothesis: A test strategy that reads the mark price and never trades.
    fails_when: Always; it is a test.
    params:
      n: { default: 3, range: [2, 4], step: 1 }
    market: { exchange: binance, type: perp, instruments: [BTC/USDT:USDT] }
    timeframe: 5m
    entries:
      long:
        when: mark_price > 1000000 and close > ema($n)
    exits:
      stop_loss: 1 * atr(3)
    YAML;

it('waits for the minute\'s mark price on an instrument where a strategy reads it (D-84)', function (): void {
    backtestFixture(days: 1);
    $perp = perpFixture(days: 1);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-02 00:00', 'UTC')));
    // Marks for the first twelve hours only.
    $marks = [];

    for ($m = 0; $m < 720; $m++) {
        $at = CarbonImmutable::parse('2026-10-01 00:00', 'UTC')->addMinutes($m);
        $marks[] = new PriceMinute($at, Decimal::of('100'), Decimal::of('100'), Decimal::of('100'), Decimal::of('100'));
    }

    app(ScalpingRepository::class)->upsertMarks($perp, CandleSource::Archive, $marks);
    $version = app(StrategyLibrary::class)->versions()->save(MARK_STRATEGY, 'operator')->version ?? throw new RuntimeException('No version.');
    Artisan::call('account:create', ['name' => 'paper', '--symbol' => ['BTC/USDT:USDT'], '--start' => '2026-10-01 06:00']);
    Deployment::query()->create(['account_id' => Account::query()->sole()->id, 'strategy_version_id' => $version->id, 'parameters' => [], 'active' => true, 'reason' => 'test', 'created_at' => '2026-10-01']);

    $exit = Artisan::call('engine:run', ['account' => 'paper', '--once' => true]);
    $output = Artisan::output();

    expect($exit)->toBe(0, $output)->and($output)->toContain('paper: handled to 2026-10-01T12:00:00Z');
});
