<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Strategy\Dsl\Ast\Binary;
use App\Strategy\Dsl\Ast\BoolLiteral;
use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\Ast\DurationLiteral;
use App\Strategy\Dsl\Ast\Identifier;
use App\Strategy\Dsl\Ast\Lookback;
use App\Strategy\Dsl\Ast\Node;
use App\Strategy\Dsl\Ast\NumberLiteral;
use App\Strategy\Dsl\Ast\OnTimeframe;
use App\Strategy\Dsl\Ast\Parameter;
use App\Strategy\Dsl\Ast\PercentLiteral;
use App\Strategy\Dsl\Ast\Unary;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Scope;
use App\Strategy\Dsl\TypeChecker;
use App\Strategy\Indicators\Atr;
use App\Strategy\Indicators\BarIndicator;
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
use App\Strategy\Indicators\Obv;
use App\Strategy\Indicators\RealizedVolatility;
use App\Strategy\Indicators\Roc;
use App\Strategy\Indicators\Rsi;
use App\Strategy\Indicators\Sma;
use App\Strategy\Indicators\StandardDeviation;
use App\Strategy\Indicators\Stochastic;
use App\Strategy\Indicators\Supertrend;
use App\Strategy\Indicators\ValueIndicator;
use App\Strategy\Indicators\VolumeProfile;
use App\Strategy\Indicators\Vwap;
use App\Strategy\Indicators\Vwma;
use App\Strategy\Indicators\Wma;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Timeframe;
use Closure;
use LogicException;

/**
 * Turns checked expressions into closures evaluated once per closed candle (D-53). Every value is a Decimal, a
 * bool, a text or null while an expression warms up; a null condition is false where a decision reads it. Indicators
 * keep their state inside the closures, so each instrument gets its own Program.
 */
final class Compiler
{
    private TypeChecker $types;

    /**
     * @param  array<string, Decimal>  $values  the parameter set this program runs with
     */
    public function __construct(private readonly Scope $scope, private readonly array $values)
    {
        $this->types = new TypeChecker;
    }

    /** Compiles $node on the strategy's timeframe into $program under $name. */
    public function add(Program $program, string $name, Node $node): void
    {
        $this->types->check($node, $this->scope->allowing(Group::Exit, Group::Order));
        $program->name($name, $this->compile($node, $this->scope->timeframe, $program));
    }

    /** The history $node needs before its value means anything, in seconds (D-53). */
    public function history(Node $node, ?Timeframe $timeframe = null): int
    {
        $timeframe ??= $this->scope->timeframe;
        $this->types->check($node, $this->scope->allowing(Group::Exit, Group::Order));

        return $this->seconds($node, $timeframe);
    }

    /** @return Closure(Frame): mixed */
    private function compile(Node $node, Timeframe $timeframe, Program $program): Closure
    {
        return match (true) {
            $node instanceof NumberLiteral => fn (): Decimal => $node->value,
            $node instanceof PercentLiteral => fn (): Decimal => $node->fraction,
            $node instanceof BoolLiteral => fn (): bool => $node->value,
            $node instanceof Parameter => fn (): Decimal => $this->value($node),
            $node instanceof Identifier => $this->identifier($node, $timeframe),
            $node instanceof Unary => $this->unary($node, $timeframe, $program),
            $node instanceof Binary => $this->binary($node, $timeframe, $program),
            $node instanceof Lookback => $this->lookback($node, $timeframe, $program),
            $node instanceof OnTimeframe => $this->onTimeframe($node, $timeframe, $program),
            $node instanceof Call => $this->call($node, $timeframe, $program),
            default => throw new LogicException('Cannot compile '.$node::class.'.'),
        };
    }

    /** @return Closure(Frame): mixed */
    private function identifier(Identifier $node, Timeframe $timeframe): Closure
    {
        $two = Decimal::of(2);
        $three = Decimal::of(3);

        return match ($node->name) {
            'open' => fn (Frame $f): Decimal => $f->candle->open->value,
            'high' => fn (Frame $f): Decimal => $f->candle->high->value,
            'low' => fn (Frame $f): Decimal => $f->candle->low->value,
            'close' => fn (Frame $f): Decimal => $f->candle->close->value,
            'volume' => fn (Frame $f): Decimal => $f->candle->volume,
            'hl2' => fn (Frame $f): Decimal => $f->candle->high->value->plus($f->candle->low->value)->dividedBy($two, 18),
            'hlc3' => fn (Frame $f): Decimal => $f->candle->high->value->plus($f->candle->low->value)->plus($f->candle->close->value)->dividedBy($three, 18),
            'ohlc4' => fn (Frame $f): Decimal => $f->candle->open->value->plus($f->candle->high->value)->plus($f->candle->low->value)->plus($f->candle->close->value)->dividedBy(Decimal::of(4), 18),
            'funding_rate' => fn (Frame $f): ?Decimal => $f->funding?->rate,
            'in_position' => fn (Frame $f): bool => $f->position !== null,
            'bars_in_trade' => fn (Frame $f): ?Decimal => $f->position === null ? null : Decimal::of(intdiv($f->time->getTimestamp() - $f->position->openedAt->getTimestamp(), $timeframe->seconds())),
            'trade_r' => fn (Frame $f): ?Decimal => self::tradeR($f),
            'hour_utc' => fn (Frame $f): Decimal => Decimal::of((int) $f->time->format('G')),
            'weekday' => fn (Frame $f): Decimal => Decimal::of((int) $f->time->format('N')),
            'none' => fn (): null => null,
            default => throw new LogicException("Cannot compile {$node->name}."),
        };
    }

    /** @return Closure(Frame): mixed */
    private function unary(Unary $node, Timeframe $timeframe, Program $program): Closure
    {
        $operand = $this->compile($node->operand, $timeframe, $program);

        return $node->operator === 'not'
            ? function (Frame $f) use ($operand): ?bool {
                $value = $operand($f);

                return is_bool($value) ? ! $value : null;
            }
        : function (Frame $f) use ($operand): ?Decimal {
            $value = $operand($f);

            return $value instanceof Decimal ? $value->negate() : null;
        };
    }

    /** @return Closure(Frame): mixed */
    private function binary(Binary $node, Timeframe $timeframe, Program $program): Closure
    {
        $left = $this->compile($node->left, $timeframe, $program);
        $right = $this->compile($node->right, $timeframe, $program);

        return match ($node->operator) {
            'and' => function (Frame $f) use ($left, $right): ?bool {
                [$a, $b] = [$left($f), $right($f)];

                return $a === false || $b === false ? false : ($a === true && $b === true ? true : null);
            },
            'or' => function (Frame $f) use ($left, $right): ?bool {
                [$a, $b] = [$left($f), $right($f)];

                return $a === true || $b === true ? true : ($a === false && $b === false ? false : null);
            },
            'crosses_above', 'crosses_below' => $this->cross($node->operator === 'crosses_above', $left, $right),
            '==', '!=' => function (Frame $f) use ($left, $right, $node): ?bool {
                [$a, $b] = [$left($f), $right($f)];

                if ($a === null || $b === null) {
                    return null;
                }

                $equal = $a instanceof Decimal && $b instanceof Decimal ? $a->equals($b) : $a === $b;

                return $node->operator === '==' ? $equal : ! $equal;
            },
            '>', '>=', '<', '<=' => function (Frame $f) use ($left, $right, $node): ?bool {
                [$a, $b] = [$left($f), $right($f)];

                if (! $a instanceof Decimal || ! $b instanceof Decimal) {
                    return null;
                }

                $order = $a->compare($b);

                return match ($node->operator) {
                    '>' => $order > 0,
                    '>=' => $order >= 0,
                    '<' => $order < 0,
                    default => $order <= 0,
                };
            },
            '+', '-', '*', '/' => function (Frame $f) use ($left, $right, $node): ?Decimal {
                [$a, $b] = [$left($f), $right($f)];

                if (! $a instanceof Decimal || ! $b instanceof Decimal) {
                    return null;
                }

                return match ($node->operator) {
                    '+' => $a->plus($b),
                    '-' => $a->minus($b),
                    '*' => $a->times($b)->truncatedTo(18),
                    default => $b->isZero() ? null : $a->dividedBy($b, 18),
                };
            },
            default => throw new LogicException("Cannot compile {$node->operator}."),
        };
    }

    /**
     * @param  Closure(Frame): mixed  $left
     * @param  Closure(Frame): mixed  $right
     * @return Closure(Frame): ?bool
     */
    private function cross(bool $above, Closure $left, Closure $right): Closure
    {
        $previous = null;

        return function (Frame $f) use ($above, $left, $right, &$previous): ?bool {
            [$a, $b] = [$left($f), $right($f)];
            $before = $previous;
            $previous = $a instanceof Decimal && $b instanceof Decimal ? [$a, $b] : null;

            if ($before === null || $previous === null) {
                return null;
            }

            return $above
                ? ! $before[0]->isGreaterThan($before[1]) && $a->isGreaterThan($b)
                : ! $before[0]->isLessThan($before[1]) && $a->isLessThan($b);
        };
    }

    /** @return Closure(Frame): mixed */
    private function lookback(Lookback $node, Timeframe $timeframe, Program $program): Closure
    {
        $series = $this->compile($node->series, $timeframe, $program);
        $history = [];
        $bars = $node->bars;

        return function (Frame $f) use ($series, &$history, $bars): mixed {
            $history[] = $series($f);

            if (count($history) > $bars + 1) {
                array_shift($history);
            }

            return count($history) === $bars + 1 ? $history[0] : null;
        };
    }

    /** @return Closure(Frame): mixed */
    private function onTimeframe(OnTimeframe $node, Timeframe $timeframe, Program $program): Closure
    {
        if ($node->timeframe === $timeframe) {
            return $this->compile($node->expression, $timeframe, $program);
        }

        $expression = $this->compile($node->expression, $node->timeframe, $program);
        $last = new class
        {
            public mixed $value = null;
        };
        $program->onTimeframe($node->timeframe->value, function (Frame $f) use ($expression, $last): void {
            $last->value = $expression($f);
        });

        return fn (): mixed => $last->value;
    }

    /** @return Closure(Frame): mixed */
    private function call(Call $node, Timeframe $timeframe, Program $program): Closure
    {
        $arguments = $this->types->arguments($node);
        $count = fn (string $name): int => (int) (string) $this->number($arguments[$name]);
        $number = fn (string $name): Decimal => $this->number($arguments[$name]);
        $series = fn (string $name): Closure => $this->compile($arguments[$name], $timeframe, $program);
        $field = $node->field;
        $onValue = function (ValueIndicator $indicator, Closure $source) use ($field): Closure {
            return function (Frame $f) use ($indicator, $source, $field): ?Decimal {
                $value = $source($f);

                return $value instanceof Decimal ? self::pick($indicator->push($value), $field) : null;
            };
        };
        $onBar = fn (BarIndicator $indicator): Closure => fn (Frame $f): ?Decimal => self::pick($indicator->push($f->candle), $field);

        return match ($node->name) {
            'sma' => $onValue(new Sma($count('n')), $series('src')),
            'ema' => $onValue(new Ema($count('n')), $series('src')),
            'wma' => $onValue(new Wma($count('n')), $series('src')),
            'hma' => $onValue(new Hma($count('n')), $series('src')),
            'rsi' => $onValue(new Rsi($count('n')), $series('src')),
            'roc' => $onValue(new Roc($count('n')), $series('src')),
            'stdev' => $onValue(new StandardDeviation($count('n')), $series('src')),
            'bb' => $onValue(new Bollinger($count('n'), $number('k')), $series('src')),
            'highest' => $onValue(new Highest($count('n')), $series('src')),
            'lowest' => $onValue(new Lowest($count('n')), $series('src')),
            'change' => $onValue(new Change($count('n')), $series('src')),
            'macd' => $onValue(new Macd($count('fast'), $count('slow'), $count('signal')), fn (Frame $f): Decimal => $f->candle->close->value),
            'realized_vol' => $onValue(new RealizedVolatility($count('n'), $timeframe), fn (Frame $f): Decimal => $f->candle->close->value),
            'vwma' => $onBar(new Vwma($count('n'))),
            'vwap' => $onBar(new Vwap($this->anchor($arguments['anchor']))),
            'stoch' => $onBar(new Stochastic($count('k'), $count('d'))),
            'atr' => $onBar(new Atr($count('n'))),
            'natr' => $onBar(new Natr($count('n'))),
            'obv' => $onBar(new Obv),
            'vp_poc', 'vp_vah', 'vp_val' => $this->volumeProfile(substr($node->name, 3), $count('n')),
            'donchian' => $onBar(new DonchianChannel($count('n'))),
            'keltner' => $onBar(new Keltner($count('n'), $number('k'))),
            'supertrend' => $onBar(new Supertrend($count('n'), $number('k'))),
            'adx', 'plus_di', 'minus_di' => $this->directional($node->name, $count('n')),
            'funding_rate_avg' => $this->fundingAverage($count('n')),
            'level' => $series('series'),
            'chandelier' => $this->chandelier($count('n'), $number('k')),
            'atr_trail' => $this->atrTrail($count('n'), $number('k')),
            default => throw new LogicException("Cannot compile {$node->name}()."),
        };
    }

    /** @return Closure(Frame): ?Decimal */
    private function directional(string $name, int $period): Closure
    {
        $indicator = new DirectionalMovement($period);

        return fn (Frame $f): ?Decimal => $indicator->push($f->candle)[$name];
    }

    /** @return Closure(Frame): ?Decimal the mean of the last n funding rates seen, one per funding time */
    private function fundingAverage(int $periods): Closure
    {
        $rates = [];
        $last = null;

        return function (Frame $f) use ($periods, &$rates, &$last): ?Decimal {
            $rate = $f->funding;

            if ($rate !== null && ($last === null || $rate->fundingTime->getTimestamp() > $last)) {
                $rates[] = $rate->rate;
                $rates = array_slice($rates, -$periods);
                $last = $rate->fundingTime->getTimestamp();
            }

            if (count($rates) < $periods) {
                return null;
            }

            $sum = Decimal::zero();

            foreach ($rates as $value) {
                $sum = $sum->plus($value);
            }

            return $sum->dividedBy(Decimal::of($periods), 18);
        };
    }

    /** @return Closure(Frame): ?array{long: Decimal, short: Decimal} the stops a long and a short would trail to */
    private function chandelier(int $period, Decimal $k): Closure
    {
        $highs = new Highest($period);
        $lows = new Lowest($period);
        $atr = new Atr($period);

        return function (Frame $f) use ($highs, $lows, $atr, $k): ?array {
            $high = $highs->push($f->candle->high->value);
            $low = $lows->push($f->candle->low->value);
            $range = $atr->push($f->candle);

            return $high === null || $low === null || $range === null ? null : ['long' => $high->minus($k->times($range)), 'short' => $low->plus($k->times($range))];
        };
    }

    /** @return Closure(Frame): ?array{long: Decimal, short: Decimal} */
    private function atrTrail(int $period, Decimal $k): Closure
    {
        $atr = new Atr($period);

        return function (Frame $f) use ($atr, $k): ?array {
            $range = $atr->push($f->candle);
            $close = $f->candle->close->value;

            return $range === null ? null : ['long' => $close->minus($k->times($range)), 'short' => $close->plus($k->times($range))];
        };
    }

    /** History needed by $node on $timeframe, in seconds. */
    private function seconds(Node $node, Timeframe $timeframe): int
    {
        $bar = $timeframe->seconds();

        return match (true) {
            $node instanceof Identifier => in_array($node->name, ['open', 'high', 'low', 'close', 'volume', 'hl2', 'hlc3', 'ohlc4'], true) ? $bar : 0,
            $node instanceof Unary => $this->seconds($node->operand, $timeframe),
            $node instanceof Binary => max($this->seconds($node->left, $timeframe), $this->seconds($node->right, $timeframe))
                + (str_starts_with($node->operator, 'crosses') ? $bar : 0),
            $node instanceof Lookback => $this->seconds($node->series, $timeframe) + $node->bars * $bar,
            $node instanceof OnTimeframe => $this->seconds($node->expression, $node->timeframe),
            $node instanceof Call => $this->callSeconds($node, $timeframe),
            default => 0,
        };
    }

    /** @return Closure(Frame): ?Decimal one level of the volume profile of the last $period candles (D-72) */
    private function volumeProfile(string $level, int $period): Closure
    {
        $profile = new VolumeProfile($period);

        return function (Frame $f) use ($profile, $level): ?Decimal {
            $levels = $profile->push($f->candle);

            return $levels[$level] ?? null;
        };
    }

    private function callSeconds(Call $node, Timeframe $timeframe): int
    {
        $arguments = $this->types->arguments($node);
        $n = fn (string $name): int => isset($arguments[$name]) ? (int) (string) $this->number($arguments[$name]) : 0;
        $root = 1;

        while (($root + 1) * ($root + 1) <= $n('n')) {
            $root++;
        }

        $own = match ($node->name) {
            'ema', 'sma', 'wma', 'vwma', 'stdev', 'highest', 'lowest', 'bb', 'donchian', 'keltner', 'atr', 'natr', 'supertrend', 'chandelier', 'atr_trail', 'vp_poc', 'vp_vah', 'vp_val' => $n('n'),
            'obv' => 1,
            'hma' => $n('n') + $root - 1,
            'rsi', 'roc', 'change', 'realized_vol', 'plus_di', 'minus_di' => $n('n') + 1,
            'adx' => 2 * $n('n'),
            'macd' => $n('slow') + $n('signal') - 1,
            'stoch' => $n('k') + $n('d') - 1,
            'vwap' => 1,
            default => 0,
        };
        $source = 0;

        foreach (['src', 'series'] as $name) {
            if (isset($arguments[$name])) {
                $source = max($source, $this->seconds($arguments[$name], $timeframe) - $timeframe->seconds());
            }
        }

        return $own * $timeframe->seconds() + max(0, $source);
    }

    private function number(Node $node): Decimal
    {
        return match (true) {
            $node instanceof NumberLiteral => $node->value,
            $node instanceof PercentLiteral => $node->fraction,
            $node instanceof Parameter => $this->value($node),
            $node instanceof Unary && $node->operator === 'neg' => $this->number($node->operand)->negate(),
            default => throw new LogicException('Not a constant number.'),
        };
    }

    private function value(Parameter $node): Decimal
    {
        return $this->values[$node->name] ?? throw new LogicException("No value for \${$node->name}.");
    }

    private function anchor(Node $node): Timeframe
    {
        return $node instanceof DurationLiteral
            ? Timeframe::from("{$node->amount}{$node->unit}")
            : throw new LogicException('Not a timeframe.');
    }

    /** @param Decimal|array<string, ?Decimal>|null $result */
    private static function pick(Decimal|array|null $result, ?string $field): ?Decimal
    {
        return is_array($result) ? ($field === null ? null : $result[$field] ?? null) : $result;
    }

    private static function tradeR(Frame $f): ?Decimal
    {
        $position = $f->position;
        $stop = $f->trade?->initialStop;

        if ($position === null || $stop === null) {
            return null;
        }

        $risk = $position->entryPrice->minus($stop)->abs();
        $move = $f->candle->close->value->minus($position->entryPrice);

        return $risk->isZero() ? null : ($position->side === Side::Buy ? $move : $move->negate())->dividedBy($risk, 18);
    }
}
