<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

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
use App\Strategy\Dsl\Functions\FunctionSpec;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Dsl\Functions\ParamKind;
use App\Strategy\Dsl\Functions\ParamSpec;
use App\Strategy\Dsl\Types\Kind;
use App\Strategy\Dsl\Types\Type;
use App\Strategy\Dsl\Types\Unit;
use App\Support\Number\Decimal;
use App\Support\Timeframe;
use LogicException;
use SplObjectStorage;

/**
 * Gives every expression its type (docs/strategy/configurator.md, section 2) and refuses what has no meaning: a
 * price compared with a percent, a function used where it does not belong, a missing argument. It also resolves
 * every call's arguments, defaults included, for the compiler.
 */
final class TypeChecker
{
    private const array COMPARISONS = ['>', '>=', '<', '<=', '==', '!='];

    /** @var SplObjectStorage<Call, array<string, Node>> the arguments of every call, by parameter name */
    private SplObjectStorage $calls;

    public function __construct()
    {
        $this->calls = new SplObjectStorage;
    }

    /** @return array<string, Node> the arguments of a checked call by parameter name, defaults included */
    public function arguments(Call $call): array
    {
        if (! $this->calls->offsetExists($call)) {
            throw new LogicException("The call to {$call->name} was not checked.");
        }

        return $this->calls[$call];
    }

    public function check(Node $node, Scope $scope): Type
    {
        return match (true) {
            $node instanceof NumberLiteral => Type::of(Kind::Number),
            $node instanceof PercentLiteral => Type::of(Kind::Percent),
            $node instanceof DurationLiteral => Type::of(Kind::Duration),
            $node instanceof RMultipleLiteral => Type::of(Kind::RMultiple),
            $node instanceof BoolLiteral => Type::of(Kind::Condition),
            $node instanceof Parameter => $scope->parameters[$node->name]
                ?? throw new DslError($node->at, "\${$node->name} is not declared under params."),
            $node instanceof Identifier => $this->identifier($node, $scope),
            $node instanceof Call => $this->call($node, $scope),
            $node instanceof Unary => $this->unary($node, $scope),
            $node instanceof Binary => $this->binary($node, $scope),
            $node instanceof Lookback => $this->lookback($node, $scope),
            $node instanceof OnTimeframe => $this->onTimeframe($node, $scope),
            default => throw new LogicException('Unknown node '.$node::class.'.'),
        };
    }

    /** Checks $node and requires $kind; $what names the place for the message ("filters", "stop_loss"). */
    public function expect(Node $node, Scope $scope, string $what, Kind ...$kinds): Type
    {
        $type = $this->check($node, $scope);

        if (! in_array($type->kind, $kinds, true)) {
            $wanted = implode(' or ', array_map(fn (Kind $kind): string => Type::of($kind)->describe(), $kinds));

            throw new DslError($node->position(), "{$what} needs {$wanted}, but ".DslWriter::write($node)." is {$type->describe()}.");
        }

        return $type;
    }

    private function identifier(Identifier $node, Scope $scope): Type
    {
        if ($node->name === 'none') {
            return Type::of(Kind::None);
        }

        $spec = $this->spec($node->name, $node, $scope);

        if ($spec->isCall) {
            throw new DslError($node->at, "{$node->name} is a function: write {$node->name}(".($spec->params === [] ? '' : '...').').');
        }

        return $spec->result ?? throw new LogicException("{$node->name} has no type.");
    }

    private function call(Call $node, Scope $scope): Type
    {
        $spec = $this->spec($node->name, $node, $scope);

        if (! $spec->isCall) {
            throw new DslError($node->at, "{$node->name} takes no arguments: write {$node->name}.");
        }

        $arguments = $this->bind($node, $spec);
        $this->calls[$node] = $arguments;
        $source = null;

        foreach ($spec->params as $param) {
            $type = $this->argument($arguments[$param->name], $param, $spec, $scope);
            $source = $param->name === 'src' ? $type : $source;
        }

        if ($spec->fields !== []) {
            if ($node->field === null || ! isset($spec->fields[$node->field])) {
                $fields = implode(', ', array_map(fn (string $field): string => ".{$field}", array_keys($spec->fields)));

                throw new DslError($node->at, "{$node->name}(...) has several values: pick one with {$fields}.");
            }

            $type = $spec->fields[$node->field];

            return $spec->followsSource && $source !== null && $type->unit === Unit::Price ? Type::series($source->unit) : $type;
        }

        if ($node->field !== null) {
            throw new DslError($node->at, "{$node->name}(...) has a single value; remove .{$node->field}.");
        }

        if ($spec->followsSource) {
            return Type::series($source !== null ? $source->unit : Unit::Plain);
        }

        return $spec->result ?? throw new LogicException("{$node->name} has no type.");
    }

    /**
     * Matches the call's arguments to the function's parameters: positional first, then named; defaults fill the
     * rest.
     *
     * @return array<string, Node>
     */
    private function bind(Call $node, FunctionSpec $spec): array
    {
        $bound = [];
        $named = false;

        foreach ($node->arguments as $index => $argument) {
            if ($argument->name === null) {
                if ($named) {
                    throw new DslError($argument->at, "in {$node->name}(...), positional arguments come before named ones.");
                }

                $param = $spec->params[$index] ?? throw new DslError($argument->at, "{$node->name} takes ".self::count(count($spec->params), 'argument').'.');
            } else {
                $named = true;
                $param = $spec->param($argument->name)
                    ?? throw new DslError($argument->at, "{$node->name} has no argument {$argument->name}. Its arguments: ".implode(', ', array_map(fn (ParamSpec $p): string => $p->name, $spec->params)).'.');
            }

            if (isset($bound[$param->name])) {
                throw new DslError($argument->at, "{$param->name} of {$node->name} is given twice.");
            }

            $bound[$param->name] = $argument->value;
        }

        foreach ($spec->params as $param) {
            if (! isset($bound[$param->name])) {
                if ($param->default === null) {
                    throw new DslError($node->at, "{$node->name} needs {$param->name}, {$param->kind->value}.");
                }

                $bound[$param->name] = Parser::parse($param->default, $node->at->line, $node->at->column);
            }
        }

        return $bound;
    }

    private function argument(Node $value, ParamSpec $param, FunctionSpec $spec, Scope $scope): Type
    {
        $wrong = fn (): DslError => new DslError($value->position(), "{$param->name} of {$spec->name} must be {$param->kind->value}, not ".DslWriter::write($value).'.');

        switch ($param->kind) {
            case ParamKind::Count:
                $type = $this->check($value, $scope);
                $whole = $value instanceof NumberLiteral && self::isWhole($value->value)
                    || $value instanceof Parameter && ($scope->wholeParameters[$value->name] ?? false);

                if (! $whole) {
                    throw new DslError($value->position(), "{$param->name} of {$spec->name} must be a whole number of candles from 1, such as {$spec->name}(20).");
                }

                return $type;
            case ParamKind::Anchor:
                if (! $value instanceof DurationLiteral || Timeframe::tryFrom("{$value->amount}{$value->unit}") === null) {
                    throw $wrong();
                }

                return Type::of(Kind::Duration);
            default:
                $type = $this->check($value, $scope);
                $accepted = match ($param->kind) {
                    ParamKind::Number => $type->kind === Kind::Number,
                    ParamKind::Series => $type->kind === Kind::Series,
                    ParamKind::Duration => $type->kind === Kind::Duration,
                    ParamKind::Percent => $type->kind === Kind::Percent,
                };

                return $accepted ? $type : throw $wrong();
        }
    }

    private function unary(Unary $node, Scope $scope): Type
    {
        if ($node->operator === 'not') {
            return $this->expect($node->operand, $scope, 'not', Kind::Condition);
        }

        $type = $this->check($node->operand, $scope);

        if (! $type->isNumeric()) {
            throw new DslError($node->at, '- applies to numbers, not to '.$type->describe().'.');
        }

        return $type;
    }

    private function binary(Binary $node, Scope $scope): Type
    {
        if ($node->operator === 'and' || $node->operator === 'or') {
            $this->expect($node->left, $scope, $node->operator, Kind::Condition);
            $this->expect($node->right, $scope, $node->operator, Kind::Condition);

            return Type::of(Kind::Condition);
        }

        $left = $this->check($node->left, $scope);
        $right = $this->check($node->right, $scope);

        foreach ([[$node->left, $left], [$node->right, $right]] as [$operand, $type]) {
            if (! $type->isNumeric()) {
                throw new DslError($operand->position(), "{$node->operator} needs numbers, but ".DslWriter::write($operand)." is {$type->describe()}.");
            }
        }

        if (in_array($node->operator, self::COMPARISONS, true) || str_starts_with($node->operator, 'crosses')) {
            $this->comparable($node, $left, $right);

            return Type::of(Kind::Condition);
        }

        return $this->arithmetic($node, $left, $right);
    }

    private function comparable(Binary $node, Type $left, Type $right): void
    {
        $percent = $left->kind === Kind::Percent ? [$right, $node->left] : ($right->kind === Kind::Percent ? [$left, $node->right] : null);

        if ($percent !== null && $percent[0]->kind === Kind::Series && $percent[0]->unit !== Unit::Fraction) {
            $series = $percent[0];
            $source = DslWriter::write($node);
            $hint = '';

            if ($right->kind === Kind::Percent && $node->right instanceof PercentLiteral) {
                $factor = Decimal::of(1)->plus($node->right->fraction);
                $base = DslWriter::write($node->left);
                $hint = " Did you mean {$base} {$node->operator} {$base}[1] * {$factor} ?";
            }

            throw new DslError($node->at, "cannot compare {$series->describe()} with a percent ({$source}).{$hint}");
        }

        $a = $left->kind === Kind::Percent ? Unit::Fraction : $left->unit;
        $b = $right->kind === Kind::Percent ? Unit::Fraction : $right->unit;

        if ($a !== Unit::Any && $b !== Unit::Any && $a !== $b) {
            throw new DslError($node->at, "cannot compare {$left->describe()} with {$right->describe()} (".DslWriter::write($node).').');
        }
    }

    private function arithmetic(Binary $node, Type $left, Type $right): Type
    {
        $a = $left->kind === Kind::Percent ? Unit::Any : $left->unit;
        $b = $right->kind === Kind::Percent ? Unit::Any : $right->unit;
        $additive = $node->operator === '+' || $node->operator === '-';

        if ($additive && $a !== Unit::Any && $b !== Unit::Any && $a !== $b) {
            throw new DslError($node->at, 'cannot '.($node->operator === '+' ? 'add' : 'subtract')." {$left->describe()} and {$right->describe()} (".DslWriter::write($node).').');
        }

        $unit = match (true) {
            $a === Unit::Any => $b,
            $b === Unit::Any => $a,
            $additive => $a,
            default => Unit::Plain,
        };
        $series = $left->kind === Kind::Series || $right->kind === Kind::Series;

        return $series ? Type::series($unit) : Type::of(Kind::Number);
    }

    private function lookback(Lookback $node, Scope $scope): Type
    {
        $type = $this->check($node->series, $scope);

        if ($type->kind !== Kind::Series && $type->kind !== Kind::Condition) {
            throw new DslError($node->at, '[ ] reads a series back in time; '.DslWriter::write($node->series)." is {$type->describe()}.");
        }

        return $type;
    }

    private function onTimeframe(OnTimeframe $node, Scope $scope): Type
    {
        if ($node->timeframe->seconds() < $scope->timeframe->seconds()) {
            throw new DslError($node->at, "on {$node->timeframe->value} reads a shorter timeframe than the strategy's {$scope->timeframe->value}; use {$scope->timeframe->value} or longer.");
        }

        $type = $this->check($node->expression, $scope);

        if ($type->kind !== Kind::Series && $type->kind !== Kind::Condition) {
            throw new DslError($node->at, 'on applies to series and conditions; '.DslWriter::write($node->expression)." is {$type->describe()}.");
        }

        return $type;
    }

    private function spec(string $name, Node $node, Scope $scope): FunctionSpec
    {
        $spec = Library::get($name);

        if ($spec === null) {
            $closest = null;
            $distance = PHP_INT_MAX;

            foreach (array_keys(Library::all()) as $known) {
                $d = levenshtein($name, $known);
                [$closest, $distance] = $d < $distance ? [$known, $d] : [$closest, $distance];
            }

            throw new DslError($node->position(), "{$name} is not a function of the DSL.".($distance <= 2 ? " Did you mean {$closest}?" : ''));
        }

        if ($spec->refused !== null) {
            throw new DslError($node->position(), $spec->refused);
        }

        if (! $scope->allows($spec->group)) {
            throw new DslError($node->position(), match ($spec->group) {
                Group::Exit => "{$name} is an exit level: use it in stop_loss or trailing.",
                default => "{$name} is an order type: use it in entries.<side>.order.",
            });
        }

        if ($spec->group === Group::Perpetual && ! $scope->perpetual) {
            throw new DslError($node->position(), "{$name} exists only for perpetuals; this strategy trades spot.");
        }

        return $spec;
    }

    private static function isWhole(Decimal $value): bool
    {
        return $value->isPositive() && $value->equals($value->truncatedTo(0));
    }

    private static function count(int $count, string $noun): string
    {
        return $count === 1 ? "1 {$noun}" : "{$count} {$noun}s";
    }
}
