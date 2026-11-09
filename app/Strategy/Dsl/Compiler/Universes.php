<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Compiler;

use App\Market\Instruments\ResearchUniverse;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Document\StrategySpec;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/** Who the members of a universe strategy are over a run (D-92), from the research universe as it was built (D-89). */
final readonly class Universes
{
    public function __construct(private ResearchUniverse $research) {}

    /**
     * The members of $spec's universe in each month from $from's to $to's. Refused when the research universe is
     * not built, was built for fewer members than the rule asks for, or has nobody in one of the months: a strategy
     * never runs on another universe than the one it names.
     */
    public function schedule(StrategySpec $spec, CarbonImmutable $from, CarbonImmutable $to): UniverseSchedule
    {
        $rule = $spec->universe ?? throw new InvalidArgumentException("{$spec->id} names its instruments: it has no universe.");
        $built = $this->research->built() ?? throw new InvalidArgumentException('The research universe is not built: run market:universe:build first.');

        if ($rule->n > $built['top']) {
            throw new InvalidArgumentException("{$spec->id} trades the top {$rule->n}, and the research universe is built for the top {$built['top']}: build it again with --top={$rule->n}.");
        }

        $months = $this->research->months($rule->n, $from, $to);

        foreach ($months as $month => $members) {
            if ($members === []) {
                throw new InvalidArgumentException("The research universe has no members for {$month}: it is built from {$built['from']} to {$built['to']}.");
            }
        }

        return new UniverseSchedule($months);
    }
}
