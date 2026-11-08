<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Risk\RiskPolicy;
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\GoldenData;

/*
 * The baselines must reproduce known results on real data (docs/README.md, Phase 2): buy and hold equals its closed
 * form, and the trend rule equals an independent reference written here from the rules of docs/simulation.md,
 * section 3, without the engine, the venue or the ledger.
 */

const TICK = '0.01';
const STEP = '0.00001';

/** Market fill price of docs/simulation.md, section 3: the open moved by half spread and impact, rounded against the order. */
function referencePrice(Candle $minute, Decimal $quantity, bool $buying): Decimal
{
    $participation = $minute->volume->isPositive() ? $quantity->dividedBy($minute->volume, 18) : Decimal::of(1);
    $participation = $participation->isGreaterThan(Decimal::of(1)) ? Decimal::of(1) : $participation;
    $slippage = Decimal::of('0.0002')->plus(Decimal::of('0.1')->times($participation));
    $open = $minute->open->value;

    return $buying
        ? $open->times(Decimal::of(1)->plus($slippage))->roundUpToMultipleOf(Decimal::of(TICK))
        : $open->times(Decimal::of(1)->minus($slippage))->roundDownToMultipleOf(Decimal::of(TICK));
}

/** @return array<int, Candle> BTC minutes by open time */
function btcMinutes(): array
{
    $minutes = [];

    foreach (GoldenData::candles('BTCUSDT') as $candle) {
        $minutes[$candle->openTime->getTimestamp()] = $candle;
    }

    return $minutes;
}

it('buys and holds exactly as the closed form says', function (): void {
    $report = GoldenData::run(BuyAndHold::build([], [Symbol::parse('BTC/USDT')]), RiskPolicy::benchmark(), '2026-10-02');
    $minutes = btcMinutes();
    // Decided when the first minute of the window closes, filled at the open of the next one.
    $entry = $minutes[CarbonImmutable::parse('2026-10-02 00:01', 'UTC')->getTimestamp()];
    $last = $minutes[CarbonImmutable::parse('2026-10-10 23:59', 'UTC')->getTimestamp()];
    $quote = Decimal::of('10000');
    $price = referencePrice($entry, $quote->dividedBy($entry->open->value, 18), true);
    $quantity = $quote->dividedBy($price, 18)->roundDownToMultipleOf(Decimal::of(STEP));
    $held = $quantity->minus($quantity->times(Decimal::of('0.001')));
    $final = $quote->minus($quantity->times($price))->plus($held->times($last->close->value));

    expect((string) $report->metrics->finalEquity)->toBe((string) $final)
        ->and($report->content)->toHaveKey('open_positions.0.entry_price', (string) $price)
        ->and($report->content)->toHaveKey('open_positions.0.quantity', (string) $held);
});

it('follows the 12/48 hourly trend rule exactly as an independent reference', function (): void {
    $from = CarbonImmutable::parse('2026-10-03', 'UTC');
    $report = GoldenData::run(
        MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]),
        RiskPolicy::benchmark(),
        '2026-10-03',
    );

    $minutes = btcMinutes();
    $closes = [];
    $quote = Decimal::of('10000');
    $position = Decimal::zero();
    $dust = Decimal::zero();
    $trades = [];
    $open = null;
    $average = function (array $values): Decimal {
        $sum = Decimal::zero();

        foreach ($values as $value) {
            $sum = $sum->plus($value instanceof Decimal ? $value : Decimal::zero());
        }

        return $sum->dividedBy(Decimal::of(count($values)), 18);
    };

    foreach ($minutes as $time => $minute) {
        if (($time + 60) % 3600 !== 0) {
            continue;
        }

        // The hour closes with this minute; the decision is made then, and fills at the next minute's open.
        $closes[] = $minute->close->value;
        $decidedAt = $time + 60;
        $next = $minutes[$decidedAt] ?? null;

        if (count($closes) < 48 || $decidedAt < $from->getTimestamp() || $next === null) {
            continue;
        }

        $fast = $average(array_slice($closes, -12));
        $slow = $average(array_slice($closes, -48));

        if ($fast->isGreaterThan($slow) && $position->isZero()) {
            $price = referencePrice($next, $quote->dividedBy($next->open->value, 18), true);
            $bought = $quote->dividedBy($price, 18)->roundDownToMultipleOf(Decimal::of(STEP));
            $quote = $quote->minus($bought->times($price));
            $position = $bought->minus($bought->times(Decimal::of('0.001')));
            $open = [$next->openTime->format('Y-m-d\TH:i:s\Z'), (string) $price];
        } elseif (! $fast->isGreaterThan($slow) && $position->isPositive()) {
            $sold = $position->roundDownToMultipleOf(Decimal::of(STEP));
            $price = referencePrice($next, $sold, false);
            $proceeds = $sold->times($price);
            $quote = $quote->plus($proceeds)->minus($proceeds->times(Decimal::of('0.001')));
            $dust = $dust->plus($position->minus($sold));
            $position = Decimal::zero();
            $trades[] = [...($open ?? []), $next->openTime->format('Y-m-d\TH:i:s\Z'), (string) $price];
        }
    }

    $last = $minutes[CarbonImmutable::parse('2026-10-10 23:59', 'UTC')->getTimestamp()];
    $final = $quote->plus($position->plus($dust)->times($last->close->value));
    $engineTrades = [];

    $decoded = json_decode($report->json(), true, flags: JSON_THROW_ON_ERROR);

    foreach (is_array($decoded) && is_array($decoded['trades'] ?? null) ? $decoded['trades'] : [] as $trip) {
        $engineTrades[] = is_array($trip) ? [$trip['opened_at'] ?? '', $trip['entry_price'] ?? '', $trip['closed_at'] ?? '', $trip['exit_price'] ?? ''] : [];
    }

    expect($engineTrades)->toBe($trades)
        ->and(count($trades))->toBeGreaterThan(1)
        ->and((string) $report->metrics->finalEquity)->toBe((string) $final);
});
