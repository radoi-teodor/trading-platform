<?php

declare(strict_types=1);

use App\Market\Binance\Sleeper;
use App\Market\Data\MarketType;
use App\Market\Depth\DepthStream;
use App\Market\Models\Instrument;
use App\Market\ScalpingRepository;
use App\Market\Stream\StreamTransport;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\Support\FakeTransport;
use Tests\Support\RecordingSleeper;

uses(RefreshDatabase::class);

/**
 * A depthUpdate message as fstream.binance.com/public sends it (D-91).
 *
 * @param  list<list<string>>  $bids
 * @param  list<list<string>>  $asks
 */
function depthMessage(string $time, int $first, int $last, int $previous, array $bids = [], array $asks = []): string
{
    return json_encode(['stream' => 'btcusdt@depth@500ms', 'data' => [
        'e' => 'depthUpdate', 'E' => CarbonImmutable::parse($time, 'UTC')->getTimestampMs(), 'T' => 0, 's' => 'BTCUSDT',
        'U' => $first, 'u' => $last, 'pu' => $previous, 'b' => $bids, 'a' => $asks,
    ]], JSON_THROW_ON_ERROR);
}

/**
 * @param  list<string>  $messages
 * @return array{DepthStream, FakeTransport, Instrument}
 */
function depthStreamWith(array $messages): array
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 10:00:00', 'UTC')));
    $transport = new FakeTransport([$messages]);
    app()->instance(StreamTransport::class, $transport);
    app()->instance(Sleeper::class, new RecordingSleeper);
    $btc = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    Http::fake(['fapi.binance.com/fapi/v1/depth*' => Http::response(['lastUpdateId' => 100, 'E' => 0, 'T' => 0, 'bids' => [['99.5', '4'], ['99', '2']], 'asks' => [['101', '1'], ['104', '10']]])]);

    return [app(DepthStream::class), $transport, $btc];
}

it('keeps a local order book from a snapshot and the diffs, and writes its depth when each 5 minutes ends (D-91)', function (): void {
    [$stream, $transport, $btc] = depthStreamWith([
        depthMessage('2026-11-09 10:00:01', 99, 105, 98),
        depthMessage('2026-11-09 10:04:59', 106, 110, 105, bids: [['99.5', '5']]),
        depthMessage('2026-11-09 10:05:00', 111, 112, 110, bids: [['99.5', '0']]),
    ]);

    $stream->run([$btc], maxSessions: 1);
    $rows = app(ScalpingRepository::class)->depth($btc, CarbonImmutable::parse('2026-11-09 10:00', 'UTC'), CarbonImmutable::parse('2026-11-09 10:10', 'UTC'));

    // The book as the first 5 minutes ended: 99.5 x 5 within 1 % of the mid price (100.25).
    expect($transport->connectedTo)->toBe(['wss://fstream.binance.com/public/stream?streams=btcusdt@depth@500ms'])
        ->and($rows)->toHaveCount(1)
        ->and($rows[0]->time->format('H:i'))->toBe('10:00')
        ->and((string) $rows[0]->bid(1))->toBe('497.5')
        ->and((string) $rows[0]->ask(5))->toBe('1141')
        ->and(DB::table('heartbeats')->where('process', 'market:depth')->exists())->toBeTrue();
});

it('starts again from a new snapshot when an update is missing', function (): void {
    [$stream, , $btc] = depthStreamWith([
        depthMessage('2026-11-09 10:00:01', 99, 105, 98),
        depthMessage('2026-11-09 10:00:02', 108, 110, 107),  // 106 and 107 never came
        depthMessage('2026-11-09 10:00:03', 111, 112, 110),
    ]);

    $stream->run([$btc], maxSessions: 1);

    expect(Http::recorded()->filter(fn (array $pair): bool => str_contains($pair[0]->url(), '/fapi/v1/depth'))->count())->toBe(2);
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'symbol=BTCUSDT') && str_contains($request->url(), 'limit=1000'));
});
