<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\LiquidationRepository;
use App\Market\Models\Instrument;
use App\Market\Stream\LiquidationCollector;
use App\Simulation\Charts\ChartSeries;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/**
 * A forceOrder event as !forceOrder@arr sends it: SELL is a long position closed by the exchange, BUY a short one.
 *
 * @return array<string, mixed>
 */
function forceOrder(string $symbol, string $side, string $price, string $quantity, string $time): array
{
    return ['e' => 'forceOrder', 'E' => 0, 'o' => [
        's' => $symbol, 'S' => $side, 'o' => 'LIMIT', 'f' => 'IOC', 'q' => $quantity, 'p' => $price, 'ap' => $price,
        'X' => 'FILLED', 'l' => $quantity, 'z' => $quantity, 'T' => CarbonImmutable::parse($time, 'UTC')->getTimestampMs(),
    ]];
}

it('sums each minute\'s liquidations per perpetual and records the minutes it listened to (D-91)', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-11-09 10:00:20', 'UTC'));
    app()->instance(Clock::class, $clock);
    $btc = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    Instrument::register('binance', MarketType::Perp, Symbol::parse('ETH/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $collector = app(LiquidationCollector::class);
    $collector->start();

    $collector->message(forceOrder('BTCUSDT', 'SELL', '80000', '0.5', '2026-11-09 10:01:05'));
    $collector->message(forceOrder('BTCUSDT', 'SELL', '79900', '1', '2026-11-09 10:01:40'));
    $collector->message(forceOrder('BTCUSDT', 'BUY', '80100', '0.25', '2026-11-09 10:01:59'));
    $collector->message(forceOrder('NOPEUSDT', 'SELL', '1', '1', '2026-11-09 10:01:10'));  // unknown: ignored

    $clock->advanceTo(CarbonImmutable::parse('2026-11-09 10:02:01', 'UTC'));
    $collector->tick();
    $minute = app(LiquidationRepository::class)->minutes($btc, CarbonImmutable::parse('2026-11-09 10:01', 'UTC'), CarbonImmutable::parse('2026-11-09 10:01', 'UTC'))[0];

    // 10:00 was only partly heard (the collector started at 10:00:20), so it is not covered; 10:01 is.
    expect([$minute->longCount, (string) $minute->longNotional, $minute->shortCount, (string) $minute->shortNotional])->toBe([2, '119900', 1, '20025'])
        ->and(DB::table('liquidation_coverage')->pluck('minute')->map(fn (mixed $m): string => substr(is_string($m) ? $m : '', 11, 5))->all())->toBe(['10:01']);
});

it('gives strategies the liquidations of each candle, and none for minutes nobody listened to', function (): void {
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $candles = [];

    for ($m = 0; $m < 10; $m++) {
        $candles[] = Candle::fromStrings(CarbonImmutable::parse('2026-11-09 10:00', 'UTC')->addMinutes($m), Timeframe::OneMinute, '100', '101', '99', '100', '1', '100', 1, '0.5');
    }

    app(CandleRepository::class)->upsert($perp, CandleSource::Archive, $candles);
    $repository = app(LiquidationRepository::class);
    // Heard from 10:00 to 10:04 only; liquidations at 10:01 and 10:03.
    $repository->cover(array_map(fn (int $m): CarbonImmutable => CarbonImmutable::parse('2026-11-09 10:00', 'UTC')->addMinutes($m), range(0, 4)));
    $repository->store($perp, CarbonImmutable::parse('2026-11-09 10:01', 'UTC'), 2, '1000', 0, '0');
    $repository->store($perp, CarbonImmutable::parse('2026-11-09 10:03', 'UTC'), 1, '500', 1, '700');

    $chart = app(ChartSeries::class)->compute(Symbol::parse('BTC/USDT:USDT'), Timeframe::FiveMinutes, CarbonImmutable::parse('2026-11-09 10:00', 'UTC'), CarbonImmutable::parse('2026-11-09 10:10', 'UTC'), ['long_liquidations', 'short_liquidations']);

    expect($chart['series']['long_liquidations'])->toBe(['1500', null])
        ->and($chart['series']['short_liquidations'])->toBe(['700', null]);
});
