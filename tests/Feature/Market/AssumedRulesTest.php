<?php

declare(strict_types=1);

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Instruments\AssumedRules;
use App\Market\Models\Instrument;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A perpetual known only from the archives, with one stored minute per price and volume given.
 *
 * @param  list<array{string, string}>  $minutes
 */
function archiveOnlyPerp(string $base, array $minutes, InstrumentStatus $status = InstrumentStatus::Delisted): Instrument
{
    $instrument = Instrument::register('binance', MarketType::Perp, Symbol::parse("{$base}/USDT:USDT"), CarbonImmutable::parse('2026-11-01', 'UTC'), $status);
    $candles = [];

    foreach ($minutes as $index => [$price, $volume]) {
        $candles[] = Candle::fromStrings(
            CarbonImmutable::parse('2025-03-09 23:50:00', 'UTC')->addMinutes($index), Timeframe::OneMinute,
            $price, $price, $price, $price, $volume, '10', 3, '0',
        );
    }

    app(CandleRepository::class)->upsert($instrument, CandleSource::Archive, $candles);

    return $instrument;
}

it('works out the filters of a perpetual delisted before they were read, from the decimals of its candles (D-89)', function (): void {
    $gone = archiveOnlyPerp('GONE', [['0.04120', '310'], ['0.04135', '12.5'], ['0.0413', '7']]);

    $history = app(AssumedRules::class)->of($gone);
    $rules = $history->at(CarbonImmutable::parse('2025-02-01', 'UTC'));

    expect((string) $rules->tickSize)->toBe('0.00001')
        ->and((string) $rules->stepSize)->toBe('0.1')
        ->and((string) $rules->minQty)->toBe('0.1')
        ->and($rules->maxQty)->toBeNull()
        ->and((string) $rules->minNotional)->toBe('5')
        // Every moment it traded is before the filters are "known", so a report says they are assumed.
        ->and($history->knownFrom()->format('Y-m-d H:i'))->toBe('2025-03-09 23:53')
        ->and($history->isAssumedAt(CarbonImmutable::parse('2025-03-09 23:52', 'UTC')))->toBeTrue();
});

it('assumes whole prices and quantities when the candles have no decimals (D-89)', function (): void {
    $rules = app(AssumedRules::class)->of(archiveOnlyPerp('WHOLE', [['12', '300'], ['13', '20']]))->at(CarbonImmutable::parse('2025-02-01', 'UTC'));

    expect((string) $rules->tickSize)->toBe('1')->and((string) $rules->stepSize)->toBe('1');
});

it('assumes nothing for a perpetual without candles (D-89)', function (): void {
    app(AssumedRules::class)->of(archiveOnlyPerp('EMPTY', []));
})->throws(InvalidArgumentException::class, 'EMPTY/USDT:USDT has neither exchange filters nor candles to work them out from');

it('assumes nothing for an instrument that still trades (D-89)', function (): void {
    app(AssumedRules::class)->of(archiveOnlyPerp('LIVE', [['1.5', '2']], InstrumentStatus::Trading));
})->throws(InvalidArgumentException::class, 'No exchange filters are known for LIVE/USDT:USDT; run market:instruments');
