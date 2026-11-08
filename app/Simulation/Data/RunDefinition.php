<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use App\Execution\Data\FeeSchedule;
use App\Execution\Engine;
use App\Execution\Venues\CostModel;
use App\Market\Data\MarketType;
use App\Risk\RiskPolicy;
use App\Simulation\Report\CanonicalJson;
use App\Strategy\Data\StrategyIdentity;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * Everything that decides a backtest's result (docs/simulation.md, section 4): the same definition on the same data
 * gives the same report, byte for byte. The trading window is [from, to); indicators warm up from $warmupFrom.
 */
final readonly class RunDefinition
{
    /**
     * @param  list<Symbol>  $symbols
     * @param  list<string>  $benchmarks
     */
    public function __construct(
        public StrategyIdentity $strategy,
        public MarketType $market,
        public array $symbols,
        public CarbonImmutable $from,
        public CarbonImmutable $to,
        public CarbonImmutable $warmupFrom,
        public Money $initialBalance,
        public FeeSchedule $fees,
        public CostModel $costs,
        public RiskPolicy $policy,
        public array $benchmarks = [],
        public int $seed = 0,
        public ?Decimal $futuresBalance = null,
        public ?FeeSchedule $perpetualFees = null,
        public ?Decimal $maintenanceRate = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'strategy' => [
                'name' => $this->strategy->name,
                'version' => $this->strategy->version,
                'parameters' => $this->strategy->parameters,
                'key' => $this->strategy->key(),
            ],
            'market' => $this->market->value,
            'symbols' => array_map(fn (Symbol $symbol): string => (string) $symbol, $this->symbols),
            'from' => $this->from,
            'to' => $this->to,
            'warmup_from' => $this->warmupFrom,
            'initial_balance' => ['amount' => $this->initialBalance->amount, 'asset' => (string) $this->initialBalance->asset],
            'fees' => ['maker' => $this->fees->maker, 'taker' => $this->fees->taker],
            'cost_model' => [
                'version' => $this->costs->version,
                'half_spread' => $this->costs->halfSpread,
                'impact' => $this->costs->impact,
                'limit_volume_share' => $this->costs->limitVolumeShare,
            ],
            'risk' => $this->policy->toArray(),
            'benchmarks' => $this->benchmarks,
            'seed' => $this->seed,
            'engine_version' => Engine::VERSION,
        ] + ($this->futuresBalance === null ? [] : [
            // Present only when the run trades perpetuals, so that spot definitions keep their hash.
            'initial_futures_balance' => $this->futuresBalance,
            'perpetual' => [
                'fees' => ['maker' => $this->perpetualFees?->maker, 'taker' => $this->perpetualFees?->taker],
                'maintenance_margin_rate' => $this->maintenanceRate,
            ],
        ]);
    }

    public function hash(): string
    {
        return CanonicalJson::hash($this->toArray());
    }
}
