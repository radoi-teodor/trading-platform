<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Market\Data\MarketType;
use App\Strategy\Baselines\FundingCarry;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\EntryOrder;
use App\Strategy\Data\Intent;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\StrategyLimits;
use App\Strategy\Data\Subscription;
use App\Strategy\Dsl\Ast\Argument;
use App\Strategy\Dsl\Ast\Binary;
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
use App\Strategy\Dsl\Document\EntrySpec;
use App\Strategy\Dsl\Document\ParameterSpec;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Scope;
use App\Strategy\Dsl\TypeChecker;
use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Dsl\Types\Type;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use InvalidArgumentException;
use LogicException;

/**
 * A strategy built from a checked document (docs/strategy/configurator.md, section 3). On every closed candle of
 * its timeframe it evaluates every expression, then:
 * - flat: enters long or short when the filters and that side's condition hold, with the stop and the target fixed
 *   from the close (D-53);
 * - in a position: leaves on the time stop or the exit condition, and trails the stop when it tightens.
 * The stop and the target themselves work on the venue (D-10). One instance runs one backtest or one account.
 */
final class CompiledStrategy implements LimitedStrategy
{
    private const array SECONDS = ['m' => 60, 'h' => 3_600, 'd' => 86_400, 'w' => 604_800];

    /** @var array<string, Program> by symbol */
    private array $programs = [];

    /** @var array<string, TradeMemory> the entry each instrument last asked for */
    private array $trades = [];

    /** @var list<Timeframe> the timeframes read with `on`, longest first */
    private array $timeframes;

    private int $warmup;

    private Scope $scope;

    /** @param array<string, Decimal> $values */
    private function __construct(private readonly StrategySpec $spec, private readonly array $values, private readonly int $version)
    {
        $this->scope = new Scope(
            $spec->timeframe,
            $spec->market === MarketType::Perp,
            array_map(fn (ParameterSpec $p): Type => Type::of($p->kind), $spec->params),
            array_map(fn (ParameterSpec $p): bool => $p->whole, $spec->params),
        );
        $probe = $this->program();
        $timeframes = array_map(fn (string $value): Timeframe => Timeframe::from($value), $probe->timeframes());
        usort($timeframes, fn (Timeframe $a, Timeframe $b): int => $b->seconds() <=> $a->seconds());
        $this->timeframes = $timeframes;
        $this->warmup = $this->computeWarmup();
    }

    /**
     * Builds the strategy of a document with its parameters' defaults, overridden by $overrides (by name, as text:
     * 20 or 1.5%).
     *
     * @param  array<string, string>  $overrides
     */
    public static function fromSpec(StrategySpec $spec, array $overrides = [], int $version = 1): Strategy
    {
        if ($spec->template !== null) {
            $carry = FundingCarry::build($spec->template->parameters(), [$spec->template->perpetual]);

            return new RenamedStrategy($carry, new StrategyIdentity($spec->id, $version, $spec->template->parameters()));
        }

        $values = [];

        foreach ($spec->params as $name => $param) {
            $values[$name] = $param->default;
        }

        foreach ($overrides as $name => $text) {
            $param = $spec->params[$name] ?? throw new InvalidArgumentException("{$spec->id} has no parameter {$name}. Parameters: ".implode(', ', array_keys($spec->params)).'.');
            $percent = str_ends_with($text, '%');

            if (preg_match('/^-?\d+(\.\d+)?%?$/', $text) !== 1 || $percent !== ($param->kind === Kind::Percent)) {
                throw new InvalidArgumentException("{$name} takes ".($param->kind === Kind::Percent ? 'a percent, such as 1.5%' : 'a number').", got '{$text}'.");
            }

            $value = Decimal::of($percent ? substr($text, 0, -1) : $text);
            $value = $percent ? $value->dividedBy(Decimal::of(100), 18) : $value;

            if ($param->whole && ! $value->isPositive() || $param->whole && ! $value->equals($value->truncatedTo(0))) {
                throw new InvalidArgumentException("{$name} is a period: a whole number from 1, got '{$text}'.");
            }

            $values[$name] = $value;
        }

        return new self($spec, $values, $version);
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity($this->spec->id, $this->version, array_map(fn (Decimal $value): string => (string) $value, $this->values));
    }

    public function subscriptions(): array
    {
        $subscriptions = [];

        foreach ($this->spec->instruments as $symbol) {
            foreach ([...$this->timeframes, $this->spec->timeframe] as $timeframe) {
                $subscriptions[] = new Subscription($symbol, $timeframe);
            }
        }

        return $subscriptions;
    }

    public function warmupBars(): int
    {
        return $this->warmup;
    }

    public function limits(): StrategyLimits
    {
        return new StrategyLimits(
            $this->spec->riskPerTrade,
            $this->spec->maxPosition,
            $this->spec->maxOpenPositions,
            $this->spec->maxTradesPerDay,
            $this->spec->cooldownSeconds,
        );
    }

    public function onCandle(StrategyContext $context): array
    {
        $key = (string) $context->symbol;
        $program = $this->programs[$key] ??= $this->program();
        $frame = new Frame($context->candle, $context->time, $context->position, $context->funding, $this->trades[$key] ?? null, $context->perpetual);

        if ($context->candle->timeframe !== $this->spec->timeframe) {
            $program->update($context->candle->timeframe->value, $frame);

            return [];
        }

        $values = $program->evaluate($frame);

        if ($context->position === null) {
            return $this->enter($context->symbol, $context->candle->close->value, $values);
        }

        return $this->manage($context, $values);
    }

    /**
     * @param  array<string, mixed>  $values
     * @return list<Intent>
     */
    private function enter(Symbol $symbol, Decimal $close, array $values): array
    {
        foreach ($values as $name => $value) {
            if (str_starts_with($name, 'filter.') && $value !== true) {
                return [];
            }
        }

        foreach ($this->spec->entries as $name => $entry) {
            if ($values["entry.{$name}"] !== true) {
                continue;
            }

            $intent = $this->entry($symbol, $entry, $close, $values['stop'] ?? null);

            if ($intent !== null) {
                return [$intent];
            }
        }

        return [];
    }

    private function entry(Symbol $symbol, EntrySpec $entry, Decimal $close, mixed $stopValue): ?Intent
    {
        $long = $entry->side === Side::Buy;
        $reason = 'entries.'.($long ? 'long' : 'short').'.when';
        $stopLoss = $this->spec->exits->stopLoss;

        if ($stopLoss === null) {
            $this->trades[(string) $symbol] = new TradeMemory($entry->side, null);

            return $long ? new OpenLong($symbol, null, null, $reason, $this->order($entry)) : new OpenShort($symbol, null, null, $reason, $this->order($entry));
        }

        if (! $stopValue instanceof Decimal) {
            return null;
        }

        $one = Decimal::of(1);
        $stop = match ($stopLoss->type->kind) {
            Kind::Percent => $close->times($long ? $one->minus($stopValue) : $one->plus($stopValue))->truncatedTo(18),
            Kind::Stop => $stopValue,
            default => $long ? $close->minus($stopValue) : $close->plus($stopValue),
        };

        if (! $stop->isPositive()) {
            return null;
        }

        $target = null;

        if ($this->spec->exits->takeProfit !== null) {
            $risk = $long ? $close->minus($stop) : $stop->minus($close);
            $target = $long ? $close->plus($this->spec->exits->takeProfit->times($risk)) : $close->minus($this->spec->exits->takeProfit->times($risk));
            $target = $target->isPositive() ? $target : null;
        }

        $this->trades[(string) $symbol] = new TradeMemory($entry->side, $stop);
        $stopPrice = Price::of($stop);
        $targetPrice = $target === null ? null : Price::of($target);
        $order = $this->order($entry);

        return $long ? new OpenLong($symbol, $stopPrice, $targetPrice, $reason, $order) : new OpenShort($symbol, $stopPrice, $targetPrice, $reason, $order);
    }

    /**
     * @param  array<string, mixed>  $values
     * @return list<Intent>
     */
    private function manage(StrategyContext $context, array $values): array
    {
        $position = $context->position;

        if ($position === null) {
            return [];
        }

        $timeStop = $this->spec->exits->timeStop?->node;

        if ($timeStop instanceof DurationLiteral) {
            $held = $context->time->getTimestamp() - $position->openedAt->getTimestamp();
            $limit = $timeStop->unit === 'bars' ? $timeStop->amount * $this->spec->timeframe->seconds() : $timeStop->amount * self::SECONDS[$timeStop->unit];

            if ($held >= $limit) {
                return [new Close($context->symbol, 'exits.time_stop')];
            }
        }

        if (($values['exit'] ?? null) === true) {
            return [new Close($context->symbol, 'exits.when')];
        }

        $trail = $values['trailing'] ?? null;
        $long = $position->side === Side::Buy;
        $candidate = is_array($trail) ? ($trail[$long ? 'long' : 'short'] ?? null) : null;
        $close = $context->candle->close->value;

        if ($candidate instanceof Decimal && $candidate->isPositive() && ($long ? $candidate->isLessThan($close) : $candidate->isGreaterThan($close))) {
            $current = $position->stop?->value;
            $tighter = $current === null || ($long ? $candidate->isGreaterThan($current) : $candidate->isLessThan($current));

            if ($tighter) {
                return [new MoveStop($context->symbol, Price::of($candidate), 'exits.trailing')];
            }
        }

        return [];
    }

    /** The limit order of an entry (D-55): its offset from the decision price and how long it works. */
    private function order(EntrySpec $entry): ?EntryOrder
    {
        $call = $entry->order?->node;

        if (! $call instanceof Call) {
            return null;
        }

        $checker = new TypeChecker;
        $checker->check($call, $this->scope->allowing(Group::Order));
        $arguments = $checker->arguments($call);
        $expiry = $arguments['expiry'] ?? null;
        $seconds = $expiry instanceof DurationLiteral
            ? $expiry->amount * ($expiry->unit === 'bars' ? $this->spec->timeframe->seconds() : self::SECONDS[$expiry->unit])
            : throw new LogicException('A limit order without an expiry.');

        return new EntryOrder($this->constant($arguments['offset'] ?? throw new LogicException('A limit order without an offset.')), $seconds);
    }

    private function constant(Node $node): Decimal
    {
        return match (true) {
            $node instanceof PercentLiteral => $node->fraction,
            $node instanceof NumberLiteral => $node->value,
            $node instanceof Parameter => $this->values[$node->name] ?? throw new LogicException("No value for \${$node->name}."),
            $node instanceof Unary && $node->operator === 'neg' => $this->constant($node->operand)->negate(),
            default => throw new LogicException('Not a constant.'),
        };
    }

    /** The compiled expressions of the document, fresh, for one instrument. */
    /**
     * The stores of data for scalping its expressions read (D-79), so a backtest can refuse a range they do not cover.
     *
     * @return list<string> table names: trade_flow, open_interest, mark_price_minutes, premium_index_minutes
     */
    public function scalpingData(): array
    {
        $tables = [
            'open_interest' => 'open_interest', 'oi_change' => 'open_interest', 'mark_price' => 'mark_price_minutes', 'basis' => 'premium_index_minutes',
            'large_buy_volume' => 'trade_flow', 'large_sell_volume' => 'trade_flow', 'large_delta' => 'trade_flow',
        ];
        $exits = $this->spec->exits;
        $expressions = [...$this->spec->filters, ...array_map(fn (EntrySpec $entry) => $entry->when, array_values($this->spec->entries)), $exits->stopLoss, $exits->trailing, $exits->when];
        $used = [];
        $walk = function (Node $node) use (&$walk, &$used, $tables): void {
            match (true) {
                $node instanceof Identifier, $node instanceof Call => isset($tables[$node->name]) ? $used[$tables[$node->name]] = true : null,
                default => null,
            };
            $children = match (true) {
                $node instanceof Unary => [$node->operand],
                $node instanceof Binary => [$node->left, $node->right],
                $node instanceof Lookback => [$node->series],
                $node instanceof OnTimeframe => [$node->expression],
                $node instanceof Call => array_map(fn (Argument $a): Node => $a->value, $node->arguments),
                default => [],
            };

            foreach ($children as $child) {
                $walk($child);
            }
        };

        foreach ($expressions as $expression) {
            if ($expression !== null) {
                $walk($expression->node);
            }
        }

        return array_keys($used);
    }

    private function program(): Program
    {
        $program = new Program;
        $compiler = new Compiler($this->scope, $this->values);

        foreach ($this->spec->filters as $index => $filter) {
            $compiler->add($program, "filter.{$index}", $filter->node);
        }

        foreach ($this->spec->entries as $name => $entry) {
            $compiler->add($program, "entry.{$name}", $entry->when->node);
        }

        $exits = $this->spec->exits;

        foreach (['stop' => $exits->stopLoss, 'trailing' => $exits->trailing, 'exit' => $exits->when] as $name => $expression) {
            if ($expression !== null) {
                $compiler->add($program, $name, $expression->node);
            }
        }

        return $program;
    }

    /** The history every expression needs, in candles of the longest timeframe the strategy follows (D-53). */
    private function computeWarmup(): int
    {
        $compiler = new Compiler($this->scope, $this->values);
        $exits = $this->spec->exits;
        $seconds = 0;
        $expressions = [...$this->spec->filters, ...array_map(fn (EntrySpec $entry) => $entry->when, array_values($this->spec->entries)), $exits->stopLoss, $exits->trailing, $exits->when];

        foreach ($expressions as $expression) {
            if ($expression !== null) {
                $seconds = max($seconds, $compiler->history($expression->node));
            }
        }

        $longest = ($this->timeframes[0] ?? $this->spec->timeframe)->seconds();

        return intdiv($seconds + $longest - 1, $longest);
    }
}
