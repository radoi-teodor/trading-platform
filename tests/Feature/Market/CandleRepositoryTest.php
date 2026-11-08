<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

uses(RefreshDatabase::class);

/** @return array{CandleRepository, Instrument, TestHandler} */
function candleStore(): array
{
    $clock = new SimClock(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));
    $logs = new TestHandler;
    app()->instance(Clock::class, $clock);
    app()->instance(LoggerInterface::class, new Logger('test', [$logs]));
    $instrument = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), $clock->now());

    return [app(CandleRepository::class), $instrument, $logs];
}

function minute(int $minute, string $close = '76050.12345678'): Candle
{
    return Candle::fromStrings(
        CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC')->addMinutes($minute),
        Timeframe::OneMinute,
        '76000.10000000', '76100.00000000', '75990.00000000', $close,
        '12.34567891', '938271.12345678', 1234, '6.00000001',
    );
}

it('stores a candle once however many times it is written', function (): void {
    [$repository, $instrument] = candleStore();
    $first = $repository->upsert($instrument, CandleSource::Archive, [minute(0), minute(1)]);
    $second = $repository->upsert($instrument, CandleSource::Archive, [minute(0), minute(1)]);

    expect($first->inserted)->toBe(2)
        ->and($second->inserted)->toBe(0)
        ->and($second->kept)->toBe(2)
        ->and(DB::table('candles')->count())->toBe(2);
});

it('returns every value exactly as it was written', function (): void {
    [$repository, $instrument] = candleStore();
    $repository->upsert($instrument, CandleSource::Stream, [minute(5)]);

    [$stored] = $repository->range($instrument, Timeframe::OneMinute, minute(5)->openTime, minute(5)->openTime);

    expect((string) $stored->open)->toBe('76000.1')
        ->and((string) $stored->close)->toBe('76050.12345678')
        ->and((string) $stored->volume)->toBe('12.34567891')
        ->and((string) $stored->quoteVolume)->toBe('938271.12345678')
        ->and((string) $stored->takerBuyVolume)->toBe('6.00000001')
        ->and($stored->tradeCount)->toBe(1234)
        ->and($stored->openTime->toIso8601String())->toBe('2026-10-01T00:05:00+00:00');
});

it('lets a higher-ranked source overwrite a lower one and never the reverse', function (): void {
    [$repository, $instrument] = candleStore();
    $repository->upsert($instrument, CandleSource::Stream, [minute(0, '76050.1')]);
    $promoted = $repository->upsert($instrument, CandleSource::Archive, [minute(0, '76050.2')]);
    $ignored = $repository->upsert($instrument, CandleSource::Rest, [minute(0, '76050.3')]);

    [$stored] = $repository->range($instrument, Timeframe::OneMinute, minute(0)->openTime, minute(0)->openTime);

    expect($promoted->overwritten)->toBe(1)
        ->and($ignored->kept)->toBe(1)
        ->and((string) $stored->close)->toBe('76050.2')
        ->and(DB::table('candles')->value('source'))->toBe(CandleSource::Archive->value);
});

it('logs a discrepancy when two sources disagree about a closed candle', function (): void {
    [$repository, $instrument, $logs] = candleStore();
    $repository->upsert($instrument, CandleSource::Stream, [minute(0, '76050.1')]);
    $result = $repository->upsert($instrument, CandleSource::Archive, [minute(0, '76050.2')]);

    expect($result->discrepancies)->toBe(1)
        ->and($logs->hasWarningThatContains('Candle discrepancy'))->toBeTrue();
});

it('records when each candle was received', function (): void {
    [$repository, $instrument] = candleStore();
    $repository->upsert($instrument, CandleSource::Rest, [minute(0)]);

    expect(Rows::time(DB::table('candles')->sole(), 'received_at')->toIso8601String())
        ->toBe('2026-11-08T12:00:00+00:00');
});

it('finds the latest stored open time and returns ranges in order', function (): void {
    [$repository, $instrument] = candleStore();
    expect($repository->latestOpenTime($instrument, Timeframe::OneMinute))->toBeNull();

    $repository->upsert($instrument, CandleSource::Archive, [minute(2), minute(0), minute(1)]);

    $range = $repository->range($instrument, Timeframe::OneMinute, minute(0)->openTime, minute(2)->openTime);

    expect($repository->latestOpenTime($instrument, Timeframe::OneMinute)?->toIso8601String())->toBe('2026-10-01T00:02:00+00:00')
        ->and(array_map(fn (Candle $c): string => $c->openTime->format('H:i'), $range))->toBe(['00:00', '00:01', '00:02']);
});

it('refuses an inconsistent row even when it bypasses the model', function (): void {
    [$repository, $instrument] = candleStore();
    DB::table('candles')->insert([
        'instrument_id' => $instrument->id, 'timeframe' => '1m', 'open_time' => '2026-10-01 00:00:00+00',
        'open' => '100', 'high' => '90', 'low' => '95', 'close' => '100',
        'volume' => '1', 'quote_volume' => '100', 'trade_count' => 1, 'taker_buy_volume' => '0',
        'source' => CandleSource::Archive->value, 'received_at' => '2026-11-08 12:00:00+00',
    ]);
})->throws(QueryException::class);
