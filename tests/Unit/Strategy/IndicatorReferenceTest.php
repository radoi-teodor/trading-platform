<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Strategy\Indicators\Atr;
use App\Strategy\Indicators\Bollinger;
use App\Strategy\Indicators\Change;
use App\Strategy\Indicators\DirectionalMovement;
use App\Strategy\Indicators\DonchianChannel;
use App\Strategy\Indicators\Ema;
use App\Strategy\Indicators\Highest;
use App\Strategy\Indicators\Hma;
use App\Strategy\Indicators\Keltner;
use App\Strategy\Indicators\Lowest;
use App\Strategy\Indicators\Macd;
use App\Strategy\Indicators\Natr;
use App\Strategy\Indicators\RealizedVolatility;
use App\Strategy\Indicators\Roc;
use App\Strategy\Indicators\Rsi;
use App\Strategy\Indicators\Sma;
use App\Strategy\Indicators\StandardDeviation;
use App\Strategy\Indicators\Stochastic;
use App\Strategy\Indicators\Supertrend;
use App\Strategy\Indicators\Vwap;
use App\Strategy\Indicators\Vwma;
use App\Strategy\Indicators\Wma;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

/*
 * Every indicator of the DSL's first library (D-52) against values computed independently with Python's decimal
 * module at 60 digits (tests/Fixtures/indicators/generate.py). Divisions here truncate at 18 decimals, so values
 * must agree to 1e-9; warmup must agree exactly.
 */

mutates(
    Sma::class, Ema::class, Wma::class, Hma::class, Vwma::class, Vwap::class, Rsi::class, Roc::class, Macd::class,
    Stochastic::class, Atr::class, Natr::class, StandardDeviation::class, RealizedVolatility::class, Bollinger::class,
    DonchianChannel::class, Keltner::class, DirectionalMovement::class, Supertrend::class, Highest::class,
    Lowest::class, Change::class,
);

/** @return array{bars: list<Candle>, cases: array<string, array{string, array<string, string>, array<string, list<?string>>}>} */
function indicatorReference(): array
{
    $json = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/Fixtures/indicators/reference.json'), true, flags: JSON_THROW_ON_ERROR);
    $field = function (mixed $object, string $key): mixed {
        if (! is_array($object) || ! array_key_exists($key, $object)) {
            throw new UnexpectedValueException("reference.json: missing {$key}.");
        }

        return $object[$key];
    };
    $text = fn (mixed $object, string $key): string => is_string($value = $field($object, $key)) ? $value : throw new UnexpectedValueException("reference.json: {$key} is not text.");
    $list = fn (mixed $object, string $key): array => is_array($value = $field($object, $key)) ? $value : throw new UnexpectedValueException("reference.json: {$key} is not a list.");
    $bars = [];
    $cases = [];

    foreach ($list($json, 'bars') as $bar) {
        $time = $field($bar, 'time');
        $bars[] = Candle::fromStrings(
            CarbonImmutable::createFromTimestampUTC(is_int($time) ? $time : 0), Timeframe::OneHour,
            $text($bar, 'open'), $text($bar, 'high'), $text($bar, 'low'), $text($bar, 'close'), $text($bar, 'volume'), '0', 1, '0',
        );
    }

    foreach ($list($json, 'cases') as $case) {
        $arguments = array_map(fn (mixed $value): string => is_scalar($value) ? (string) $value : '', $list($case, 'arguments'));
        $values = [];

        foreach ($list($case, 'values') as $name => $series) {
            $values[(string) $name] = array_values(array_map(fn (mixed $value): ?string => is_string($value) ? $value : null, is_array($series) ? $series : []));
        }

        $function = $text($case, 'function');
        $cases[$function.' '.json_encode($arguments)] = [$function, array_combine(array_map('strval', array_keys($arguments)), $arguments), $values];
    }

    return ['bars' => $bars, 'cases' => $cases];
}

/**
 * Feeds every bar to the indicator of a case and returns its outputs per bar, by field.
 *
 * @param  array<string, string>  $a
 * @param  list<Candle>  $bars
 * @return array<string, list<?Decimal>>
 */
function runIndicator(string $function, array $a, array $bars): array
{
    $n = fn (string $key): int => (int) $a[$key];
    $k = fn (): Decimal => Decimal::of($a['k']);
    $close = fn (Candle $bar): Decimal => $bar->close->value;
    $hl2 = fn (Candle $bar): Decimal => $bar->high->value->plus($bar->low->value)->dividedBy(Decimal::of(2), 18);
    $src = ($a['src'] ?? 'close') === 'hl2' ? $hl2 : $close;

    [$indicator, $input] = match ($function) {
        'sma' => [new Sma($n('n')), 'src'],
        'ema' => [new Ema($n('n')), 'src'],
        'wma' => [new Wma($n('n')), 'src'],
        'hma' => [new Hma($n('n')), 'src'],
        'vwma' => [new Vwma($n('n')), 'bar'],
        'vwap' => [new Vwap(Timeframe::from($a['anchor'])), 'bar'],
        'rsi' => [new Rsi($n('n')), 'src'],
        'roc' => [new Roc($n('n')), 'src'],
        'macd' => [new Macd($n('fast'), $n('slow'), $n('signal')), 'src'],
        'stoch' => [new Stochastic($n('k'), $n('d')), 'bar'],
        'atr' => [new Atr($n('n')), 'bar'],
        'natr' => [new Natr($n('n')), 'bar'],
        'stdev' => [new StandardDeviation($n('n')), 'src'],
        'realized_vol' => [new RealizedVolatility($n('n'), Timeframe::from($a['timeframe'])), 'src'],
        'bb' => [new Bollinger($n('n'), $k()), 'src'],
        'donchian' => [new DonchianChannel($n('n')), 'bar'],
        'keltner' => [new Keltner($n('n'), $k()), 'bar'],
        'dmi' => [new DirectionalMovement($n('n')), 'bar'],
        'supertrend' => [new Supertrend($n('n'), $k()), 'bar'],
        'highest' => [new Highest($n('n')), 'src'],
        'lowest' => [new Lowest($n('n')), 'src'],
        'change' => [new Change($n('n')), 'src'],
        default => throw new InvalidArgumentException("No indicator {$function}."),
    };
    $outputs = [];

    foreach ($bars as $bar) {
        $result = $indicator->push($input === 'bar' ? $bar : $src($bar));

        foreach (is_array($result) ? $result : ['value' => $result] as $field => $value) {
            $outputs[$field][] = $value;
        }
    }

    return $outputs;
}

/**
 * @param  array<string, string>  $arguments
 * @param  array<string, list<?string>>  $expected
 */
function assertMatchesReference(string $function, array $arguments, array $expected): void
{
    $actual = runIndicator($function, $arguments, indicatorReference()['bars']);
    $tolerance = Decimal::of('0.000000001');

    expect(array_keys($actual))->toEqualCanonicalizing(array_keys($expected));

    foreach ($expected as $field => $values) {
        foreach ($values as $bar => $value) {
            $got = $actual[$field][$bar];

            expect($got === null)->toBe($value === null, "{$function}.{$field} at bar {$bar}: warmup differs");

            if ($got !== null && $value !== null) {
                expect($got->minus(Decimal::of($value))->abs()->isGreaterThan($tolerance))
                    ->toBeFalse("{$function}.{$field} at bar {$bar}: {$got} instead of {$value}");
            }
        }
    }
}

it('matches the independent reference values', function (string $case): void {
    assertMatchesReference(...indicatorReference()['cases'][$case]);
})->with(fn (): array => array_keys(indicatorReference()['cases']));

it('refuses periods it cannot use', function (callable $build): void {
    $build();
})->with([
    'wma' => [fn () => new Wma(0)],
    'hma under 2' => [fn () => new Hma(1)],
    'macd fast not shorter' => [fn () => new Macd(26, 12, 9)],
    'realized vol under 2' => [fn () => new RealizedVolatility(1, Timeframe::OneHour)],
])->throws(InvalidArgumentException::class);
