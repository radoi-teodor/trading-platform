<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

use App\Market\Data\MarketType;
use App\Strategy\Dsl\Ast\Argument;
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
use App\Strategy\Dsl\Ast\RMultipleLiteral;
use App\Strategy\Dsl\Ast\Unary;
use App\Strategy\Dsl\Document\CarryTemplate;
use App\Strategy\Dsl\Document\EntrySpec;
use App\Strategy\Dsl\Document\Expression;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Dsl\Types\Kind;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use LogicException;

/**
 * Renders a compiled strategy as plain sentences, from its syntax tree and not from any model's paraphrase
 * (docs/strategy/configurator.md, section 4; D-14). The operator approves this text. Parameters read as the values
 * of the version being read.
 */
final class ReadBack
{
    private const array COMPARISONS = [
        '>' => 'is above', '<' => 'is below', '>=' => 'is at or above', '<=' => 'is at or below',
        '==' => 'equals', '!=' => 'differs from', 'crosses_above' => 'crosses above', 'crosses_below' => 'crosses below',
    ];

    private const array ARITHMETIC = ['+' => 'plus', '-' => 'minus', '*' => 'times', '/' => 'divided by'];

    private const array NAMES = [
        'close' => 'the close', 'open' => 'the open', 'high' => 'the high', 'low' => 'the low', 'volume' => 'the volume',
        'hl2' => 'the median price', 'hlc3' => 'the typical price', 'ohlc4' => 'the average price',
        'funding_rate' => 'the funding rate', 'in_position' => 'a position is open', 'bars_in_trade' => 'the candles in the trade',
        'trade_r' => 'the open result in R', 'hour_utc' => 'the UTC hour', 'weekday' => 'the weekday',
        'buy_volume' => 'the volume bought by takers', 'sell_volume' => 'the volume sold by takers', 'delta' => 'the volume delta',
        'open_interest' => 'the open interest', 'mark_price' => 'the mark price', 'basis' => 'the basis',
        'long_short_ratio' => 'the ratio of accounts long to short', 'top_trader_ratio' => 'the ratio of the top traders positions long to short',
        'taker_ratio' => 'the ratio of taker buy to sell volume',
        'large_buy_volume' => 'the volume of large buys', 'large_sell_volume' => 'the volume of large sells', 'large_delta' => 'the large-trade delta',
    ];

    private const array SHORT_NAMES = [
        'rsi' => 'RSI', 'roc' => 'the rate of change', 'atr' => 'ATR', 'natr' => 'NATR', 'stdev' => 'the standard deviation',
        'realized_vol' => 'the realized volatility', 'adx' => 'ADX', 'plus_di' => '+DI', 'minus_di' => '-DI',
    ];

    private const array AVERAGES = ['sma' => 'SMA', 'ema' => 'EMA', 'wma' => 'WMA', 'hma' => 'HMA'];

    /** @var array<string, string> */
    private array $values;

    /** @param array<string, string> $overrides parameter values, the defaults otherwise */
    private function __construct(private readonly StrategySpec $spec, array $overrides)
    {
        $this->values = [];

        foreach ($spec->params as $name => $param) {
            $this->values[$name] = $overrides[$name] ?? (string) $param->default;
        }
    }

    /** @param array<string, string> $overrides */
    public static function render(StrategySpec $spec, array $overrides = []): string
    {
        return (new self($spec, $overrides))->paragraph();
    }

    private function paragraph(): string
    {
        $spec = $this->spec;

        if ($spec->template !== null) {
            return $this->carry($spec->template);
        }

        $where = $this->instruments($spec->instruments, $spec->market);
        $opening = "On {$where}, every time ".self::article(self::candle($spec->timeframe)).' candle closes: ';
        $filters = array_map(fn (Expression $e): string => $this->condition($e->node), $spec->filters);
        $entries = array_map(fn (EntrySpec $entry): string => $this->entry($entry), array_values($spec->entries));
        $sentences = [$opening.($filters === [] ? '' : 'if '.self::join($filters).', ').implode(', and ', $entries).'.'];
        $sentences[] = $this->sizing();
        $sentences[] = $this->stop();

        if ($spec->exits->takeProfit !== null) {
            $sentences[] = 'Take profit at '.self::number($spec->exits->takeProfit).' times the initial risk.';
        }

        if ($spec->exits->trailing !== null) {
            $sentences[] = 'Trail with '.$this->series($spec->exits->trailing->node).'.';
        }

        $sentences[] = $this->leaving();
        $sentences[] = $this->limits();

        if ($this->values !== []) {
            $sentences[] = 'Parameters: '.implode(', ', array_map(fn (string $n, string $v): string => "{$n} = {$v}", array_keys($this->values), $this->values)).'.';
        }

        return implode(' ', array_filter($sentences, fn (?string $s): bool => $s !== null && $s !== ''));
    }

    private function carry(CarryTemplate $carry): string
    {
        return "On {$carry->spot} spot and {$carry->perpetual} perpetual, every time ".self::article(self::candle($carry->timeframe)).' candle closes: '
            ."when the average funding rate of the last {$carry->entryPeriods} periods is above ".self::percent($carry->entry)
            .', buy spot and sell the perpetual short in equal size; close both when the average of the last '
            ."{$carry->exitPeriods} periods is below ".self::percent($carry->exit).'. '
            ."Perpetual leverage at most {$carry->maxLeverage}, with ".self::percent($carry->marginBuffer).' extra margin; '
            .'at most '.self::percent($carry->maxPosition).' of equity in the position.';
    }

    /** @param list<Symbol> $symbols */
    private function instruments(array $symbols, MarketType $market): string
    {
        return self::join(array_map('strval', $symbols)).($market === MarketType::Perp ? ' perpetual' : ' spot');
    }

    private function entry(EntrySpec $entry): string
    {
        $action = $entry->side === Side::Buy ? 'buy' : 'sell short';
        $order = $entry->order === null ? 'at market' : $this->order($entry->order->node, $entry->side);
        $when = $entry->when->node instanceof BoolLiteral && $entry->when->node->value ? '' : ' when '.$this->condition($entry->when->node);

        return $entry->order === null || str_starts_with($order, 'at market') ? "{$action} {$order}{$when}" : "{$action}{$when}, {$order}";
    }

    private function order(Node $node, Side $side): string
    {
        if ($node instanceof Identifier && $node->name === 'market') {
            return 'at market';
        }

        if (! $node instanceof Call || $node->name !== 'limit') {
            throw new LogicException('An order is market or limit(...).');
        }

        $arguments = $this->arguments($node);
        $offset = $arguments['offset'] ?? null;
        $expiry = $arguments['expiry'] ?? null;
        $fraction = $offset instanceof PercentLiteral ? $offset->fraction : ($offset instanceof Unary && $offset->operand instanceof PercentLiteral ? $offset->operand->fraction->negate() : Decimal::zero());
        $place = $fraction->isZero() ? 'at the close' : self::percent($fraction->abs()).($fraction->isNegative() ? ' below' : ' above').' the close';

        return "with a limit order {$place}".($expiry === null ? '' : ', cancelled after '.$this->duration($expiry));
    }

    private function sizing(): string
    {
        if ($this->spec->benchmark) {
            return 'Sized as a benchmark, for backtests only.';
        }

        $risk = $this->spec->riskPerTrade;
        $position = $this->spec->maxPosition;

        return match (true) {
            $risk !== null && $position !== null => 'Risk '.self::percent($risk).' of equity per trade, at most '.self::percent($position).' of equity in the position.',
            $risk !== null => 'Risk '.self::percent($risk).' of equity per trade; the position size follows the account policy.',
            $position !== null => 'Risk per trade follows the account policy, at most '.self::percent($position).' of equity in the position.',
            default => 'Risk per trade and position size follow the account policy.',
        };
    }

    private function stop(): string
    {
        $stop = $this->spec->exits->stopLoss;

        if ($stop === null) {
            return 'No stop: a benchmark holds through every move.';
        }

        $sides = array_map(fn (EntrySpec $e): Side => $e->side, array_values($this->spec->entries));
        $where = match (true) {
            count($sides) > 1 => 'below the entry for a long and above it for a short',
            ($sides[0] ?? Side::Buy) === Side::Sell => 'above the entry',
            default => 'below the entry',
        };

        if ($stop->type->kind === Kind::Stop) {
            return 'Stop at '.$this->series($stop->node).', on the exchange.';
        }

        return 'Stop '.$this->series($stop->node)." {$where}, on the exchange.";
    }

    private function leaving(): ?string
    {
        $exits = $this->spec->exits;
        $time = $exits->timeStop === null ? null : 'after '.$this->duration($exits->timeStop->node);
        $when = $exits->when === null ? null : 'when '.$this->condition($exits->when->node);

        return match (true) {
            $time !== null && $when !== null => "Leave {$time}, or {$when}.",
            $time !== null => "Leave {$time}.",
            $when !== null => "Leave {$when}.",
            default => null,
        };
    }

    private function limits(): ?string
    {
        $spec = $this->spec;
        $counts = array_values(array_filter([
            $spec->maxOpenPositions === null ? null : self::plural($spec->maxOpenPositions, 'open position'),
            $spec->maxTradesPerDay === null ? null : self::plural($spec->maxTradesPerDay, 'trade').' a day',
        ]));
        $cooldown = $spec->cooldownSeconds === null ? null : 'wait '.self::seconds($spec->cooldownSeconds).' after a loss';

        return match (true) {
            $counts !== [] && $cooldown !== null => 'At most '.implode(' and ', $counts)."; {$cooldown}.",
            $counts !== [] => 'At most '.implode(' and ', $counts).'.',
            $cooldown !== null => ucfirst($cooldown).'.',
            default => null,
        };
    }

    private function condition(Node $node): string
    {
        return match (true) {
            $node instanceof BoolLiteral => $node->value ? 'always' : 'never',
            $node instanceof Identifier && $node->name === 'in_position' => 'a position is open',
            $node instanceof Unary && $node->operator === 'not' => $node->operand instanceof Identifier && $node->operand->name === 'in_position'
                ? 'no position is open'
                : 'it is not true that '.$this->condition($node->operand),
            $node instanceof Binary && ($node->operator === 'and' || $node->operator === 'or') => $this->logic($node),
            $node instanceof Binary && isset(self::COMPARISONS[$node->operator]) => $this->series($node->left).' '.self::COMPARISONS[$node->operator].' '.$this->series($node->right),
            $node instanceof Lookback => $this->condition($node->series).($node->bars === 1 ? ' on the previous candle' : " {$node->bars} candles ago"),
            $node instanceof OnTimeframe => $this->condition($node->expression).' on the '.self::candle($node->timeframe).' candles',
            default => $this->series($node),
        };
    }

    private function logic(Binary $node): string
    {
        $part = fn (Node $child): string => $child instanceof Binary && ($child->operator === 'and' || $child->operator === 'or') && $child->operator !== $node->operator
            ? '('.$this->condition($child).')'
            : $this->condition($child);

        return $part($node->left)." {$node->operator} ".$part($node->right);
    }

    private function series(Node $node): string
    {
        return match (true) {
            $node instanceof NumberLiteral => self::number($node->value),
            $node instanceof PercentLiteral => self::percent($node->fraction),
            $node instanceof RMultipleLiteral => self::number($node->value).'R',
            $node instanceof DurationLiteral => $this->duration($node),
            $node instanceof Parameter => $this->values[$node->name] ?? throw new LogicException("No value for \${$node->name}."),
            $node instanceof Identifier => self::NAMES[$node->name] ?? $node->name,
            $node instanceof Unary => $node->operator === 'not' ? $this->condition($node) : 'minus '.$this->series($node->operand),
            $node instanceof Binary && isset(self::ARITHMETIC[$node->operator]) => $this->series($node->left).' '.self::ARITHMETIC[$node->operator].' '.$this->series($node->right),
            $node instanceof Binary => $this->condition($node),
            $node instanceof Lookback => $this->lookback($node),
            $node instanceof OnTimeframe => self::onTimeframe($this->series($node->expression), $node->timeframe),
            $node instanceof Call => $this->call($node),
            default => throw new LogicException('Cannot read back '.$node::class.'.'),
        };
    }

    private function lookback(Lookback $node): string
    {
        if ($node->series instanceof Identifier && isset(self::NAMES[$node->series->name])) {
            $name = substr(self::NAMES[$node->series->name], 4);

            return $node->bars === 1 ? "the previous {$name}" : "the {$name} {$node->bars} candles ago";
        }

        return $this->series($node->series).($node->bars === 1 ? ' as of the previous candle' : " as of {$node->bars} candles ago");
    }

    private function call(Call $node): string
    {
        $a = $this->arguments($node);
        $v = fn (string $name): string => isset($a[$name]) ? $this->series($a[$name]) : '';
        $of = fn (): string => isset($a['src']) && ! ($a['src'] instanceof Identifier && $a['src']->name === 'close') ? ' of '.self::bare($v('src')) : '';
        $field = $node->field;

        return match ($node->name) {
            'sma', 'ema', 'wma', 'hma' => "the {$v('n')}-period ".self::AVERAGES[$node->name].$of(),
            'vwma' => "the {$v('n')}-period volume-weighted average",
            'vwap' => isset($a['anchor']) && $a['anchor'] instanceof DurationLiteral && "{$a['anchor']->amount}{$a['anchor']->unit}" !== '1d'
                ? 'the VWAP anchored to '.self::candle(Timeframe::from("{$a['anchor']->amount}{$a['anchor']->unit}")).' candles'
                : 'the daily VWAP',
            'highest', 'lowest' => "the {$node->name} ".self::bare($v('src'))." of the last {$v('n')} candles",
            'change' => 'the change of '.self::bare($v('src'))." over {$v('n')} candles",
            'bb' => $field === 'width' ? "the Bollinger band width ({$v('n')}, {$v('k')})".$of() : "the {$field} Bollinger band ({$v('n')}, {$v('k')})".$of(),
            'donchian' => "the {$field} Donchian channel ({$v('n')})",
            'keltner' => "the {$field} Keltner channel ({$v('n')}, {$v('k')})",
            'macd' => match ($field) {
                'signal' => 'the MACD signal line', 'hist' => 'the MACD histogram', default => 'the MACD line',
            }." ({$v('fast')}, {$v('slow')}, {$v('signal')})",
            'stoch' => 'Stochastic %'.strtoupper((string) $field)." ({$v('k')}, {$v('d')})",
            'supertrend' => "the Supertrend {$field} ({$v('n')}, {$v('k')})",
            'funding_rate_avg' => "the average funding rate of the last {$v('n')} periods",
            'obv' => 'the on-balance volume',
            'cvd' => "the volume delta of the last {$v('n')} candles",
            'oi_change' => "the change of open interest over {$v('n')} candles",
            'bid_depth' => "the bids resting within {$v('band')} below the price",
            'ask_depth' => "the asks resting within {$v('band')} above the price",
            'depth_imbalance' => "the order book imbalance within {$v('band')}",
            'vp_poc' => "the point of control of the last {$v('n')} candles' volume profile",
            'vp_vah' => "the top of the value area of the last {$v('n')} candles",
            'vp_val' => "the bottom of the value area of the last {$v('n')} candles",
            'level' => $v('series'),
            'chandelier' => "a chandelier stop ({$v('n')}, {$v('k')})",
            'atr_trail' => "an ATR trailing stop ({$v('n')}, {$v('k')})",
            default => (self::SHORT_NAMES[$node->name] ?? strtoupper($node->name))."({$v('n')})".$of(),
        };
    }

    /** @return array<string, Node> the call's arguments by parameter name, defaults filled in */
    private function arguments(Call $node): array
    {
        $spec = Library::get($node->name) ?? throw new LogicException("{$node->name} is not a function.");
        $resolved = [];
        $position = 0;

        foreach ($node->arguments as $argument) {
            /** @var Argument $argument */
            $name = $argument->name ?? ($spec->params[$position++]->name ?? throw new LogicException("Too many arguments to {$node->name}."));
            $resolved[$name] = $argument->value;
        }

        foreach ($spec->params as $param) {
            if (! isset($resolved[$param->name]) && $param->default !== null) {
                $resolved[$param->name] = Parser::parse($param->default);
            }
        }

        return $resolved;
    }

    private function duration(Node $node): string
    {
        if ($node instanceof Parameter) {
            return $this->series($node).' candles';
        }

        if (! $node instanceof DurationLiteral) {
            throw new LogicException('A duration is written 60 bars or 8h.');
        }

        $unit = ['bars' => 'candle', 'm' => 'minute', 'h' => 'hour', 'd' => 'day', 'w' => 'week'][$node->unit] ?? $node->unit;

        return self::plural($node->amount, $unit);
    }

    private static function seconds(int $seconds): string
    {
        return match (true) {
            $seconds % 604_800 === 0 => self::plural(intdiv($seconds, 604_800), 'week'),
            $seconds % 86_400 === 0 => self::plural(intdiv($seconds, 86_400), 'day'),
            $seconds % 3_600 === 0 => self::plural(intdiv($seconds, 3_600), 'hour'),
            default => self::plural(intdiv($seconds, 60), 'minute'),
        };
    }

    private static function onTimeframe(string $phrase, Timeframe $timeframe): string
    {
        return str_starts_with($phrase, 'the ') ? 'the '.self::candle($timeframe).' '.substr($phrase, 4) : "{$phrase} on ".self::candle($timeframe).' candles';
    }

    /** "1-minute", "4-hour", "daily". */
    private static function candle(Timeframe $timeframe): string
    {
        return match ($timeframe) {
            Timeframe::OneDay => 'daily',
            Timeframe::OneWeek => 'weekly',
            default => preg_replace_callback('/^(\d+)([mh])$/', fn (array $m): string => $m[1].'-'.($m[2] === 'm' ? 'minute' : 'hour'), $timeframe->value) ?? $timeframe->value,
        };
    }

    private static function article(string $word): string
    {
        return (in_array($word[0], ['a', 'e', 'i', 'o', 'u'], true) ? 'an' : 'a')." {$word}";
    }

    private static function bare(string $phrase): string
    {
        return str_starts_with($phrase, 'the ') ? substr($phrase, 4) : $phrase;
    }

    /** @param list<string> $items */
    private static function join(array $items): string
    {
        return count($items) <= 1 ? implode('', $items) : implode(', ', array_slice($items, 0, -1)).' and '.end($items);
    }

    private static function plural(int $count, string $noun): string
    {
        return "{$count} {$noun}".($count === 1 ? '' : 's');
    }

    private static function number(Decimal $value): string
    {
        return (string) $value;
    }

    private static function percent(Decimal $fraction): string
    {
        return $fraction->times(Decimal::of(100)).' %';
    }
}
