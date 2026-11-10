<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Instruments\TradingBreaks;
use App\Market\Models\Instrument;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

function breakInstrument(string $symbol = 'AERGO/USDT:USDT'): Instrument
{
    return Instrument::register('binance', MarketType::Perp, Symbol::parse($symbol), CarbonImmutable::parse('2026-11-01', 'UTC'));
}

/** @param array<string, int> $trades the trades of each stored day, by day */
function storeDays(Instrument $instrument, array $trades): void
{
    foreach ($trades as $day => $count) {
        DB::table('daily_stats')->insert([
            'instrument_id' => $instrument->id, 'day' => $day, 'open' => '1', 'high' => '1', 'low' => '1', 'close' => '1',
            'volume' => $count === 0 ? '0' : '10', 'quote_volume' => $count === 0 ? '0' : '10', 'trades' => $count,
        ]);
    }
}

/** @return list<string> the breaks of $instrument as "from to" */
function breaksOf(Instrument $instrument): array
{
    return array_map(
        fn (array $break): string => $break['from']->format('m-d H:i').' '.$break['to']->format('m-d H:i'),
        app(TradingBreaks::class)->of($instrument),
    );
}

it('finds where an instrument stopped trading and started again from its days (D-89)', function (): void {
    $aergo = breakInstrument();
    // Days without a trade before its first and after its last trading day are not a break: they are outside its life.
    storeDays($aergo, ['2026-10-01' => 0, '2026-10-02' => 5, '2026-10-03' => 7, '2026-10-04' => 0, '2026-10-05' => 0, '2026-10-06' => 9, '2026-10-07' => 0]);
    $other = breakInstrument('BTC/USDT:USDT');
    storeDays($other, ['2026-10-02' => 5, '2026-10-03' => 7, '2026-10-04' => 3]);

    expect(app(TradingBreaks::class)->find($aergo))->toBe(1)
        ->and(app(TradingBreaks::class)->find($other))->toBe(0)
        ->and(breaksOf($aergo))->toBe(['10-04 00:00 10-06 00:00'])
        ->and(breaksOf($other))->toBe([])
        ->and(app(TradingBreaks::class)->among([$aergo->id, $other->id]))->toBe([
            $aergo->id => [[CarbonImmutable::parse('2026-10-04', 'UTC')->getTimestamp(), CarbonImmutable::parse('2026-10-06', 'UTC')->getTimestamp()]],
        ]);
});

it('takes a day that is not stored between two trading days as a break', function (): void {
    $aergo = breakInstrument();
    storeDays($aergo, ['2026-10-02' => 5, '2026-10-05' => 9]);

    app(TradingBreaks::class)->find($aergo);

    expect(breaksOf($aergo))->toBe(['10-03 00:00 10-05 00:00']);
});

it('sets the ends of a break to the minute from the stored candles', function (): void {
    $aergo = breakInstrument();
    storeDays($aergo, ['2026-10-01' => 5, '2026-10-02' => 0, '2026-10-03' => 9]);
    $candles = [];

    // It traded to 09:00 on the first day, the archive goes on with flat candles, and it trades again from 11:00.
    foreach (range(0, 2 * 1440 + 720) as $minute) {
        if ($minute >= 2 * 1440 && $minute < 2 * 1440 + 660) {
            continue; // the archive holds nothing between midnight and the relisting
        }

        $candles[] = CandleFactory::at($minute, '100', $minute >= 540 && $minute < 2 * 1440 ? '0' : '1');
    }

    app(CandleRepository::class)->upsert($aergo, CandleSource::Archive, $candles);
    app(TradingBreaks::class)->find($aergo);

    expect(breaksOf($aergo))->toBe(['10-01 09:00 10-03 11:00']);
});

it('finds the same breaks again, and forgets one the days no longer show', function (): void {
    $aergo = breakInstrument();
    storeDays($aergo, ['2026-10-01' => 5, '2026-10-02' => 0, '2026-10-03' => 9]);

    app(TradingBreaks::class)->find($aergo);
    app(TradingBreaks::class)->find($aergo);

    expect(DB::table('instrument_breaks')->count())->toBe(1);

    DB::table('daily_stats')->where('day', '2026-10-02')->update(['trades' => 4]);

    expect(app(TradingBreaks::class)->find($aergo))->toBe(0)
        ->and(DB::table('instrument_breaks')->count())->toBe(0);
});
