<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function partitionOf(string $openTime): ?string
{
    $row = DB::selectOne('select tableoid::regclass::text as partition from candles where open_time = ?', [$openTime]);

    return is_object($row) && is_string($row->partition ?? null) ? $row->partition : null;
}

it('keeps candles in one partition per month, with the same key and check (D-90)', function (): void {
    $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $candle = fn (string $time): Candle => Candle::fromStrings(CarbonImmutable::parse($time, 'UTC'), Timeframe::OneMinute, '100', '101', '99', '100', '1', '100', 1, '0.5');
    $repository = app(CandleRepository::class);

    $repository->upsert($btc, CandleSource::Archive, [$candle('2026-10-31 23:59:00'), $candle('2026-11-01 00:00:00'), $candle('2040-01-01 00:00:00')]);
    // The primary key still decides what an upsert replaces: the same minute again is kept, not duplicated.
    $repository->upsert($btc, CandleSource::Archive, [$candle('2026-11-01 00:00:00')]);

    $kind = DB::table('pg_class')->where('relname', 'candles')->value('relkind');

    expect($kind)->toBe('p')
        ->and(partitionOf('2026-10-31 23:59:00+00'))->toBe('candles_2026_10')
        ->and(partitionOf('2026-11-01 00:00:00+00'))->toBe('candles_2026_11')
        ->and(partitionOf('2040-01-01 00:00:00+00'))->toBe('candles_default')
        ->and(DB::table('candles')->count())->toBe(3)
        ->and(fn () => DB::table('candles')->insert([
            'instrument_id' => $btc->id, 'timeframe' => '1m', 'open_time' => '2026-11-02 00:00:00+00', 'open' => '100', 'high' => '90',
            'low' => '99', 'close' => '100', 'volume' => '1', 'quote_volume' => '100', 'trade_count' => 1, 'taker_buy_volume' => '0.5',
            'source' => 1, 'received_at' => '2026-11-02 00:01:00+00',
        ]))->toThrow(QueryException::class);
});
