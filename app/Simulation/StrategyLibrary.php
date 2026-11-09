<?php

declare(strict_types=1);

namespace App\Simulation;

use App\Market\Contracts\InstrumentDirectory;
use App\Risk\RiskPolicy;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\CheckResult;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\ReadBack;
use App\Strategy\Versions\StrategyRepository;
use App\Support\Clock\Clock;

/**
 * Strategy documents as the agents and, later, the UI see them: checked against the stored instruments and the
 * account policy's limits (D-54), and saved as versions (D-61). It keeps the risk policy out of the Agents module.
 */
final readonly class StrategyLibrary
{
    private PolicyLimits $limits;

    public function __construct(private Clock $clock, private InstrumentDirectory $instruments, RiskPolicy $policy)
    {
        $this->limits = new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions);
    }

    public function check(string $document): CheckResult
    {
        return DocumentChecker::check($document, $this->instruments, $this->limits);
    }

    public function versions(): StrategyRepository
    {
        return new StrategyRepository($this->clock, $this->instruments, $this->limits);
    }

    /**
     * What a checked document compiles to, for a reader.
     *
     * @return array{strategy: string, title: string, instruments: list<string>, universe: ?string, timeframe: string, warmup_candles: int, parameters: array<string, string>, benchmark: bool, read_back: string}|null
     */
    public function describe(CheckResult $result): ?array
    {
        $spec = $result->spec;

        if ($spec === null) {
            return null;
        }

        $strategy = CompiledStrategy::fromSpec($spec, [], 1, UniverseSchedule::none());

        return [
            'strategy' => $spec->id,
            'title' => $spec->title,
            'instruments' => array_map('strval', $spec->instruments),
            'universe' => $spec->universe?->text(),
            'timeframe' => $spec->timeframe->value,
            'warmup_candles' => $strategy->warmupBars(),
            'parameters' => $strategy->identity()->parameters,
            'benchmark' => $spec->benchmark,
            'read_back' => ReadBack::render($spec),
        ];
    }
}
