<?php

declare(strict_types=1);

namespace App\Simulation\Report;

use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineRecord;
use App\Market\Data\RulesHistory;
use App\Portfolio\Data\PerpPosition;
use App\Portfolio\Data\Position;
use App\Portfolio\Data\RoundTrip;
use App\Simulation\Data\BacktestResult;
use App\Simulation\Data\ParticipantResult;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Metrics\Performance;
use App\Support\Asset;
use App\Support\Number\Decimal;

/** Assembles a backtest report (D-42): definition, data identity, metrics, trades, equity, decisions, benchmarks. */
final class ReportBuilder
{
    /**
     * @param  array<string, RulesHistory>  $rules
     * @param  array<string, string>  $missingBenchmarks  label => why it could not run
     */
    public static function build(RunDefinition $definition, BacktestResult $result, array $rules, array $missingBenchmarks = []): Report
    {
        $assumed = [];

        foreach ($definition->symbols as $symbol) {
            $history = $rules[(string) $symbol] ?? null;

            if ($history !== null && $history->isAssumedAt($result->feedFrom)) {
                $assumed[(string) $symbol] = $history->knownFrom();
            }
        }

        $benchmarks = ['cash' => ['return' => Decimal::zero()]];

        foreach ($result->benchmarks as $benchmark) {
            $benchmarks[$benchmark->participant->label] = self::summary($benchmark);
        }

        foreach ($missingBenchmarks as $label => $reason) {
            $benchmarks[$label] = ['missing' => $reason];
        }

        $main = $result->main;
        $metrics = Performance::measure($main->equity, $main->ledger);

        return new Report([
            'definition' => $definition->toArray(),
            'data' => [
                'fingerprint' => $result->fingerprint,
                'candles' => $result->candles,
                'read_from' => $result->feedFrom,
                'filters_assumed_before' => $assumed,
            ],
            'result' => $metrics->toArray(),
            'trades' => array_map(self::trip(...), $main->ledger->roundTrips()),
            'open_positions' => [...array_map(self::position(...), $main->ledger->positions()), ...array_map(self::perpetual(...), $main->ledger->perpPositions())],
            'dust' => array_filter(
                array_combine(array_keys($main->ledger->balances()), array_map(fn (string $asset): Decimal => $main->ledger->dust(Asset::of($asset)), array_keys($main->ledger->balances()))),
                fn (Decimal $amount): bool => ! $amount->isZero(),
            ),
            'equity' => [
                'daily' => self::series($main->equity->daily()),
                'hourly' => self::series($main->equity->hourly()),
            ],
            'decisions' => self::decisions($main->log->decisions, $main->log->quietDecisions),
            'events' => array_map(fn (EngineRecord $event): array => [
                'time' => $event->time, 'kind' => $event->kind, 'detail' => $event->detail,
            ], $main->log->events),
            'benchmarks' => $benchmarks,
        ], $metrics);
    }

    /** @return array<string, mixed> */
    private static function summary(ParticipantResult $result): array
    {
        $metrics = Performance::measure($result->equity, $result->ledger);

        return [
            'strategy' => $result->participant->strategy->identity()->canonical(),
            'final_equity' => $metrics->finalEquity,
            'return' => $metrics->return,
            'max_drawdown' => $metrics->maxDrawdown,
            'sharpe' => $metrics->sharpe,
            'time_in_market' => $metrics->timeInMarket,
        ];
    }

    /** @return array<string, mixed> */
    private static function trip(RoundTrip $trip): array
    {
        return [
            'symbol' => $trip->symbol, 'opened_at' => $trip->openedAt, 'closed_at' => $trip->closedAt,
            'quantity' => $trip->quantity, 'entry_price' => $trip->entryPrice, 'exit_price' => $trip->exitPrice,
            'gross_profit' => $trip->grossProfit, 'fees' => $trip->fees, 'slippage' => $trip->slippage,
            'net_profit' => $trip->netProfit, 'initial_risk' => $trip->initialRisk, 'r_multiple' => $trip->rMultiple,
            'mae' => $trip->maximumAdverseExcursion, 'mfe' => $trip->maximumFavourableExcursion,
            'exit_reason' => $trip->exitReason, 'dust' => $trip->dust,
        ] + ($trip->funding === null ? [] : ['side' => $trip->side, 'funding' => $trip->funding]);
    }

    /** @return array<string, mixed> */
    private static function position(Position $position): array
    {
        return [
            'symbol' => $position->symbol, 'opened_at' => $position->openedAt, 'quantity' => $position->quantity,
            'entry_price' => $position->entryPrice(), 'fees' => $position->fees,
        ];
    }

    /** @return array<string, mixed> */
    private static function perpetual(PerpPosition $position): array
    {
        return [
            'symbol' => $position->symbol, 'opened_at' => $position->openedAt, 'side' => $position->side,
            'quantity' => $position->quantity, 'entry_price' => $position->entryPrice, 'margin' => $position->margin,
            'leverage' => $position->leverage, 'fees' => $position->fees, 'funding' => $position->funding,
        ];
    }

    /**
     * @param  array<string, Decimal>  $points
     * @return list<array{time: string, equity: Decimal}>
     */
    private static function series(array $points): array
    {
        return array_map(fn (string $time, Decimal $equity): array => ['time' => $time, 'equity' => $equity], array_keys($points), $points);
    }

    /**
     * @param  list<DecisionRecord>  $decisions
     * @return array<string, mixed>
     */
    private static function decisions(array $decisions, int $quiet): array
    {
        $intents = 0;
        $approved = 0;
        $rejected = [];

        foreach ($decisions as $decision) {
            foreach ($decision->intents as $intent) {
                $intents++;
                $approved += $intent->approved ? 1 : 0;

                if (! $intent->approved) {
                    $reason = $intent->rejection ?? 'unknown';
                    $rejected[$reason] = ($rejected[$reason] ?? 0) + 1;
                }
            }
        }

        ksort($rejected, SORT_STRING);

        return ['count' => count($decisions) + $quiet, 'intents' => $intents, 'approved' => $approved, 'rejected' => $rejected];
    }
}
