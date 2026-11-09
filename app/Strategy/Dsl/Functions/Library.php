<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Dsl\Types\Type;
use App\Strategy\Dsl\Types\Unit;

/** The functions and names of the DSL's first version (docs/strategy/configurator.md, section 2; D-52). */
final class Library
{
    /** @var array<string, FunctionSpec>|null */
    private static ?array $specs = null;

    public static function get(string $name): ?FunctionSpec
    {
        return self::all()[$name] ?? null;
    }

    /** @return array<string, FunctionSpec> */
    public static function all(): array
    {
        if (self::$specs !== null) {
            return self::$specs;
        }

        $price = Type::series(Unit::Price);
        $plain = Type::series(Unit::Plain);
        $fraction = Type::series(Unit::Fraction);
        $condition = Type::of(Kind::Condition);
        $count = fn (string $name, ?string $default = null): ParamSpec => new ParamSpec($name, ParamKind::Count, $default);
        $number = fn (string $name, ?string $default = null): ParamSpec => new ParamSpec($name, ParamKind::Number, $default);
        $src = new ParamSpec('src', ParamKind::Series, 'close');
        $specs = [];

        foreach (['open', 'high', 'low', 'close', 'hl2', 'hlc3', 'ohlc4'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Price, $price, isCall: false);
        }

        $specs[] = new FunctionSpec('volume', Group::Price, Type::series(Unit::Volume), isCall: false);

        // The candle's volume bought and sold by takers, and their difference (D-79).
        foreach (['buy_volume', 'sell_volume', 'delta'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Price, Type::series(Unit::Volume), isCall: false);
        }

        foreach (['sma', 'ema', 'wma', 'hma'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Indicator, null, [$count('n'), $src], followsSource: true);
        }

        $specs[] = new FunctionSpec('vwma', Group::Indicator, $price, [$count('n')]);
        $specs[] = new FunctionSpec('vwap', Group::Indicator, $price, [new ParamSpec('anchor', ParamKind::Anchor, '1d')]);
        $specs[] = new FunctionSpec('rsi', Group::Indicator, $plain, [$count('n', '14'), $src]);
        $specs[] = new FunctionSpec('roc', Group::Indicator, $plain, [$count('n'), $src]);
        $specs[] = new FunctionSpec('macd', Group::Indicator, null, [$count('fast', '12'), $count('slow', '26'), $count('signal', '9')], ['line' => $price, 'signal' => $price, 'hist' => $price]);
        $specs[] = new FunctionSpec('stoch', Group::Indicator, null, [$count('k', '14'), $count('d', '3')], ['k' => $plain, 'd' => $plain]);
        $specs[] = new FunctionSpec('atr', Group::Indicator, $price, [$count('n', '14')]);
        $specs[] = new FunctionSpec('natr', Group::Indicator, $plain, [$count('n', '14')]);
        $specs[] = new FunctionSpec('stdev', Group::Indicator, null, [$count('n'), $src], followsSource: true);
        $specs[] = new FunctionSpec('realized_vol', Group::Indicator, $fraction, [$count('n')]);
        $specs[] = new FunctionSpec('bb', Group::Indicator, null, [$count('n', '20'), $number('k', '2'), $src], ['upper' => $price, 'middle' => $price, 'lower' => $price, 'width' => $fraction], followsSource: true);
        $specs[] = new FunctionSpec('donchian', Group::Indicator, null, [$count('n')], ['upper' => $price, 'middle' => $price, 'lower' => $price]);
        $specs[] = new FunctionSpec('keltner', Group::Indicator, null, [$count('n', '20'), $number('k', '2')], ['upper' => $price, 'middle' => $price, 'lower' => $price]);
        $specs[] = new FunctionSpec('adx', Group::Indicator, $plain, [$count('n', '14')]);
        $specs[] = new FunctionSpec('plus_di', Group::Indicator, $plain, [$count('n', '14')]);
        $specs[] = new FunctionSpec('minus_di', Group::Indicator, $plain, [$count('n', '14')]);
        $specs[] = new FunctionSpec('supertrend', Group::Indicator, null, [$count('n', '10'), $number('k', '3')], ['line' => $price, 'direction' => $plain]);
        $specs[] = new FunctionSpec('obv', Group::Indicator, Type::series(Unit::Volume));
        $specs[] = new FunctionSpec('cvd', Group::Indicator, Type::series(Unit::Volume), [$count('n')]);
        foreach (['vp_poc', 'vp_vah', 'vp_val'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Indicator, $price, [$count('n')]);
        }

        $specs[] = new FunctionSpec('highest', Group::Indicator, null, [new ParamSpec('src', ParamKind::Series), $count('n')], followsSource: true);
        $specs[] = new FunctionSpec('lowest', Group::Indicator, null, [new ParamSpec('src', ParamKind::Series), $count('n')], followsSource: true);
        $specs[] = new FunctionSpec('change', Group::Indicator, null, [new ParamSpec('src', ParamKind::Series), $count('n')], followsSource: true);

        $specs[] = new FunctionSpec('funding_rate', Group::Perpetual, $fraction, isCall: false);
        $specs[] = new FunctionSpec('funding_rate_avg', Group::Perpetual, $fraction, [$count('n')]);
        $specs[] = new FunctionSpec('open_interest', Group::Perpetual, $plain, isCall: false);
        $specs[] = new FunctionSpec('oi_change', Group::Perpetual, $fraction, [$count('n')]);
        $specs[] = new FunctionSpec('basis', Group::Perpetual, $fraction, isCall: false);
        $specs[] = new FunctionSpec('mark_price', Group::Perpetual, $price, isCall: false);

        foreach (['long_short_ratio', 'top_trader_ratio', 'taker_ratio'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Perpetual, $plain, isCall: false);
        }

        foreach (['large_buy_volume', 'large_sell_volume', 'large_delta'] as $name) {
            $specs[] = new FunctionSpec($name, Group::Perpetual, Type::series(Unit::Volume), isCall: false);
        }

        $specs[] = new FunctionSpec('in_position', Group::Position, $condition, isCall: false);
        $specs[] = new FunctionSpec('bars_in_trade', Group::Position, $plain, isCall: false);
        $specs[] = new FunctionSpec('trade_r', Group::Position, $plain, isCall: false);
        $specs[] = new FunctionSpec('hour_utc', Group::Time, $plain, isCall: false);
        $specs[] = new FunctionSpec('weekday', Group::Time, $plain, isCall: false);

        $specs[] = new FunctionSpec('level', Group::Exit, Type::of(Kind::Stop), [new ParamSpec('series', ParamKind::Series)]);
        $specs[] = new FunctionSpec('chandelier', Group::Exit, Type::of(Kind::Trail), [$count('n', '22'), $number('k', '3')]);
        $specs[] = new FunctionSpec('atr_trail', Group::Exit, Type::of(Kind::Trail), [$count('n', '14'), $number('k', '3')]);

        $specs[] = new FunctionSpec('market', Group::Order, Type::of(Kind::Order), isCall: false);
        $specs[] = new FunctionSpec('limit', Group::Order, Type::of(Kind::Order), [new ParamSpec('offset', ParamKind::Percent), new ParamSpec('expiry', ParamKind::Duration, '1 bars')]);

        $byName = [];

        foreach ($specs as $spec) {
            $byName[$spec->name] = $spec;
        }

        return self::$specs = $byName;
    }
}
