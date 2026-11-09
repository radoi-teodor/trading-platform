<?php

declare(strict_types=1);

use App\Market\Binance\Sleeper;
use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\FormingCandles;
use App\Market\Models\Instrument;
use App\Market\Stream\CandleStream;
use App\Market\Stream\StreamTransport;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\Support\FakeTransport;
use Tests\Support\RecordingSleeper;

uses(RefreshDatabase::class);

/** @return list<string> */
function streamMessages(): array
{
    $lines = file(__DIR__.'/../../Fixtures/binance/spot-stream-kline-btcusdt.jsonl', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    return $lines === false ? [] : $lines;
}

/**
 * @param  list<list<string>>  $sessions
 * @return array{CandleStream, FakeTransport, RecordingSleeper, Instrument}
 */
function streamWith(array $sessions, string $now = '2026-11-08 15:04:30'): array
{
    $transport = new FakeTransport($sessions);
    $sleeper = new RecordingSleeper;
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse($now, 'UTC')));
    app()->instance(StreamTransport::class, $transport);
    app()->instance(Sleeper::class, $sleeper);
    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse($now, 'UTC'));

    return [app(CandleStream::class), $transport, $sleeper, $btc];
}

it('stores closed candles only, as coming from the stream', function (): void {
    [$stream, $transport, , $btc] = streamWith([streamMessages()]);
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-11-08', 'UTC'));

    $stream->run(MarketType::Spot, [$btc, $eth], maxSessions: 1);

    expect($transport->connectedTo)->toBe(['wss://data-stream.binance.vision/stream?streams=btcusdt@kline_1m/ethusdt@kline_1m'])
        ->and(DB::table('candles')->count())->toBe(1)
        ->and(DB::table('candles')->value('source'))->toBe(CandleSource::Stream->value);
});

/** A kline stream message for BTCUSDT at 15:03, still forming unless $closed. */
function klineMessage(string $close, bool $closed = false): string
{
    return json_encode(['stream' => 'btcusdt@kline_1m', 'data' => ['e' => 'kline', 'E' => 1794150186096, 's' => 'BTCUSDT', 'k' => [
        't' => 1794150180000, 'T' => 1794150239999, 's' => 'BTCUSDT', 'i' => '1m', 'o' => '100', 'c' => $close, 'h' => '110', 'l' => '90',
        'v' => '2', 'n' => 5, 'x' => $closed, 'q' => '200', 'V' => '1', 'Q' => '100', 'B' => '0',
    ]]], JSON_THROW_ON_ERROR);
}

it('keeps the forming minute of each instrument for the chart, at most once a second (D-87)', function (): void {
    [$stream, , , $btc] = streamWith([[klineMessage('101'), klineMessage('102'), klineMessage('103')]]);

    $stream->run(MarketType::Spot, [$btc], maxSessions: 1);
    $forming = app(FormingCandles::class)->latest($btc);

    expect(DB::table('candles')->count())->toBe(0)
        ->and(DB::table('forming_candles')->count())->toBe(1)
        ->and($forming?->candle->openTime->toIso8601String())->toBe('2026-11-08T15:03:00+00:00')
        // The clock does not move in this test: the second and third updates fall in the same second.
        ->and((string) $forming?->candle->close)->toBe('101')
        ->and($forming?->receivedAt->toIso8601String())->toBe('2026-11-08T15:04:30+00:00');
});

it('writes the forming minute again once a second has passed, and still stores the closed one', function (): void {
    [, , , $btc] = streamWith([[klineMessage('101'), klineMessage('102'), klineMessage('104', closed: true)]], '2026-11-08 15:03:10');
    // A clock that moves a second each time it is read: every update falls in a new second.
    app()->instance(Clock::class, new class implements Clock
    {
        private int $reads = 0;

        public function now(): CarbonImmutable
        {
            return CarbonImmutable::parse('2026-11-08 15:03:10', 'UTC')->addSeconds($this->reads++);
        }
    });

    app(CandleStream::class)->run(MarketType::Spot, [$btc], maxSessions: 1);

    expect((string) app(FormingCandles::class)->latest($btc)?->candle->close)->toBe('102')
        ->and(DB::table('candles')->count())->toBe(1)
        ->and(DB::table('candles')->value('close'))->toStartWith('104');
});

// On Windows PHP has no pcntl, so SIGTERM is not defined: the command died before streaming anything and
// start-local.ps1 -Stream restarted it forever. It traps the signals only where they exist.
it('starts the stream command on a PHP without signal handling', function (): void {
    [$stream] = streamWith([]);
    $stream->stop();
    app()->instance(CandleStream::class, $stream);

    expect(Artisan::call('market:stream', ['market' => 'spot']))->toBe(0)
        ->and(Artisan::output())->toContain('Streaming spot candles');
});

it('repairs the gap since the last stored candle when it connects', function (): void {
    [$stream, , , $btc] = streamWith([[]]);
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, [
        Candle::fromStrings(CarbonImmutable::parse('2026-11-08 15:00:00', 'UTC'), Timeframe::OneMinute, '100', '101', '99', '100', '1', '100', 1, '0.5'),
    ]);
    Http::fake(['data-api.binance.vision/api/v3/klines*' => Http::response(json_encode([
        [1794150060000, '100', '101', '99', '100', '1', 1794150119999, '100', 1, '0.5', '50', '0'], // 15:01
        [1794150120000, '100', '101', '99', '100', '1', 1794150179999, '100', 1, '0.5', '50', '0'], // 15:02
        [1794150180000, '100', '101', '99', '100', '1', 1794150239999, '100', 1, '0.5', '50', '0'], // 15:03
    ], JSON_THROW_ON_ERROR))]);

    $stream->run(MarketType::Spot, [$btc], maxSessions: 1);

    expect(DB::table('candles')->where('source', CandleSource::Rest->value)->count())->toBe(3);
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'startTime=1794150060000'));
});

it('backs off while failures repeat and starts over after a session that delivered data', function (): void {
    [$stream, $transport, $sleeper, $btc] = streamWith([[FakeTransport::FAIL], [], [FakeTransport::FAIL], streamMessages(), []]);

    $stream->run(MarketType::Spot, [$btc], maxSessions: 5);

    // failure, empty session, failure: 1, 2, 4; data then a server close: back to 1; empty again: 2.
    expect($transport->connectedTo)->toHaveCount(5)
        ->and($sleeper->slept)->toBe([1, 2, 4, 1, 2])
        ->and(DB::table('candles')->count())->toBe(1);
});

it('keeps streaming when the gap cannot be repaired, and says so in its heartbeat', function (): void {
    [$stream, , , $btc] = streamWith([streamMessages()]);
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, [
        Candle::fromStrings(CarbonImmutable::parse('2026-11-08 15:00:00', 'UTC'), Timeframe::OneMinute, '100', '101', '99', '100', '1', '100', 1, '0.5'),
    ]);
    Http::fake(['*' => Http::response(['code' => 0, 'msg' => 'refused'], 451)]);

    $stream->run(MarketType::Spot, [$btc], maxSessions: 1);

    $heartbeat = DB::table('heartbeats')->where('process', 'market:stream spot')->sole();

    expect(DB::table('candles')->where('source', CandleSource::Stream->value)->count())->toBe(1)
        ->and(is_string($heartbeat->details) ? $heartbeat->details : '')->toContain('451');
});

it('collects the scalping data of the perpetuals it is given on the same connection (D-81)', function (): void {
    $trade = json_encode(['stream' => 'btcusdt@aggTrade', 'data' => ['e' => 'aggTrade', 'E' => 0, 's' => 'BTCUSDT', 'a' => 1, 'p' => '80000', 'q' => '2', 'f' => 1, 'l' => 1, 'T' => 1794150000000, 'm' => false]], JSON_THROW_ON_ERROR);
    [$stream, $transport] = streamWith([[$trade]], '2026-11-08 15:00:30');
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-08', 'UTC'));
    Http::fake(['*' => Http::response([])]);

    $stream->run(MarketType::Perp, [$perp], maxSessions: 1, scalping: ['BTCUSDT' => $perp], thresholds: ['BTCUSDT' => Decimal::of('100000')]);

    // USD-M market streams moved to the /market path; the old /stream path accepts the connection and sends nothing.
    expect($transport->connectedTo)->toBe(['wss://fstream.binance.com/market/stream?streams=btcusdt@kline_1m/btcusdt@aggTrade/btcusdt@markPrice@1s/!forceOrder@arr'])
        ->and(DB::table('candles')->count())->toBe(0);
});
