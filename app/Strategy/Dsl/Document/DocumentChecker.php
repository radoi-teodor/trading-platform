<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Market\Contracts\InstrumentDirectory;
use App\Market\Data\MarketType;
use App\Strategy\Dsl\Ast\Binary;
use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\Ast\DurationLiteral;
use App\Strategy\Dsl\Ast\Identifier;
use App\Strategy\Dsl\Ast\NumberLiteral;
use App\Strategy\Dsl\Ast\PercentLiteral;
use App\Strategy\Dsl\Ast\RMultipleLiteral;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\DslWriter;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Parser;
use App\Strategy\Dsl\Position;
use App\Strategy\Dsl\Scope;
use App\Strategy\Dsl\TypeChecker;
use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Dsl\Types\Type;
use App\Strategy\Dsl\Types\Unit;
use App\Strategy\Dsl\Yaml\YamlList;
use App\Strategy\Dsl\Yaml\YamlMap;
use App\Strategy\Dsl\Yaml\YamlReader;
use App\Strategy\Dsl\Yaml\YamlScalar;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Closure;
use InvalidArgumentException;

/**
 * Checks a strategy document against docs/strategy/configurator.md: its keys, the type of every expression and the
 * compiler's rules (a stop on every strategy, shorts only on perpetuals, limits only tighter than the policy,
 * instruments that trade). It collects every error it can find, in document order, instead of stopping at the first.
 */
final class DocumentChecker
{
    private const array KEYS = [
        'strategy', 'title', 'family', 'hypothesis', 'fails_when', 'profile', 'template', 'params', 'market', 'timeframe',
        'filters', 'entries', 'exits', 'sizing', 'limits', 'agents',
    ];

    private const array SECTIONS = [
        'market' => ['exchange', 'type', 'instruments'],
        'entry' => ['when', 'order'],
        'exits' => ['stop_loss', 'take_profit', 'trailing', 'time_stop', 'when'],
        'sizing' => ['risk_per_trade', 'max_position'],
        'limits' => ['max_open_positions', 'max_trades_per_day', 'cooldown_after_loss'],
        'agents' => ['max_brief_age'],
        'funding_carry' => ['spot', 'perp', 'enter_when', 'exit_when', 'max_leverage_perp', 'margin_buffer'],
        'parameter' => ['default', 'range', 'step'],
    ];

    private const array FAMILIES = ['trend', 'mean_reversion', 'carry', 'volatility', 'event'];

    private const array SECONDS = ['m' => 60, 'h' => 3_600, 'd' => 86_400, 'w' => 604_800];

    /** @var list<DslError> */
    private array $errors = [];

    private TypeChecker $types;

    private function __construct(private readonly InstrumentDirectory $instruments, private readonly PolicyLimits $policy)
    {
        $this->types = new TypeChecker;
    }

    public static function check(string $yaml, InstrumentDirectory $instruments, PolicyLimits $policy): CheckResult
    {
        try {
            $document = YamlReader::read($yaml);
        } catch (DslError $error) {
            return new CheckResult(null, [$error], []);
        }

        $checker = new self($instruments, $policy);
        [$spec, $warnings] = $checker->document($document);
        $errors = $checker->errors;
        usort($errors, fn (DslError $a, DslError $b): int => [$a->position->line, $a->position->column] <=> [$b->position->line, $b->position->column]);

        return new CheckResult($errors === [] ? $spec : null, $errors, $warnings);
    }

    /** @return array{?StrategySpec, list<string>} */
    private function document(YamlMap $document): array
    {
        $this->knownKeys($document, self::KEYS, 'a strategy document');
        $id = $this->text($document, 'strategy');

        if ($id !== null && preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $id->text) !== 1) {
            $this->fail($id->at, 'strategy must be lowercase letters, digits and hyphens, such as btc-trend-4h.');
        }

        $title = $this->text($document, 'title');
        $family = $this->text($document, 'family');

        if ($family !== null && ! in_array($family->text, self::FAMILIES, true)) {
            $this->fail($family->at, 'family must be one of '.implode(', ', self::FAMILIES).'.');
        }

        $hypothesis = $this->text($document, 'hypothesis');
        $failsWhen = $this->text($document, 'fails_when');
        $profile = $this->text($document, 'profile', required: false);

        if ($profile !== null && ! in_array($profile->text, ['account', 'benchmark'], true)) {
            $this->fail($profile->at, 'profile must be account or benchmark.');
        }

        $benchmark = $profile !== null && $profile->text === 'benchmark';
        $template = $this->text($document, 'template', required: false);
        $timeframeText = $this->text($document, 'timeframe', required: $template === null);
        $timeframe = $timeframeText === null ? Timeframe::OneHour : Timeframe::tryFrom($timeframeText->text);

        if ($timeframe === null && $timeframeText !== null) {
            $this->fail($timeframeText->at, 'timeframe must be one of '.implode(', ', array_map(fn (Timeframe $t): string => $t->value, Timeframe::cases())).'.');
        }

        $sizing = $this->section($document, 'sizing', 'sizing');

        if ($template !== null) {
            $carry = $this->carry($document, $template, $sizing, $timeframe ?? Timeframe::OneHour);

            if ($id === null || $title === null || $family === null || $hypothesis === null || $failsWhen === null || $carry === null) {
                return [null, []];
            }

            return [new StrategySpec(
                $id->text, $title->text, $family->text, trim($hypothesis->text), trim($failsWhen->text), false, $carry, [],
                MarketType::Perp, [$carry->spot, $carry->perpetual], $carry->timeframe, [], [],
                new ExitSpec(null, null, null, null, null), null, $carry->maxPosition, null, null, null, null,
            ), []];
        }

        [$market, $instruments] = $this->market($document);
        $params = $this->params($document);
        $scope = new Scope(
            $timeframe ?? Timeframe::OneHour,
            $market === MarketType::Perp,
            array_map(fn (ParameterSpec $p): Type => Type::of($p->kind), $params),
            array_map(fn (ParameterSpec $p): bool => $p->whole, $params),
        );
        $filters = $this->filters($document, $scope);
        $entries = $this->entries($document, $scope, $market, $instruments);
        $exits = $this->exits($document, $scope, $benchmark);
        $riskPerTrade = $this->fraction($sizing, 'risk_per_trade', $this->policy->riskPerTrade);
        $maxPosition = $this->fraction($sizing, 'max_position', $this->policy->maxPosition);
        $limits = $this->section($document, 'limits', 'limits');
        $maxOpenPositions = $this->count($limits, 'max_open_positions', $this->policy->maxOpenPositions);
        $maxTradesPerDay = $this->count($limits, 'max_trades_per_day', null);
        $cooldown = $this->seconds($limits, 'cooldown_after_loss', $scope);
        $agents = $this->section($document, 'agents', 'agents');
        $briefAge = $this->seconds($agents, 'max_brief_age', $scope);

        if ($this->errors !== [] || $id === null || $title === null || $family === null || $hypothesis === null || $failsWhen === null
            || $market === null || $timeframe === null || $exits === null) {
            return [null, $scope->warnings];
        }

        return [new StrategySpec(
            $id->text, $title->text, $family->text, trim($hypothesis->text), trim($failsWhen->text), $benchmark, null, $params,
            $market, $instruments, $timeframe, $filters, $entries, $exits, $riskPerTrade, $maxPosition,
            $maxOpenPositions, $maxTradesPerDay, $cooldown, $briefAge,
        ), $scope->warnings];
    }

    /** @return array{?MarketType, list<Symbol>} */
    private function market(YamlMap $document): array
    {
        $market = $this->section($document, 'market', 'market', required: true);

        if ($market === null) {
            return [null, []];
        }

        $exchange = $this->text($market, 'exchange');

        if ($exchange !== null && $exchange->text !== 'binance') {
            $this->fail($exchange->at, 'exchange must be binance.');
        }

        $typeText = $this->text($market, 'type');
        $type = $typeText === null ? null : MarketType::tryFrom($typeText->text);

        if ($typeText !== null && $type === null) {
            $this->fail($typeText->at, 'market.type must be spot or perp.');
        }

        $list = $market->entries['instruments'] ?? null;

        if (! $list instanceof YamlList || $list->items === []) {
            $this->fail($market->keyPosition('instruments') ?? $market->at, 'market.instruments must list at least one symbol, such as [BTC/USDT].');

            return [$type, []];
        }

        $symbols = [];

        foreach ($list->items as $item) {
            $symbol = $item instanceof YamlScalar ? $this->symbol($item) : null;

            if ($symbol === null || $type === null) {
                continue;
            }

            if ($symbol->isPerpetual() !== ($type === MarketType::Perp)) {
                $this->fail($item->position(), $symbol->isPerpetual() ? "{$symbol} is a perpetual; market.type is spot." : "{$symbol} is spot; market.type is perp.");
            } elseif (! $this->instruments->isTradable($symbol)) {
                $this->fail($item->position(), "{$symbol} is not a tradable {$type->value} instrument on binance; run market:instruments if it is new.");
            } else {
                $symbols[] = $symbol;
            }
        }

        return [$type, $symbols];
    }

    /** @return array<string, ParameterSpec> */
    private function params(YamlMap $document): array
    {
        $params = $this->section($document, 'params', 'params');
        $specs = [];

        foreach ($params === null ? [] : $params->entries as $name => $value) {
            $at = $params?->keyPosition($name) ?? $value->position();

            if (! $value instanceof YamlMap) {
                $this->fail($at, "{$name} needs { default: ..., range: [..., ...], step: ... }.");

                continue;
            }

            $this->knownKeys($value, self::SECTIONS['parameter'], "the parameter {$name}");
            $default = $value->entries['default'] ?? null;

            if (! $default instanceof YamlScalar) {
                $this->fail($at, "{$name} needs a default.");

                continue;
            }

            [$kind, $number] = $this->literal($default);

            if ($kind === null || $number === null) {
                continue;
            }

            $range = $value->entries['range'] ?? null;
            [$min, $max] = [null, null];

            if ($range !== null) {
                $bounds = $range instanceof YamlList && count($range->items) === 2 ? array_map(fn ($item): ?array => $item instanceof YamlScalar ? $this->literal($item) : null, $range->items) : [];

                if (count($bounds) !== 2 || $bounds[0] === null || $bounds[1] === null || $bounds[0][0] !== $kind || $bounds[1][0] !== $kind) {
                    $this->fail($range->position(), "the range of {$name} needs two values like its default, such as [10, 30].");

                    continue;
                }

                [$min, $max] = [$bounds[0][1], $bounds[1][1]];

                if ($min === null || $max === null || $number->isLessThan($min) || $number->isGreaterThan($max)) {
                    $texts = array_map(fn ($item): string => $item instanceof YamlScalar ? $item->text : '', $range->items);
                    $this->fail($default->at, "the default {$default->text} of {$name} is outside its range [".implode(', ', $texts).'].');
                }
            }

            $stepValue = $value->entries['step'] ?? null;
            $step = $stepValue instanceof YamlScalar ? $this->literal($stepValue)[1] : null;

            if ($stepValue !== null && ($step === null || ! $step->isPositive())) {
                $this->fail($stepValue->position(), "the step of {$name} must be positive.");
            }

            $whole = $kind === Kind::Number && self::isWhole($number) && ($min === null || self::isWhole($min)) && ($step === null || $step->equals($step->truncatedTo(0)));
            $specs[$name] = new ParameterSpec($name, $kind, $number, $min, $max, $step, $whole);
        }

        return $specs;
    }

    /** @return list<Expression> */
    private function filters(YamlMap $document, Scope $scope): array
    {
        $filters = $document->entries['filters'] ?? null;

        if ($filters === null) {
            return [];
        }

        if (! $filters instanceof YamlList) {
            $this->fail($filters->position(), 'filters must be a list, one condition per line.');

            return [];
        }

        $checked = [];

        foreach ($filters->items as $item) {
            $expression = $this->expression($item, $scope, 'filters', Kind::Condition);

            if ($expression !== null) {
                $checked[] = $expression;
            }
        }

        return $checked;
    }

    /**
     * @param  list<Symbol>  $instruments
     * @return array<string, EntrySpec>
     */
    private function entries(YamlMap $document, Scope $scope, ?MarketType $market, array $instruments): array
    {
        $entries = $this->section($document, 'entries', 'entries', required: true);

        if ($entries === null) {
            return [];
        }

        $this->knownKeys($entries, ['long', 'short'], 'entries');
        $specs = [];

        foreach (['long' => Side::Buy, 'short' => Side::Sell] as $name => $side) {
            $entry = $entries->entries[$name] ?? null;

            if ($entry === null) {
                continue;
            }

            $at = $entries->keyPosition($name) ?? $entry->position();

            if ($side === Side::Sell && $market === MarketType::Spot) {
                $symbols = implode(', ', array_map('strval', $instruments));
                $this->fail($at, "entries.short needs market.type perp; {$symbols} on spot cannot be shorted.");

                continue;
            }

            if (! $entry instanceof YamlMap) {
                $this->fail($at, "entries.{$name} needs when: and, optionally, order:.");

                continue;
            }

            $this->knownKeys($entry, self::SECTIONS['entry'], "entries.{$name}");
            $when = $this->text($entry, 'when', at: $at);
            $condition = $when === null ? null : $this->expression($when, $scope, "entries.{$name}.when", Kind::Condition);
            $orderText = $this->text($entry, 'order', required: false);
            $order = $orderText === null ? null : $this->expression($orderText, $scope->allowing(Group::Order), "entries.{$name}.order", Kind::Order);

            if ($condition !== null) {
                $specs[$name] = new EntrySpec($side, $condition, $order !== null && $order->node instanceof Identifier ? null : $order);
            }
        }

        if ($specs === [] && $this->errors === []) {
            $this->fail($entries->at, 'entries needs long or short.');
        }

        return $specs;
    }

    private function exits(YamlMap $document, Scope $scope, bool $benchmark): ?ExitSpec
    {
        $exits = $this->section($document, 'exits', 'exits');
        $at = $document->keyPosition('exits') ?? new Position(1, 1);
        $stopText = $exits === null ? null : $this->text($exits, 'stop_loss', required: false);

        if ($stopText === null) {
            $this->fail($at, 'stop_loss is missing. Every strategy needs a stop. Example: stop_loss: 2 * atr(14)');

            return null;
        }

        $exitScope = $scope->allowing(Group::Exit);
        $stop = $this->attempt(function () use ($stopText, $exitScope, $benchmark): ?Expression {
            $node = Parser::parse($stopText->text, $stopText->at->line, $stopText->at->column);
            $type = $this->types->check($node, $exitScope);
            $text = DslWriter::write($node);

            if ($type->kind === Kind::None) {
                return $benchmark ? null : throw new DslError($stopText->at, 'stop_loss: none is only for profile: benchmark (D-56).');
            }

            $price = $type->kind === Kind::Series && $type->unit === Unit::Price;

            if (! $price && $type->kind !== Kind::Percent && $type->kind !== Kind::Stop) {
                throw new DslError($stopText->at, "stop_loss needs a price series or a percent or a stop level, but {$text} is {$type->describe()}.");
            }

            return new Expression($node, $type, $text);
        });

        if ($exits === null) {
            return null;
        }

        $this->knownKeys($exits, self::SECTIONS['exits'], 'exits');
        $target = $this->text($exits, 'take_profit', required: false);
        $takeProfit = $target === null ? null : $this->expression($target, $scope, 'take_profit', Kind::RMultiple);
        $trailing = $this->text($exits, 'trailing', required: false);
        $timeStop = $this->text($exits, 'time_stop', required: false);
        $when = $this->text($exits, 'when', required: false);

        return new ExitSpec(
            $stop,
            $takeProfit !== null && $takeProfit->node instanceof RMultipleLiteral ? $takeProfit->node->value : null,
            $trailing === null ? null : $this->expression($trailing, $exitScope, 'trailing', Kind::Trail),
            $timeStop === null ? null : $this->expression($timeStop, $scope, 'time_stop', Kind::Duration),
            $when === null ? null : $this->expression($when, $scope, 'exits.when', Kind::Condition),
        );
    }

    private function carry(YamlMap $document, YamlScalar $template, ?YamlMap $sizing, Timeframe $timeframe): ?CarryTemplate
    {
        if ($template->text !== 'funding_carry') {
            $this->fail($template->at, 'template must be one of funding_carry.');

            return null;
        }

        $params = $this->section($document, 'params', 'params', required: true);

        if ($params === null) {
            return null;
        }

        $this->knownKeys($params, self::SECTIONS['funding_carry'], 'the funding_carry parameters');

        foreach (self::SECTIONS['funding_carry'] as $key) {
            if (! isset($params->entries[$key])) {
                $this->fail($document->keyPosition('params') ?? $params->at, "funding_carry needs {$key}.");
            }
        }

        $spotText = $this->text($params, 'spot', required: false);
        $perpText = $this->text($params, 'perp', required: false);
        $spot = $spotText === null ? null : $this->symbol($spotText);
        $perpetual = $perpText === null ? null : $this->symbol($perpText);

        if ($spot !== null && $perpetual !== null && $perpText !== null
            && (string) $perpetual !== "{$spot->base}/{$spot->quote}:{$spot->quote}") {
            $this->fail($perpText->at, "perp must be the perpetual of {$spot}, {$spot->base}/{$spot->quote}:{$spot->quote}.");
        }

        foreach ([$spot, $perpetual] as $index => $symbol) {
            $text = $index === 0 ? $spotText : $perpText;

            if ($symbol !== null && $text !== null && ! $this->instruments->isTradable($symbol)) {
                $this->fail($text->at, "{$symbol} is not a tradable instrument on binance; run market:instruments if it is new.");
            }
        }

        $enter = $this->fundingRule($params, 'enter_when', '>');
        $exit = $this->fundingRule($params, 'exit_when', '<');
        $leverage = $this->count($params, 'max_leverage_perp', null);
        $buffer = $this->fraction($params, 'margin_buffer', null);
        $maxPosition = $this->fraction($sizing, 'max_position', null);

        if ($spot === null || $perpetual === null || $enter === null || $exit === null || $leverage === null || $buffer === null || $maxPosition === null) {
            return null;
        }

        return new CarryTemplate($spot, $perpetual, $enter[0], $enter[1], $exit[0], $exit[1], $leverage, $buffer, $maxPosition, $timeframe);
    }

    /** @return array{int, Decimal}|null funding_rate_avg(n) compared with x%: n and x as a fraction */
    private function fundingRule(YamlMap $params, string $key, string $operator): ?array
    {
        $text = $this->text($params, $key, required: false);

        if ($text === null) {
            return null;
        }

        return $this->attempt(function () use ($text, $key, $operator): array {
            $node = Parser::parse($text->text, $text->at->line, $text->at->column);
            $call = $node instanceof Binary && $node->operator === $operator ? $node->left : null;
            $periods = $call instanceof Call && $call->name === 'funding_rate_avg' && count($call->arguments) === 1 ? $call->arguments[0]->value : null;
            $rate = $node instanceof Binary ? $node->right : null;

            if (! $periods instanceof NumberLiteral || ! self::isWhole($periods->value) || ! $rate instanceof PercentLiteral) {
                throw new DslError($text->at, "funding_carry reads {$key} as funding_rate_avg(n) {$operator} x%.");
            }

            return [(int) (string) $periods->value, $rate->fraction];
        });
    }

    private function expression(YamlScalar|YamlList|YamlMap $value, Scope $scope, string $what, Kind ...$kinds): ?Expression
    {
        if (! $value instanceof YamlScalar) {
            $this->fail($value->position(), "{$what} must be one expression on one line.");

            return null;
        }

        return $this->attempt(function () use ($value, $scope, $what, $kinds): Expression {
            $node = Parser::parse($value->text, $value->at->line, $value->at->column);

            return new Expression($node, $this->types->expect($node, $scope, $what, ...$kinds), DslWriter::write($node));
        });
    }

    /** A percent of equity that may not exceed $limit (D-54), as a fraction. */
    private function fraction(?YamlMap $section, string $key, ?Decimal $limit): ?Decimal
    {
        $text = $section === null ? null : $this->text($section, $key, required: false);

        if ($text === null) {
            return null;
        }

        [$kind, $value] = $this->literal($text);

        if ($kind !== Kind::Percent || $value === null || ! $value->isPositive()) {
            $this->fail($text->at, "{$key} must be a positive percent, such as 0.5%.");

            return null;
        }

        if ($limit !== null && $value->isGreaterThan($limit)) {
            $this->fail($text->at, "{$key} {$text->text} is above the policy limit of ".$limit->times(Decimal::of(100))->roundedTo(4).'%.');
        }

        return $value;
    }

    private function count(?YamlMap $section, string $key, ?int $limit): ?int
    {
        $text = $section === null ? null : $this->text($section, $key, required: false);

        if ($text === null) {
            return null;
        }

        if (preg_match('/^[1-9]\d{0,5}$/', $text->text) !== 1) {
            $this->fail($text->at, "{$key} must be a whole number from 1.");

            return null;
        }

        if ($limit !== null && (int) $text->text > $limit) {
            $this->fail($text->at, "{$key} {$text->text} is above the policy limit of {$limit}.");
        }

        return (int) $text->text;
    }

    private function seconds(?YamlMap $section, string $key, Scope $scope): ?int
    {
        $text = $section === null ? null : $this->text($section, $key, required: false);
        $expression = $text === null ? null : $this->expression($text, $scope, $key, Kind::Duration);
        $node = $expression?->node;

        if (! $node instanceof DurationLiteral || $text === null) {
            return null;
        }

        if ($node->unit === 'bars') {
            $this->fail($text->at, "{$key} must be a time, such as 8h, not candles.");

            return null;
        }

        return $node->amount * self::SECONDS[$node->unit];
    }

    private function symbol(YamlScalar $text): ?Symbol
    {
        try {
            return Symbol::parse($text->text);
        } catch (InvalidArgumentException) {
            $this->fail($text->at, "{$text->text} is not a symbol such as BTC/USDT or BTC/USDT:USDT.");

            return null;
        }
    }

    /** @return array{?Kind, ?Decimal} a number or a percent written as a scalar */
    private function literal(YamlScalar $text): array
    {
        if (preg_match('/^-?\d+(\.\d+)?%$/', $text->text) === 1) {
            return [Kind::Percent, Decimal::of(substr($text->text, 0, -1))->dividedBy(Decimal::of(100), 18)];
        }

        if (preg_match('/^-?\d+(\.\d+)?$/', $text->text) === 1) {
            return [Kind::Number, Decimal::of($text->text)];
        }

        $this->fail($text->at, "{$text->text} must be a number or a percent.");

        return [null, null];
    }

    private function section(YamlMap $document, string $key, string $name, bool $required = false): ?YamlMap
    {
        $value = $document->entries[$key] ?? null;

        if ($value === null) {
            if ($required) {
                $this->fail(new Position(1, 1), "{$name} is missing.");
            }

            return null;
        }

        if (! $value instanceof YamlMap) {
            $this->fail($value->position(), "{$name} must be a set of keys.");

            return null;
        }

        return $value;
    }

    private function text(YamlMap $map, string $key, bool $required = true, ?Position $at = null): ?YamlScalar
    {
        $value = $map->entries[$key] ?? null;

        if ($value === null) {
            if ($required) {
                $this->fail($at ?? ($map->at->line === 1 && $map->at->column === 1 ? new Position(1, 1) : $map->at), "{$key} is missing.");
            }

            return null;
        }

        if (! $value instanceof YamlScalar || $value->text === '') {
            $this->fail($value->position(), "{$key} must be a single value.");

            return null;
        }

        return $value;
    }

    /** @param list<string> $keys */
    private function knownKeys(YamlMap $map, array $keys, string $where): void
    {
        foreach (array_keys($map->entries) as $key) {
            if (! in_array($key, $keys, true)) {
                $this->fail($map->keyPosition($key) ?? $map->at, "{$key} is not a key of {$where}. Keys: ".implode(', ', $keys).'.');
            }
        }
    }

    /**
     * Runs a check, recording its error instead of stopping.
     *
     * @template T
     *
     * @param  Closure(): T  $check
     * @return T|null
     */
    private function attempt(Closure $check): mixed
    {
        try {
            return $check();
        } catch (DslError $error) {
            $this->errors[] = $error;

            return null;
        }
    }

    private function fail(Position $at, string $problem): void
    {
        $this->errors[] = new DslError($at, $problem);
    }

    private static function isWhole(Decimal $value): bool
    {
        return $value->isPositive() && $value->equals($value->truncatedTo(0));
    }
}
