<?php

declare(strict_types=1);

namespace App\Simulation\Data;

use App\Portfolio\Data\RoundTrip;
use App\Simulation\Metrics\Performance;
use App\Simulation\Validation\DeflatedSharpe;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * What one parameter set of a grid gave, without the strategy that made it: its metrics, its daily returns and its
 * closed trades. It crosses process boundaries as plain arrays, so a grid split over workers merges back exactly.
 */
final readonly class SetOutcome
{
    /**
     * @param  array<string, string>  $parameters
     * @param  list<Decimal>  $daily
     * @param  list<array{opened: CarbonImmutable, closed: CarbonImmutable, net: Decimal}>  $trips
     */
    public function __construct(
        public array $parameters,
        public Decimal $return,
        public ?Decimal $sharpe,
        public ?Decimal $dailySharpe,
        public Decimal $maxDrawdown,
        public int $trades,
        public ?Decimal $profitFactor,
        public ?Decimal $expectancy,
        public array $daily,
        public array $trips,
    ) {}

    public static function of(ParticipantResult $result): self
    {
        $metrics = Performance::measure($result->equity, $result->ledger);
        $daily = Performance::dailyReturns($result->equity);

        return new self(
            $result->participant->strategy->identity()->parameters,
            $metrics->return,
            $metrics->sharpe,
            count($daily) >= 2 ? DeflatedSharpe::sharpe($daily) : null,
            $metrics->maxDrawdown,
            $metrics->trades->count,
            $metrics->trades->profitFactor,
            $metrics->trades->expectancy,
            $daily,
            array_map(fn (RoundTrip $trip): array => ['opened' => $trip->openedAt, 'closed' => $trip->closedAt, 'net' => $trip->netProfit], $result->ledger->roundTrips()),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $text = fn (?Decimal $value): ?string => $value === null ? null : (string) $value;

        return [
            'parameters' => $this->parameters,
            'return' => (string) $this->return,
            'sharpe' => $text($this->sharpe),
            'daily_sharpe' => $text($this->dailySharpe),
            'max_drawdown' => (string) $this->maxDrawdown,
            'trades' => $this->trades,
            'profit_factor' => $text($this->profitFactor),
            'expectancy' => $text($this->expectancy),
            'daily' => array_map('strval', $this->daily),
            'trips' => array_map(fn (array $trip): array => [
                'opened' => $trip['opened']->toIso8601ZuluString(), 'closed' => $trip['closed']->toIso8601ZuluString(), 'net' => (string) $trip['net'],
            ], $this->trips),
        ];
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $decimal = fn (mixed $value): ?Decimal => is_string($value) ? Decimal::of($value) : null;
        $list = fn (mixed $value): array => is_array($value) ? array_values($value) : [];

        $parameters = [];

        foreach (is_array($data['parameters'] ?? null) ? $data['parameters'] : [] as $name => $value) {
            $parameters[(string) $name] = is_string($value) ? $value : '';
        }

        return new self(
            $parameters,
            $decimal($data['return'] ?? null) ?? Decimal::zero(),
            $decimal($data['sharpe'] ?? null),
            $decimal($data['daily_sharpe'] ?? null),
            $decimal($data['max_drawdown'] ?? null) ?? Decimal::zero(),
            is_int($data['trades'] ?? null) ? $data['trades'] : 0,
            $decimal($data['profit_factor'] ?? null),
            $decimal($data['expectancy'] ?? null),
            array_map(fn (mixed $v): Decimal => Decimal::of(is_string($v) ? $v : '0'), $list($data['daily'] ?? null)),
            array_map(fn (mixed $trip): array => [
                'opened' => CarbonImmutable::parse(is_array($trip) && is_string($trip['opened'] ?? null) ? $trip['opened'] : '', 'UTC'),
                'closed' => CarbonImmutable::parse(is_array($trip) && is_string($trip['closed'] ?? null) ? $trip['closed'] : '', 'UTC'),
                'net' => Decimal::of(is_array($trip) && is_string($trip['net'] ?? null) ? $trip['net'] : '0'),
            ], $list($data['trips'] ?? null)),
        );
    }
}
