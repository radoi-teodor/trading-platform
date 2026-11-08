<?php

declare(strict_types=1);

namespace App\Simulation;

use App\Execution\Data\EngineMode;
use App\Execution\Engine;
use App\Execution\Journal\InMemoryDecisionLog;
use App\Execution\Venues\SimulatedVenue;
use App\Market\Contracts\CandleFeed;
use App\Market\Data\RulesHistory;
use App\Portfolio\Ledger;
use App\Simulation\Data\BacktestResult;
use App\Simulation\Data\Participant;
use App\Simulation\Data\ParticipantResult;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Metrics\EquityTracker;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;

/**
 * Runs the engine loop over history (D-8): one SimClock, one pass over the candles, and for each participant (the
 * strategy and its benchmarks) its own simulated venue, ledger and engine. Each minute the venues match their
 * orders against the candles first, then the engines handle the minute that just closed (docs/engine.md, section 2).
 */
final readonly class Backtest
{
    public function __construct(private CandleFeed $feed) {}

    /**
     * @param  array<string, RulesHistory>  $rules  by unified symbol
     * @param  list<Participant>  $benchmarks
     */
    public function run(RunDefinition $definition, Participant $main, array $rules, array $benchmarks = []): BacktestResult
    {
        $participants = [$main, ...$benchmarks];
        $feedFrom = $definition->warmupFrom;

        foreach ($participants as $participant) {
            $feedFrom = $participant->warmupFrom < $feedFrom ? $participant->warmupFrom : $feedFrom;
        }

        $clock = new SimClock($feedFrom);
        $runs = array_map(function (Participant $participant) use ($definition, $rules, $clock): array {
            $spot = $participant->spotBalance ?? $definition->initialBalance;
            $futures = $participant->futuresBalance ?? $definition->futuresBalance ?? Decimal::zero();
            $venue = new SimulatedVenue(
                $clock, $definition->fees, $definition->costs, $rules, [$spot],
                $definition->perpetualFees, $definition->maintenanceRate, $futures,
            );
            $ledger = new Ledger($spot->asset, [$spot], $futures);
            $log = new InMemoryDecisionLog;
            $engine = new Engine(
                EngineMode::Backtest, $venue, $ledger, $participant->policy, $log, [$participant->strategy], $rules,
                $definition->from, $participant->warmupFrom, $definition->maintenanceRate,
            );

            return [$participant, $venue, $engine, $log, new EquityTracker($spot->amount->plus($futures), $definition->from)];
        }, $participants);

        $fingerprint = hash_init('sha256');
        $candles = 0;
        $symbols = [];

        foreach ($this->feed->minutes(self::symbols($definition, $participants), $feedFrom, $definition->to) as $minute) {
            $clock->advanceTo($minute->closedAt);

            foreach ($minute->candles as $symbol => $candle) {
                $candles++;
                hash_update($fingerprint, implode('|', [
                    $symbol, $candle->openTime->getTimestamp(), $candle->open, $candle->high, $candle->low, $candle->close,
                    $candle->volume, $candle->quoteVolume, $candle->tradeCount, $candle->takerBuyVolume,
                ])."\n");
            }

            foreach ($runs as [, $venue, $engine, , $tracker]) {
                foreach ($minute->candles as $symbol => $candle) {
                    $venue->match($symbols[$symbol] ??= Symbol::parse($symbol), $candle);
                }

                // Funding at the end of the minute, on the positions open then, at that minute's close (D-44).
                foreach ($minute->funding as $symbol => $rate) {
                    if (isset($minute->candles[$symbol])) {
                        $venue->settleFunding($symbols[$symbol] ??= Symbol::parse($symbol), $rate, $minute->candles[$symbol]->close);
                    }
                }

                $equity = $engine->handle($minute);

                if ($minute->closedAt > $definition->from) {
                    $ledger = $engine->ledger();
                    $tracker->record($minute->closedAt, $equity, $ledger->positions() !== [] || $ledger->perpPositions() !== []);
                }
            }
        }

        $results = array_map(
            fn (array $run): ParticipantResult => new ParticipantResult($run[0], $run[4], $run[2]->ledger(), $run[3], $run[2]->marks()),
            $runs,
        );

        return new BacktestResult(hash_final($fingerprint), $candles, $feedFrom, $results[0], array_slice($results, 1));
    }

    /**
     * The run's symbols, then any other a participant follows: a perpetual strategy's benchmarks hold the spot
     * instrument of the same base (D-49).
     *
     * @param  list<Participant>  $participants
     * @return list<Symbol>
     */
    private static function symbols(RunDefinition $definition, array $participants): array
    {
        $symbols = [];

        foreach ($definition->symbols as $symbol) {
            $symbols[(string) $symbol] = $symbol;
        }

        foreach ($participants as $participant) {
            foreach ($participant->strategy->subscriptions() as $subscription) {
                $symbols[(string) $subscription->symbol] ??= $subscription->symbol;
            }
        }

        return array_values($symbols);
    }
}
