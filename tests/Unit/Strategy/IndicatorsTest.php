<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Strategy\Indicators\Atr;
use App\Strategy\Indicators\Donchian;
use App\Strategy\Indicators\Ema;
use App\Strategy\Indicators\Sma;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\CandleFactory;

mutates(Sma::class, Ema::class, Atr::class, Donchian::class);

/** @return list<?string> */
function feedValues(Sma|Ema $indicator, string ...$values): array
{
    return array_values(array_map(fn (string $value): ?string => ($result = $indicator->push(Decimal::of($value))) === null ? null : (string) $result, $values));
}

function bar(int $i, string $high, string $low, string $close): Candle
{
    return Candle::fromStrings(
        CarbonImmutable::parse('2026-10-01', 'UTC')->addHours($i), Timeframe::OneHour, $close, $high, $low, $close, '1', '1', 1, '0',
    );
}

it('averages the last n values', function (): void {
    expect(feedValues(new Sma(3), '1', '2', '3', '4', '10'))->toBe([null, null, '2', '3', '5.666666666666666666']);
});

it('seeds an EMA with the SMA of its first n values, then smooths with 2 / (n + 1)', function (): void {
    expect(feedValues(new Ema(3), '1', '2', '3', '4', '5', '1'))->toBe([null, null, '2', '3', '4', '2.5']);
});

it('keeps the last value readable', function (): void {
    $sma = new Sma(2);

    expect($sma->value())->toBeNull();

    $sma->push(Decimal::of('4'));
    $sma->push(Decimal::of('6'));

    expect((string) $sma->value())->toBe('5');
});

it('matches a full recomputation over a long series', function (): void {
    $sma = new Sma(20);
    $ema = new Ema(10);
    $closes = [];
    $emaReference = null;
    $alpha = Decimal::of(2)->dividedBy(Decimal::of(11), 18);

    foreach (CandleFactory::walk('2026-10-01 00:00:00', 300, seed: 13) as $i => $minute) {
        $close = $minute->close->value;
        $closes[] = $close;
        $ours = $sma->push($close);
        $oursEma = $ema->push($close);

        if ($i >= 19) {
            $window = array_slice($closes, -20);
            $sum = array_reduce($window, fn (Decimal $carry, Decimal $v): Decimal => $carry->plus($v), Decimal::zero());
            expect((string) $ours)->toBe((string) $sum->dividedBy(Decimal::of(20), 18));
        }

        if ($i === 9) {
            $seed = array_reduce($closes, fn (Decimal $carry, Decimal $v): Decimal => $carry->plus($v), Decimal::zero());
            $emaReference = $seed->dividedBy(Decimal::of(10), 18);
        } elseif ($emaReference !== null) {
            $emaReference = $emaReference->plus($alpha->times($close->minus($emaReference)))->truncatedTo(18);
        }

        expect($oursEma === null ? null : (string) $oursEma)->toBe($emaReference === null ? null : (string) $emaReference);
    }
});

it('computes Wilder\'s ATR from true ranges', function (): void {
    $atr = new Atr(3);
    $values = array_map(fn (Candle $bar): ?string => ($v = $atr->push($bar)) === null ? null : (string) $v, [
        bar(0, '10', '8', '9'),     // TR 2 (first bar: high - low)
        bar(1, '12', '9', '11'),    // TR max(3, |12 - 9|, |9 - 9|) = 3
        bar(2, '11', '10', '10.5'), // TR max(1, 0, 1) = 1; ATR = (2 + 3 + 1) / 3 = 2
        bar(3, '15', '11', '14'),   // TR max(4, 4.5, 0.5) = 4.5; ATR = (2 * 2 + 4.5) / 3
        bar(4, '13', '12', '12.5'), // TR max(1, 1, 2) = 2; ATR = (2.833... * 2 + 2) / 3
    ]);

    expect($values)->toBe([null, null, '2', '2.833333333333333333', '2.555555555555555555'])
        ->and((string) $atr->value())->toBe('2.555555555555555555');
});

it('gives the highest high and lowest low of the last n bars', function (): void {
    $channel = new Donchian(3);

    $channel->push(bar(0, '10', '8', '9'));
    $channel->push(bar(1, '12', '9', '11'));

    expect($channel->upper())->toBeNull()
        ->and($channel->lower())->toBeNull();

    $channel->push(bar(2, '11', '7', '10'));

    expect((string) $channel->upper())->toBe('12')
        ->and((string) $channel->lower())->toBe('7');

    $channel->push(bar(3, '9', '8.5', '9'));
    $channel->push(bar(4, '9.5', '8.8', '9'));

    expect((string) $channel->upper())->toBe('11')
        ->and((string) $channel->lower())->toBe('7');

    $channel->push(bar(5, '9.4', '8.9', '9'));

    expect((string) $channel->upper())->toBe('9.5')
        ->and((string) $channel->lower())->toBe('8.5');
});

it('refuses a period below one', function (callable $build): void {
    $build();
})->with([
    'sma' => [fn () => new Sma(0)],
    'ema' => [fn () => new Ema(0)],
    'atr' => [fn () => new Atr(0)],
    'donchian' => [fn () => new Donchian(0)],
])->throws(InvalidArgumentException::class);

it('keeps the last EMA readable and averages a first ATR that does not divide evenly', function (): void {
    $ema = new Ema(2);
    $ema->push(Decimal::of('1'));
    $ema->push(Decimal::of('2'));

    $atr = new Atr(3);
    $atr->push(bar(0, '10', '8', '9'));   // TR 2
    $atr->push(bar(1, '12', '9', '11'));  // TR 3
    $first = $atr->push(bar(2, '12', '10', '11')); // TR 2

    expect((string) $ema->value())->toBe('1.5')
        ->and((string) $first)->toBe('2.333333333333333333');
});
