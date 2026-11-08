<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Market\Data\MarketType;
use App\Risk\RiskPolicy;
use App\Simulation\Report\Report;
use App\Strategy\Baselines\FundingCarry;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\GoldenData;

/*
 * The perpetual baselines must reproduce known results on real data (docs/README.md, Phase 2b): the funding carry
 * equals its closed form, and the short trend rule equals an independent reference. Both are written here from the
 * rules of docs/simulation.md, section 3, docs/engine.md, section 8, and D-44 to D-50, without the engine, the venue
 * or the ledger.
 */

/** A market fill: the next minute's open moved by half spread and impact, rounded against the order to the tick. */
function perpetualReferencePrice(Candle $minute, Decimal $quantity, bool $buying, string $tick): Decimal
{
    $participation = $minute->volume->isPositive() ? $quantity->dividedBy($minute->volume, 18) : Decimal::of(1);
    $participation = $participation->isGreaterThan(Decimal::of(1)) ? Decimal::of(1) : $participation;
    $slippage = Decimal::of('0.0002')->plus(Decimal::of('0.1')->times($participation));
    $open = $minute->open->value;

    return $buying
        ? $open->times(Decimal::of(1)->plus($slippage))->roundUpToMultipleOf(Decimal::of($tick))
        : $open->times(Decimal::of(1)->minus($slippage))->roundDownToMultipleOf(Decimal::of($tick));
}

/** @return array<int, Candle> minutes by open time */
function goldenMinutes(MarketType $market): array
{
    $minutes = [];

    foreach (GoldenData::candles('BTCUSDT', $market) as $candle) {
        $minutes[$candle->openTime->getTimestamp()] = $candle;
    }

    return $minutes;
}

/** @return array<int, Decimal> funding rates by funding time */
function goldenRates(): array
{
    $rates = [];

    foreach (GoldenData::funding() as $rate) {
        $rates[$rate->fundingTime->getTimestamp()] = $rate->rate;
    }

    return $rates;
}

/**
 * @param  list<Decimal>  $values
 */
function referenceMean(array $values): Decimal
{
    $sum = Decimal::zero();

    foreach ($values as $value) {
        $sum = $sum->plus($value);
    }

    return $sum->dividedBy(Decimal::of(count($values)), 18);
}

/** @return list<array<array-key, mixed>> the report's round trips as decoded JSON */
function reportTrades(Report $report): array
{
    $decoded = json_decode($report->json(), true, flags: JSON_THROW_ON_ERROR);
    $trades = [];

    foreach (is_array($decoded) && is_array($decoded['trades'] ?? null) ? $decoded['trades'] : [] as $trip) {
        $trades[] = is_array($trip) ? $trip : [];
    }

    return $trades;
}

it('shorts the 12/48 hourly trend rule on the perpetual exactly as an independent reference, funding included', function (): void {
    $from = CarbonImmutable::parse('2026-10-03', 'UTC');
    $report = GoldenData::run(
        MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h', 'side' => 'short'], [Symbol::parse('BTC/USDT:USDT')]),
        RiskPolicy::benchmark(),
        '2026-10-03',
    );

    $minutes = goldenMinutes(MarketType::Perp);
    $rates = goldenRates();
    // The USD-M wallet, the margin of an open position included: margin comes back whole when it closes.
    $wallet = Decimal::of('10000');
    $quantity = Decimal::zero();
    $entry = Decimal::zero();
    $funding = Decimal::zero();
    $closes = [];
    $trades = [];
    $open = [];
    $fee = Decimal::of('0.0005');

    foreach ($minutes as $time => $minute) {
        $closedAt = $time + 60;

        // Funding at the end of a minute, on the position open then, at that minute's close; a short receives (D-44).
        if (isset($rates[$closedAt]) && $quantity->isPositive()) {
            $amount = $quantity->times($minute->close->value)->times($rates[$closedAt]);
            $wallet = $wallet->plus($amount);
            $funding = $funding->plus($amount);
        }

        if ($closedAt % 3600 !== 0) {
            continue;
        }

        $closes[] = $minute->close->value;
        $next = $minutes[$closedAt] ?? null;

        if (count($closes) < 48 || $closedAt < $from->getTimestamp() || $next === null) {
            continue;
        }

        $below = referenceMean(array_slice($closes, -12))->isLessThan(referenceMean(array_slice($closes, -48)));

        if ($below && $quantity->isZero()) {
            // The benchmark profile: 99 % of the free wallet at 1x, at the decision price (D-50).
            $quantity = $wallet->times(Decimal::of('0.99'))->dividedBy($minute->close->value, 18)->roundDownToMultipleOf(Decimal::of('0.001'));
            $entry = perpetualReferencePrice($next, $quantity, false, '0.1');
            $wallet = $wallet->minus($quantity->times($entry)->times($fee));
            $funding = Decimal::zero();
            $open = [$next->openTime->format('Y-m-d\TH:i:s\Z'), (string) $entry];
        } elseif (! $below && $quantity->isPositive()) {
            $exit = perpetualReferencePrice($next, $quantity, true, '0.1');
            $wallet = $wallet->minus($quantity->times($exit->minus($entry)))->minus($quantity->times($exit)->times($fee));
            $trades[] = [...$open, $next->openTime->format('Y-m-d\TH:i:s\Z'), (string) $exit, 'sell', (string) $funding];
            $quantity = Decimal::zero();
        }
    }

    $last = $minutes[CarbonImmutable::parse('2026-10-10 23:59', 'UTC')->getTimestamp()];
    $final = $wallet->minus($quantity->times($last->close->value->minus($entry)));
    $engineTrades = array_map(fn (array $trip): array => [
        $trip['opened_at'] ?? '', $trip['entry_price'] ?? '', $trip['closed_at'] ?? '', $trip['exit_price'] ?? '', $trip['side'] ?? '', $trip['funding'] ?? '',
    ], reportTrades($report));

    expect($engineTrades)->toBe($trades)
        ->and(count($trades))->toBeGreaterThan(1)
        ->and((string) $report->metrics->finalEquity)->toBe((string) $final);
});

it('carries funding exactly as the closed form says: spot and perpetual legs, funding and fees', function (): void {
    $from = CarbonImmutable::parse('2026-10-04', 'UTC');
    $strategy = FundingCarry::build([...FundingCarry::defaults(), 'entry' => '0.00003'], [Symbol::parse('BTC/USDT:USDT')]);
    $report = GoldenData::run($strategy, RiskPolicy::account(config()->array('risk.account')), '2026-10-04');

    $spotMinutes = goldenMinutes(MarketType::Spot);
    $perpMinutes = goldenMinutes(MarketType::Perp);
    $rates = goldenRates();
    // Both markets: the equity starts half in each wallet (D-49).
    $cash = Decimal::of('5000');
    $wallet = Decimal::of('5000');
    $held = Decimal::zero();
    $dust = Decimal::zero();
    $hedge = Decimal::zero();
    $hedgeEntry = Decimal::zero();
    $funding = Decimal::zero();
    $seen = [];
    $trips = [];
    $spotTrip = [];
    $perpTrip = [];

    foreach ($perpMinutes as $time => $minute) {
        $closedAt = $time + 60;
        $spot = $spotMinutes[$time];

        if (isset($rates[$closedAt]) && $hedge->isPositive()) {
            $amount = $hedge->times($minute->close->value)->times($rates[$closedAt]);
            $wallet = $wallet->plus($amount);
            $funding = $funding->plus($amount);
        }

        if ($closedAt % 3600 !== 0) {
            continue;
        }

        // The strategy is called every hour of the run and remembers each funding rate it is shown.
        if (isset($rates[$closedAt])) {
            $seen[] = $rates[$closedAt];
        }

        $nextPerp = $perpMinutes[$closedAt] ?? null;
        $nextSpot = $spotMinutes[$closedAt] ?? null;

        if ($closedAt < $from->getTimestamp() || $nextPerp === null || $nextSpot === null) {
            continue;
        }

        if ($hedge->isZero() && count($seen) >= 9 && referenceMean(array_slice($seen, -9))->isGreaterThan(Decimal::of('0.00003'))) {
            // D-48: 25 % of equity per leg (the account's notional cap is below the template's 30 %), at most what the
            // spot cash and the perpetual margin at 1x (floor(2 / 1.5)) can pay with a 1 % buffer.
            $equity = $cash->plus($dust->times($spot->close->value))->plus($wallet);
            $quantity = $equity->times(Decimal::of('0.25'))->dividedBy($spot->close->value, 18);
            foreach ([$cash->dividedBy($spot->close->value->times(Decimal::of('1.01')), 18), $wallet->dividedBy($minute->close->value->times(Decimal::of('1.01')), 18)] as $limit) {
                $quantity = $quantity->isGreaterThan($limit) ? $limit : $quantity;
            }

            $quantity = $quantity->roundDownToMultipleOf(Decimal::of('0.001'));

            $spotEntry = perpetualReferencePrice($nextSpot, $quantity, true, '0.01');
            $cash = $cash->minus($quantity->times($spotEntry));
            $held = $quantity->minus($quantity->times(Decimal::of('0.001')));
            $hedgeEntry = perpetualReferencePrice($nextPerp, $quantity, false, '0.1');
            $hedge = $quantity;
            $wallet = $wallet->minus($hedge->times($hedgeEntry)->times(Decimal::of('0.0005')));
            $funding = Decimal::zero();
            $spotTrip = ['BTC/USDT', $nextSpot->openTime->format('Y-m-d\TH:i:s\Z'), (string) $spotEntry];
            $perpTrip = ['BTC/USDT:USDT', $nextPerp->openTime->format('Y-m-d\TH:i:s\Z'), (string) $hedgeEntry];
        } elseif ($hedge->isPositive() && referenceMean(array_slice($seen, -3))->isLessThan(Decimal::zero())) {
            $sold = $held->roundDownToMultipleOf(Decimal::of('0.00001'));
            $spotExit = perpetualReferencePrice($nextSpot, $sold, false, '0.01');
            $proceeds = $sold->times($spotExit);
            $cash = $cash->plus($proceeds)->minus($proceeds->times(Decimal::of('0.001')));
            $dust = $dust->plus($held->minus($sold));
            $held = Decimal::zero();
            $hedgeExit = perpetualReferencePrice($nextPerp, $hedge, true, '0.1');
            $wallet = $wallet->minus($hedge->times($hedgeExit->minus($hedgeEntry)))->minus($hedge->times($hedgeExit)->times(Decimal::of('0.0005')));
            $hedge = Decimal::zero();
            $closedTime = $nextPerp->openTime->format('Y-m-d\TH:i:s\Z');
            $trips[] = [...$spotTrip, $closedTime, (string) $spotExit, ''];
            $trips[] = [...$perpTrip, $closedTime, (string) $hedgeExit, (string) $funding];
        }
    }

    $lastTime = CarbonImmutable::parse('2026-10-10 23:59', 'UTC')->getTimestamp();
    $final = $cash->plus($held->plus($dust)->times($spotMinutes[$lastTime]->close->value))
        ->plus($wallet)
        ->minus($hedge->times($perpMinutes[$lastTime]->close->value->minus($hedgeEntry)));
    $engineTrips = array_map(fn (array $trip): array => [
        $trip['symbol'] ?? '', $trip['opened_at'] ?? '', $trip['entry_price'] ?? '', $trip['closed_at'] ?? '', $trip['exit_price'] ?? '', $trip['funding'] ?? '',
    ], reportTrades($report));
    usort($engineTrips, fn (array $a, array $b): int => [$a[3], $a[0]] <=> [$b[3], $b[0]]);

    expect($engineTrips)->toBe($trips)
        ->and(count($trips))->toBeGreaterThanOrEqual(2)
        ->and((string) $report->metrics->finalEquity)->toBe((string) $final);
});
