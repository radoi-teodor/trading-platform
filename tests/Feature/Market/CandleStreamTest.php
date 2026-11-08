<?php

declare(strict_types=1);

use App\Market\Binance\Sleeper;
use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Stream\CandleStream;
use App\Market\Stream\StreamTransport;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
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
