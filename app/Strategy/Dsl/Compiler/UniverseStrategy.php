<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\StrategyLimits;
use App\Strategy\Data\Subscription;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Ast\Call;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use LogicException;

/**
 * A strategy over a universe (D-92, docs/universe.md): every time a candle of its timeframe closes it decides for
 * all the members at once, in two phases. First every instrument's ranked values are worked out and the members
 * that have their history are entered in the cross-section; then every instrument's conditions are evaluated,
 * reading its place among them. One instrument decides as in a strategy that names its instruments
 * (CompiledStrategy); what is new is who may enter and in which order.
 *
 * - An instrument is ranked while it is a member in the month of the close and once it has the strategy's warm-up
 *   in candles without a gap; after a gap its history starts again.
 * - A position in an instrument that is no longer a member is closed (universe.left).
 * - Intents come out as closes and stop moves in symbol order, then entries from the best ranked: by the first
 *   ranking of that side's condition, else of the filters (bottom counts from the lowest), a long before a short
 *   of the same place, then by symbol. An entry nothing ranks comes last.
 */
final class UniverseStrategy implements CrossSectionalStrategy, LimitedStrategy
{
    private CrossSection $section;

    /** @var array<string, Program> by symbol */
    private array $programs = [];

    /** @var array<string, array{bars: int, longest: int, last: ?int}> candles seen without a gap, by symbol */
    private array $seen = [];

    /** @var array<string, ?Call> the ranking that orders each side's entries */
    private array $rankings = [];

    public function __construct(private readonly CompiledStrategy $member, private readonly StrategySpec $spec, private readonly UniverseSchedule $universe)
    {
        $this->section = new CrossSection;

        foreach (array_keys($spec->entries) as $side) {
            $this->rankings[$side] = $member->ranking($side);
        }
    }

    public function identity(): StrategyIdentity
    {
        return $this->member->identity();
    }

    public function subscriptions(): array
    {
        $subscriptions = [];

        foreach ($this->universe->symbols() as $symbol) {
            foreach ([...$this->member->longerTimeframes(), $this->spec->timeframe] as $timeframe) {
                $subscriptions[] = new Subscription(Symbol::parse($symbol), $timeframe);
            }
        }

        return $subscriptions;
    }

    public function warmupBars(): int
    {
        return $this->member->warmupBars();
    }

    public function limits(): StrategyLimits
    {
        return $this->member->limits();
    }

    /** @return list<string> the stores of data for scalping its expressions read (D-79) */
    public function scalpingData(): array
    {
        return $this->member->scalpingData();
    }

    public function onCandle(StrategyContext $context): array
    {
        throw new LogicException("{$this->spec->id} trades a universe: it decides for all its members at once, through onCloses.");
    }

    public function onCloses(array $contexts): array
    {
        if ($contexts === []) {
            return [];
        }

        $time = $contexts[0]->time;
        $groups = [];

        foreach ($contexts as $context) {
            if ($context->time->getTimestamp() !== $time->getTimestamp()) {
                $moments = [self::moment($time), self::moment($context->time)];
                sort($moments, SORT_STRING);

                throw new LogicException("{$this->spec->id} was given candles of two moments, {$moments[0]} and {$moments[1]}: a universe is decided one moment at a time.");
            }

            $groups[(string) $context->symbol][] = $context;
        }

        ksort($groups, SORT_STRING);
        $this->section->begin();
        $closed = [];

        foreach ($groups as $symbol => $group) {
            $symbol = (string) $symbol;
            $own = $this->follow($symbol, $group);

            if ($own === null) {
                continue;
            }

            $closed[$symbol] = $own;
            $values = $this->programs[$symbol]->collect($this->member->frame($own));

            if ($this->universe->isMember($symbol, $time) && $this->warm($symbol)) {
                $this->section->enter($symbol, $values);
            }
        }

        $managed = [];
        $entries = [];

        foreach ($closed as $symbol => $context) {
            $program = $this->programs[$symbol];
            $values = $program->evaluate($this->member->frame($context));

            if ($context->position !== null) {
                $left = ! $this->universe->isMember($symbol, $time);
                array_push($managed, ...($left ? [new Close($context->symbol, 'universe.left')] : $this->member->decide($context, $values)));

                continue;
            }

            if (! $this->section->has($symbol)) {
                continue;
            }

            foreach ($this->member->decide($context, $values) as $intent) {
                $entries[] = ['place' => $this->place($intent, $program, $symbol), 'short' => $intent instanceof OpenShort, 'symbol' => $symbol, 'intent' => $intent];
            }
        }

        usort($entries, function (array $a, array $b): int {
            $order = [$a['place'], (int) $a['short']] <=> [$b['place'], (int) $b['short']];

            return $order !== 0 ? $order : strcmp($a['symbol'], $b['symbol']);
        });

        return [...$managed, ...array_map(fn (array $entry): Intent => $entry['intent'], $entries)];
    }

    /**
     * Feeds one instrument's candles of this moment to its program, the longer timeframes first, and counts them.
     * A candle of the strategy's timeframe that does not follow the last one starts the instrument's history again.
     *
     * @param  list<StrategyContext>  $group
     * @return ?StrategyContext the candle of the strategy's own timeframe, when one closed
     */
    private function follow(string $symbol, array $group): ?StrategyContext
    {
        $timeframe = $this->spec->timeframe;
        $longest = $this->member->longerTimeframes()[0] ?? null;
        $own = null;

        foreach ($group as $context) {
            $own = $context->candle->timeframe === $timeframe ? $context : $own;
        }

        $open = $own?->candle->openTime->getTimestamp();
        $last = $this->seen[$symbol]['last'] ?? null;

        if (! isset($this->programs[$symbol]) || ($open !== null && $last !== null && $open !== $last + $timeframe->seconds())) {
            $this->programs[$symbol] = $this->member->memberProgram($this->section, $symbol);
            $this->seen[$symbol] = ['bars' => 0, 'longest' => 0, 'last' => null];
        }

        usort($group, fn (StrategyContext $a, StrategyContext $b): int => $b->candle->timeframe->seconds() <=> $a->candle->timeframe->seconds());

        foreach ($group as $context) {
            if ($context->candle->timeframe === $timeframe) {
                continue;
            }

            $this->programs[$symbol]->update($context->candle->timeframe->value, $this->member->frame($context));
            $this->seen[$symbol]['longest'] += $context->candle->timeframe === $longest ? 1 : 0;
        }

        if ($own !== null) {
            $this->seen[$symbol]['bars']++;
            $this->seen[$symbol]['last'] = $open;
        }

        return $own;
    }

    /** Whether $symbol has the strategy's warm-up behind it: as much time in its own candles, and as many of the longest timeframe. */
    private function warm(string $symbol): bool
    {
        $seen = $this->seen[$symbol];
        $bars = $this->member->warmupBars();
        $longest = $this->member->longerTimeframes()[0] ?? null;

        if ($seen['bars'] * $this->spec->timeframe->seconds() < $bars * ($longest ?? $this->spec->timeframe)->seconds()) {
            return false;
        }

        return $longest === null || $seen['longest'] >= $bars;
    }

    /** The place of an entry among the others of this moment, from 0 for the first; an entry nothing ranks is last. */
    private function place(Intent $intent, Program $program, string $symbol): int
    {
        $ranking = $this->rankings[$intent instanceof OpenShort ? 'short' : 'long'] ?? null;
        $slot = $ranking === null ? null : $program->slotOf($ranking);
        $rank = $slot === null ? null : $this->section->rank($slot, $symbol);

        if ($ranking === null || $slot === null || $rank === null) {
            return PHP_INT_MAX;
        }

        return $ranking->name === 'bottom' ? $this->section->ranked($slot) - $rank : $rank - 1;
    }

    private static function moment(CarbonImmutable $time): string
    {
        return $time->utc()->format('Y-m-d H:i');
    }
}
