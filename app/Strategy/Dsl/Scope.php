<?php

declare(strict_types=1);

namespace App\Strategy\Dsl;

use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Types\Type;
use App\Support\Timeframe;

/**
 * Where an expression is checked: the strategy's timeframe and market, its declared parameters and which function
 * groups this part of the document allows. Warnings collect as the checker goes.
 */
final class Scope
{
    /** @var list<string> */
    public array $warnings = [];

    /**
     * @param  array<string, Type>  $parameters  declared parameters and their types
     * @param  array<string, bool>  $wholeParameters  the parameters whose values are all whole numbers from 1
     * @param  list<Group>  $groups  groups allowed here besides the general ones
     * @param  bool  $universe  whether the strategy trades a universe, whose members it may rank (D-92)
     */
    public function __construct(
        public readonly Timeframe $timeframe,
        public readonly bool $perpetual,
        public readonly array $parameters = [],
        public readonly array $wholeParameters = [],
        public readonly array $groups = [],
        public readonly bool $universe = false,
    ) {}

    /** The same scope, allowing other special groups (exit levels, order types). */
    public function allowing(Group ...$groups): self
    {
        $scope = new self($this->timeframe, $this->perpetual, $this->parameters, $this->wholeParameters, array_values($groups), $this->universe);
        $scope->warnings = &$this->warnings;

        return $scope;
    }

    public function allows(Group $group): bool
    {
        return ! in_array($group, [Group::Exit, Group::Order], true) || in_array($group, $this->groups, true);
    }

    public function warn(string $warning): void
    {
        if (! in_array($warning, $this->warnings, true)) {
            $this->warnings[] = $warning;
        }
    }
}
