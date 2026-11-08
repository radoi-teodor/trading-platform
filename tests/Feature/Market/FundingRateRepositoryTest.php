<?php

declare(strict_types=1);

use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('stores funding rates once, exactly, in order', function (): void {
    $clock = new SimClock(CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC'));
    app()->instance(Clock::class, $clock);
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), $clock->now());
    $repository = app(FundingRateRepository::class);

    $rates = [
        new FundingRate(CarbonImmutable::parse('2026-10-01 08:00:00', 'UTC'), Decimal::of('-0.00001234'), 8, null),
        new FundingRate(CarbonImmutable::parse('2026-10-01 00:00:00', 'UTC'), Decimal::of('0.0001'), 8, null),
    ];

    $repository->upsert($perp, CandleSource::Archive, $rates);
    $repository->upsert($perp, CandleSource::Archive, $rates);

    $stored = $repository->range($perp, CarbonImmutable::parse('2026-10-01', 'UTC'), CarbonImmutable::parse('2026-10-02', 'UTC'));

    expect(DB::table('funding_rates')->count())->toBe(2)
        ->and(array_map(fn (FundingRate $r): string => (string) $r->rate, $stored))->toBe(['0.0001', '-0.00001234'])
        ->and($stored[0]->intervalHours)->toBe(8);
});

it('registers an instrument once per exchange, market and symbol', function (): void {
    $now = CarbonImmutable::parse('2026-11-08 12:00:00', 'UTC');
    $first = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), $now);
    $again = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), $now->addDay());
    $perp = Instrument::register('binance', MarketType::Perp, Symbol::parse('ETH/USDT:USDT'), $now);

    expect($again->id)->toBe($first->id)
        ->and($perp->id)->not->toBe($first->id)
        ->and($again->venue_symbol)->toBe('ETHUSDT')
        ->and($again->last_seen_at?->toIso8601String())->toBe('2026-11-09T12:00:00+00:00')
        ->and(Instrument::query()->count())->toBe(2);
});
