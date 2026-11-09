<?php

declare(strict_types=1);

use App\Market\Data\InstrumentDefinition;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Instruments\InstrumentSync;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function definition(string $symbol, string $tickSize = '0.01'): InstrumentDefinition
{
    return new InstrumentDefinition(
        MarketType::Spot,
        Symbol::parse($symbol),
        InstrumentStatus::Trading,
        Decimal::of($tickSize),
        Decimal::of('0.00001'),
        Decimal::of('0.00001'),
        Decimal::of('9000'),
        Decimal::of('5'),
        ['PRICE_FILTER' => ['tickSize' => $tickSize]],
    );
}

it('records instruments and a new filter row only when the filters change', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-11-01 00:00:00', 'UTC'));
    app()->instance(Clock::class, $clock);
    $sync = app(InstrumentSync::class);

    $sync->sync('binance', MarketType::Spot, [definition('BTC/USDT'), definition('ETH/USDT')]);
    $clock->advanceBy(86_400);
    $sync->sync('binance', MarketType::Spot, [definition('BTC/USDT'), definition('ETH/USDT')]);
    $clock->advanceBy(86_400);
    $result = $sync->sync('binance', MarketType::Spot, [definition('BTC/USDT', '0.1'), definition('ETH/USDT')]);

    $btc = Instrument::query()->where('venue_symbol', 'BTCUSDT')->sole();
    $filters = InstrumentFilter::query()->where('instrument_id', $btc->id)->orderBy('valid_from')->get();

    expect($result->instruments)->toBe(2)
        ->and($result->filterChanges)->toBe(1)
        ->and($filters)->toHaveCount(2)
        ->and($filters->last()?->valid_from->toIso8601String())->toBe('2026-11-03T00:00:00+00:00')
        ->and(Decimal::of($filters->last()->tick_size ?? '0')->equals(Decimal::of('0.1')))->toBeTrue();
});

it('keeps the listing time, the delivery time and the underlying type of a perpetual (D-89)', function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-01 00:00:00', 'UTC')));
    $perp = fn (string $symbol, InstrumentStatus $status, ?string $delisted): InstrumentDefinition => new InstrumentDefinition(
        MarketType::Perp, Symbol::parse($symbol), $status, Decimal::of('0.1'), Decimal::of('0.001'), Decimal::of('0.001'), null, Decimal::of('5'), [],
        listedAt: CarbonImmutable::parse('2024-03-05 08:00:00', 'UTC'),
        delistedAt: $delisted === null ? null : CarbonImmutable::parse($delisted, 'UTC'),
        underlyingType: 'COIN',
    );

    app(InstrumentSync::class)->sync('binance', MarketType::Perp, [
        $perp('NEW/USDT:USDT', InstrumentStatus::Trading, null),
        $perp('OLD/USDT:USDT', InstrumentStatus::Delisted, '2026-06-01 09:00:00'),
    ]);
    // A later sync that no longer lists OLD keeps its dates.
    app(InstrumentSync::class)->sync('binance', MarketType::Perp, [$perp('NEW/USDT:USDT', InstrumentStatus::Trading, null)]);

    $new = Instrument::query()->where('venue_symbol', 'NEWUSDT')->sole();
    $old = Instrument::query()->where('venue_symbol', 'OLDUSDT')->sole();

    expect($new->listed_at?->toIso8601String())->toBe('2024-03-05T08:00:00+00:00')
        ->and($new->delisted_at)->toBeNull()
        ->and($new->underlying_type)->toBe('COIN')
        ->and($old->status)->toBe(InstrumentStatus::Delisted)
        ->and($old->delisted_at?->toIso8601String())->toBe('2026-06-01T09:00:00+00:00');
});

it('marks an instrument that the exchange no longer lists as delisted', function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-01 00:00:00', 'UTC')));
    $sync = app(InstrumentSync::class);

    $sync->sync('binance', MarketType::Spot, [definition('BTC/USDT'), definition('LUNA/USDT')]);
    $result = $sync->sync('binance', MarketType::Spot, [definition('BTC/USDT')]);

    expect(Instrument::query()->where('venue_symbol', 'LUNAUSDT')->sole()->status)->toBe(InstrumentStatus::Delisted)
        ->and($result->delisted)->toBe(1);
});

it('loads spot instruments from Binance on the command line', function (): void {
    Http::fake(['data-api.binance.vision/api/v3/exchangeInfo*' => Http::response(binanceFixture('spot-exchange-info.json'))]);

    expect(Artisan::call('market:instruments', ['market' => 'spot']))->toBe(0)
        ->and(Artisan::output())->toContain('spot: 3 instruments, 3 filter changes, 0 delisted');

    expect(Instrument::query()->count())->toBe(3);
});
