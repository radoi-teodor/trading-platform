<?php

declare(strict_types=1);

use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Market\Stream\ScalpingCollector;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

// Messages and responses in the shapes of Binance's USD-M documentation (aggTrade, markPriceUpdate,
// premiumIndexKlines, openInterestHist); fapi.binance.com answers 451 where these tests were written.

/** @return array{ScalpingCollector, SimClock, Instrument} */
function collector(): array
{
    $clock = new SimClock(CarbonImmutable::parse('2026-11-09 10:00:05', 'UTC'));
    app()->instance(Clock::class, $clock);
    $btc = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $collector = app(ScalpingCollector::class);
    $collector->follow(['BTCUSDT' => $btc], ['BTCUSDT' => Decimal::of('100000')]);

    return [$collector, $clock, $btc];
}

/** @return array<string, int|string|bool> */
function aggTrade(int $id, string $price, string $quantity, string $time, bool $buyerMaker): array
{
    return ['e' => 'aggTrade', 'E' => 0, 's' => 'BTCUSDT', 'a' => $id, 'p' => $price, 'q' => $quantity, 'f' => $id, 'l' => $id, 'T' => CarbonImmutable::parse($time, 'UTC')->getTimestampMs(), 'm' => $buyerMaker];
}

/** @return array<string, int|string> */
function markUpdate(string $price, string $time): array
{
    return ['e' => 'markPriceUpdate', 'E' => CarbonImmutable::parse($time, 'UTC')->getTimestampMs(), 's' => 'BTCUSDT', 'p' => $price, 'i' => $price, 'P' => $price, 'r' => '0.0001', 'T' => 0];
}

function defaultBinanceFakes(): void
{
    Http::fake([
        'fapi.binance.com/fapi/v1/premiumIndexKlines*' => Http::response([[1794218340000, '-0.0001', '0.0002', '-0.0003', '0.0001', '0', 1794218399999, '0', 12, '0', '0', '0']]),
        'fapi.binance.com/futures/data/openInterestHist*' => Http::response([['symbol' => 'BTCUSDT', 'sumOpenInterest' => '95000.5', 'sumOpenInterestValue' => '7400000000.25', 'timestamp' => 1794218100000]]),
        'fapi.binance.com/fapi/v1/markPriceKlines*' => Http::response([]),
        'fapi.binance.com/fapi/v1/aggTrades*' => Http::response([]),
    ]);
}

it('subscribes to the trades and the mark price of each instrument it follows', function (): void {
    defaultBinanceFakes();
    [$collector] = collector();

    expect($collector->streams())->toBe(['btcusdt@aggTrade', 'btcusdt@markPrice@1s']);
});

it('writes a minute of trade flow and mark price once the minute is over, as the archive reduces it', function (): void {
    defaultBinanceFakes();
    [$collector, $clock, $btc] = collector();

    foreach ([
        aggTrade(1, '80000', '2', '2026-11-09 10:00:01', false),   // 160,000: a large buy
        aggTrade(2, '80000', '0.5', '2026-11-09 10:00:20', true),  // 40,000: below the threshold
        aggTrade(3, '80010', '1.5', '2026-11-09 10:00:40', true),  // 120,015: a large sell
    ] as $trade) {
        expect($collector->message($trade))->toBeTrue();
    }

    foreach (['80000', '80050', '79990', '80020'] as $second => $price) {
        $collector->message(markUpdate($price, '2026-11-09 10:00:'.str_pad((string) (10 * $second + 1), 2, '0', STR_PAD_LEFT)));
    }

    $collector->tick();
    $repository = app(ScalpingRepository::class);
    $at = CarbonImmutable::parse('2026-11-09 10:00', 'UTC');
    expect($repository->flow($btc, $at, $at))->toBe([]);

    $clock->advanceTo(CarbonImmutable::parse('2026-11-09 10:01:02', 'UTC'));
    $collector->tick();
    $flow = $repository->flow($btc, $at, $at)[0];
    $mark = $repository->marks($btc, $at, $at)[0];

    expect([(string) $flow->largeBuyVolume, (string) $flow->largeSellVolume, $flow->largeBuyCount, $flow->largeSellCount, (string) $flow->largestNotional])->toBe(['2', '1.5', 1, 1, '160000'])
        ->and([(string) $mark->open, (string) $mark->high, (string) $mark->low, (string) $mark->close])->toBe(['80000', '80050', '79990', '80020']);
});

it('reads the premium index and open interest over REST every ten seconds', function (): void {
    defaultBinanceFakes();
    [$collector, $clock, $btc] = collector();
    $clock->advanceTo(CarbonImmutable::parse('2026-11-09 10:00:05', 'UTC'));
    $collector->tick();
    $collector->tick();
    $clock->advanceTo(CarbonImmutable::parse('2026-11-09 10:00:16', 'UTC'));
    $collector->tick();

    $repository = app(ScalpingRepository::class);
    expect((string) $repository->premium($btc, CarbonImmutable::parse('2026-11-09 09:59', 'UTC'), CarbonImmutable::parse('2026-11-09 09:59', 'UTC'))[0]->close)->toBe('0.0001')
        ->and((string) $repository->openInterest($btc, CarbonImmutable::parse('2026-11-09 09:55', 'UTC'), CarbonImmutable::parse('2026-11-09 09:55', 'UTC'))[0]->contracts)->toBe('95000.5')
        ->and(Http::recorded()->filter(fn (array $pair): bool => str_contains($pair[0]->url(), 'premiumIndexKlines'))->count())->toBe(2);
});

it('refills the last hour\'s missing trade flow and mark price over REST on reconnect', function (): void {
    [$collector, , $btc] = collector();
    Http::fake([
        'fapi.binance.com/fapi/v1/aggTrades*' => Http::response([['a' => 7, 'p' => '80000', 'q' => '3', 'f' => 7, 'l' => 7, 'T' => 1794214860000, 'm' => false]]),
        'fapi.binance.com/fapi/v1/markPriceKlines*' => Http::response([[1794214860000, '80000', '80100', '79900', '80050', '0', 1794214919999, '0', 60, '0', '0', '0']]),
        '*' => Http::response([]),
    ]);

    $collector->repair();

    $at = CarbonImmutable::parse('2026-11-09 09:01', 'UTC');
    expect((string) app(ScalpingRepository::class)->flow($btc, $at, $at)[0]->largeBuyVolume)->toBe('3')
        ->and((string) app(ScalpingRepository::class)->marks($btc, $at, $at)[0]->close)->toBe('80050');
    Http::assertSent(fn (Request $r): bool => str_contains($r->url(), '/fapi/v1/aggTrades') && str_contains($r->url(), 'startTime='));
});
