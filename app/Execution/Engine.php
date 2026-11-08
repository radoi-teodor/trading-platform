<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Contracts\DecisionLog;
use App\Execution\Contracts\Venue;
use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Data\Income;
use App\Execution\Data\IncomeKind;
use App\Execution\Data\IntentRecord;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRole;
use App\Execution\Data\TrackedOrder;
use App\Execution\Data\VenueFill;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Market\Feed\CandleAggregator;
use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\Position;
use App\Portfolio\Ledger;
use App\Risk\Data\AccountView;
use App\Risk\Data\RejectionReason;
use App\Risk\Data\RiskEventKind;
use App\Risk\Data\RiskProfile;
use App\Risk\Data\Verdict;
use App\Risk\RiskEngine;
use App\Risk\RiskPolicy;
use App\Risk\RiskState;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * One account's engine (docs/engine.md). It handles each minute event to the end before the next, in the order of
 * section 2: reconcile with the venue, mark, update risk state, protect positions, run the strategies whose candle
 * closed, pass their intents through the risk engine and the order manager, journal. The same code runs a backtest,
 * paper and live trading (D-8); only the venue and the clock differ.
 */
final class Engine
{
    /** Raised by hand with every change of behaviour (D-42). */
    public const int VERSION = 1;

    private readonly RiskState $riskState;

    private readonly RiskEngine $risk;

    private readonly OrderBook $book;

    private readonly OrderManager $orders;

    private Marks $marks;

    private int $cursor = 0;

    /** @var array<string, CandleAggregator> by symbol and timeframe */
    private array $aggregators = [];

    /** @var array<string, Symbol> parsed once */
    private array $symbols = [];

    /**
     * @param  list<Strategy>  $strategies
     * @param  array<string, RulesHistory>  $rules  by unified symbol
     */
    public function __construct(
        public readonly EngineMode $mode,
        private readonly Venue $venue,
        private readonly Ledger $ledger,
        private readonly RiskPolicy $policy,
        private readonly DecisionLog $log,
        private readonly array $strategies,
        private readonly array $rules,
        private readonly CarbonImmutable $tradingFrom,
        private readonly ?CarbonImmutable $decideFrom = null,
    ) {
        if ($policy->profile === RiskProfile::Benchmark && $mode !== EngineMode::Backtest) {
            throw new InvalidArgumentException("The benchmark risk profile is for backtests only (D-40); this engine runs {$mode->value}.");
        }

        $this->riskState = new RiskState($policy);
        $this->risk = new RiskEngine($policy, $this->riskState);
        $this->book = new OrderBook;
        $this->orders = new OrderManager($venue, $this->book);
        $this->marks = new Marks($ledger->quote);

        foreach ($strategies as $strategy) {
            foreach ($strategy->subscriptions() as $subscription) {
                $this->aggregators["{$subscription->symbol}|{$subscription->timeframe->value}"] ??= new CandleAggregator($subscription->timeframe);
            }
        }
    }

    /** Handles the minute that closed at $event->closedAt; returns the account's equity marked at that moment. */
    public function handle(MinuteCandles $event): Decimal
    {
        $time = $event->closedAt;
        $this->reconcile();

        foreach ($event->candles as $symbol => $candle) {
            $parsed = $this->symbols[$symbol] ??= Symbol::parse($symbol);
            $this->marks = $this->marks->with($parsed, $candle->close);
            $this->ledger->observe($parsed, $candle->high, $candle->low);
        }

        $equity = $this->ledger->equity($this->marks);
        $this->applyRiskEvents($time, $equity);
        $this->protectAll($time);
        $this->runStrategies($event);

        return $equity;
    }

    public function ledger(): Ledger
    {
        return $this->ledger;
    }

    public function marks(): Marks
    {
        return $this->marks;
    }

    /** Step 3: book the venue's fills, then require the ledger and the venue to agree (D-10, D-41). */
    private function reconcile(): void
    {
        foreach ($this->venue->updatesSince($this->cursor) as $update) {
            $this->cursor = $update->sequence;

            if ($update instanceof Income) {
                $this->bookIncome($update);

                continue;
            }

            $order = $this->book->apply($update);

            if ($update->fill !== null) {
                $this->ledger->apply($this->booking($update->fill, $order));
            }
        }

        $venueBalances = $this->venue->balances();
        $ledgerBalances = $this->ledger->balances();

        foreach (array_unique([...array_keys($venueBalances), ...array_keys($ledgerBalances)]) as $asset) {
            $code = (string) $asset;
            $ours = $ledgerBalances[$code] ?? Decimal::zero();
            $theirs = isset($venueBalances[$code]) ? $venueBalances[$code]->total() : Decimal::zero();

            if (! $ours->equals($theirs)) {
                throw new ReconciliationMismatch("The ledger holds {$ours} {$code}; the venue holds {$theirs}.");
            }
        }

        $ours = array_map(fn (TrackedOrder $order): string => $order->id->value, $this->book->working());
        $theirs = array_map(fn ($order): string => $order->clientOrderId->value, $this->venue->openOrders());
        sort($ours);
        sort($theirs);

        if ($ours !== $theirs) {
            throw new ReconciliationMismatch(sprintf(
                'Working orders differ: the engine has [%s], the venue has [%s].',
                implode(', ', $ours),
                implode(', ', $theirs),
            ));
        }
    }

    /** Funding and liquidations belong to the one strategy that holds the perpetual (D-43). */
    private function bookIncome(Income $income): void
    {
        $owner = null;

        foreach ($this->ledger->perpPositions() as $position) {
            $owner = $position->symbol->equals($income->symbol) ? $position->strategy : $owner;
        }

        if ($owner === null) {
            throw new ReconciliationMismatch("The venue reports {$income->kind->value} on {$income->symbol}, which no strategy holds.");
        }

        if ($income->kind === IncomeKind::Funding) {
            $this->ledger->applyFunding($income->id, $owner, $income->symbol, $income->amount, $income->time);

            return;
        }

        $this->ledger->liquidate($income->id, $owner, $income->symbol, $income->price ?? throw new ReconciliationMismatch('A liquidation without a price.'), $income->time);
        $this->log->event(new EngineRecord($income->time, 'liquidation', "{$income->symbol} of {$owner}: margin {$income->amount}"));
    }

    private function booking(VenueFill $fill, TrackedOrder $order): Fill
    {
        $feeAsset = $fill->fee->asset;
        $feeValue = match (true) {
            $feeAsset->equals($fill->symbol->quote) => $fill->fee->amount,
            $feeAsset->equals($fill->symbol->base) => $fill->fee->amount->times($fill->price->value),
            default => $fill->fee->amount->times(
                ($this->marks->priceOf($feeAsset) ?? throw new ReconciliationMismatch("No price to value a fee in {$feeAsset}."))->value,
            ),
        };

        return new Fill(
            id: $fill->id,
            time: $fill->time,
            strategy: $order->strategy,
            symbol: $fill->symbol,
            side: $fill->side,
            quantity: $fill->quantity,
            price: $fill->price,
            fee: $fill->fee,
            feeValue: $feeValue,
            slippage: $fill->slippage,
            initialStop: $order->role === OrderRole::Entry ? $this->orders->plan($order->strategy, $order->symbol)?->stop : null,
            exitReason: $order->role->exitReason(),
        );
    }

    private function applyRiskEvents(CarbonImmutable $time, Decimal $equity): void
    {
        foreach ($this->riskState->update($time, $equity) as $event) {
            // Phase 2 enters at market only, and a market order fills or expires within the minute that follows
            // its decision, so no entry is still working when a limit is reached (docs/engine.md, section 5).
            $this->log->event(new EngineRecord($time, $event->kind->value, "equity {$event->equity}"));

            if ($event->kind !== RiskEventKind::DrawdownFlatten) {
                continue;
            }

            foreach ($this->ledger->positions() as $position) {
                $this->exit($position, OrderRole::Flatten, $time);
            }
        }
    }

    /** Step 6: every position keeps its protective orders on the venue; remainders too small to sell become dust. */
    private function protectAll(CarbonImmutable $time): void
    {
        foreach ($this->ledger->positions() as $position) {
            $this->protect($position, $time);
        }
    }

    private function protect(Position $position, CarbonImmutable $time): void
    {
        $outcome = $this->orders->protect($position, $this->rulesAt($position->symbol, $time), $this->mark($position->symbol), $time);

        if ($outcome === 'dust') {
            $this->writeOffDust($position, $time);
        } elseif ($outcome instanceof OrderAck) {
            $this->log->event(new EngineRecord($time, 'venue_rejected', "protection of {$position->symbol} for {$position->strategy}: {$outcome->reason}"));
        }
    }

    private function runStrategies(MinuteCandles $event): void
    {
        $closed = [];

        foreach ($event->candles as $symbol => $minute) {
            foreach ($this->aggregators as $key => $aggregator) {
                if (str_starts_with($key, "{$symbol}|")) {
                    $closed[$key] = $aggregator->push($minute);
                }
            }
        }

        // Before its own warmup a strategy is not called, so a run that reads further back for a benchmark costs it
        // nothing; its candles are still aggregated, so its periods stay aligned.
        if ($this->decideFrom !== null && $event->closedAt <= $this->decideFrom) {
            return;
        }

        foreach ($this->strategies as $strategy) {
            foreach ($strategy->subscriptions() as $subscription) {
                $candle = $closed["{$subscription->symbol}|{$subscription->timeframe->value}"] ?? null;

                if ($candle !== null) {
                    $this->decide($strategy, $subscription->symbol, $candle, $event->closedAt);
                }
            }
        }
    }

    private function decide(Strategy $strategy, Symbol $symbol, Candle $candle, CarbonImmutable $time): void
    {
        $key = $strategy->identity()->key();
        $position = $this->ledger->position($key, $symbol);
        $plan = $this->orders->plan($key, $symbol);
        $view = $position === null ? null : new PositionView($position->quantity, $position->entryPrice(), $plan?->stop, $position->openedAt);
        $intents = $strategy->onCandle(new StrategyContext($time, $symbol, $candle, $view));
        $records = [];
        $entered = [];

        foreach ($intents as $intent) {
            $records[] = $time < $this->tradingFrom
                ? $this->record($intent, Verdict::rejected(RejectionReason::Warmup))
                : $this->act($key, $intent, $candle->close, $time, $entered);
        }

        $this->log->decision(new DecisionRecord(
            sprintf('%s-%s', $key, base_convert((string) $time->getTimestamp(), 10, 36)),
            $time,
            $strategy->identity()->label(),
            $symbol,
            (string) $candle->close,
            $records,
        ));
    }

    /** @param array<string, true> $entered entries already decided in this call, by symbol */
    private function act(string $strategy, Intent $intent, Price $price, CarbonImmutable $time, array &$entered): IntentRecord
    {
        $symbol = $intent->symbol();
        $position = $this->ledger->position($strategy, $symbol);

        if ($intent instanceof OpenLong) {
            if (isset($entered[(string) $symbol])) {
                return $this->record($intent, Verdict::rejected(RejectionReason::DuplicateIntent));
            }

            $entered[(string) $symbol] = true;
            $verdict = $this->risk->assessEntry($intent, $this->account($strategy, $symbol), $this->rulesAt($symbol, $time), $price, $time);

            if (! $verdict->approved) {
                return $this->record($intent, $verdict);
            }

            $ack = $this->orders->enter($strategy, $symbol, $verdict, $intent->stop, $intent->target, $time);

            return $this->record($intent, $ack->accepted ? $verdict : Verdict::rejected(RejectionReason::VenueRejected), $ack);
        }

        if ($position === null) {
            return $this->record($intent, Verdict::rejected(RejectionReason::NoPosition));
        }

        if ($intent instanceof Close) {
            return $this->record($intent, Verdict::approved(), $this->exit($position, OrderRole::Exit, $time));
        }

        if (! $intent instanceof MoveStop) {
            throw new InvalidArgumentException('Unknown intent '.$intent::class.'.');
        }

        $verdict = $this->risk->assessStopMove($intent, $this->orders->plan($strategy, $symbol)?->stop, $price);

        if ($verdict->approved) {
            $this->orders->moveStop($strategy, $symbol, $intent->stop);
            $this->protect($position, $time);
        }

        return $this->record($intent, $verdict);
    }

    private function exit(Position $position, OrderRole $role, CarbonImmutable $time): ?OrderAck
    {
        $ack = $this->orders->exit($position, $role, $this->rulesAt($position->symbol, $time), $this->mark($position->symbol), $time);

        if ($ack === null) {
            $this->writeOffDust($position, $time);
        }

        return $ack;
    }

    private function writeOffDust(Position $position, CarbonImmutable $time): void
    {
        $this->ledger->writeOffDust($position->strategy, $position->symbol, $time);
        $this->log->event(new EngineRecord($time, 'dust', "{$position->quantity} {$position->symbol->base} of {$position->strategy}"));
    }

    private function account(string $strategy, Symbol $symbol): AccountView
    {
        $openRisk = Decimal::zero();
        $positions = 0;
        $holds = false;

        foreach ($this->ledger->positions() as $position) {
            $positions++;
            $holds = $holds || ($position->strategy === $strategy && $position->symbol->equals($symbol));
            $stop = $this->orders->plan($position->strategy, $position->symbol)?->stop;
            $perUnit = $stop === null ? Decimal::zero() : $position->entryPrice()->minus($stop->value);
            $openRisk = $perUnit->isPositive() ? $openRisk->plus($perUnit->times($position->quantity)) : $openRisk;
        }

        foreach ($this->book->working() as $order) {
            if ($order->role === OrderRole::Entry) {
                $positions++;
                $holds = $holds || ($order->strategy === $strategy && $order->symbol->equals($symbol));
                $openRisk = $openRisk->plus($this->orders->plan($order->strategy, $order->symbol)->plannedRisk ?? Decimal::zero());
            }
        }

        $quote = $this->venue->balances()[(string) $this->ledger->quote] ?? null;

        return new AccountView(
            $this->ledger->equity($this->marks),
            $quote === null ? Decimal::zero() : $quote->free,
            $positions,
            $openRisk,
            $holds,
        );
    }

    private function record(Intent $intent, Verdict $verdict, ?OrderAck $ack = null): IntentRecord
    {
        return new IntentRecord(
            type: match (true) {
                $intent instanceof OpenLong => 'open_long',
                $intent instanceof Close => 'close',
                $intent instanceof MoveStop => 'move_stop',
                default => $intent::class,
            },
            reason: $intent->reason(),
            approved: $verdict->approved && ($ack === null || $ack->accepted),
            rejection: $verdict->rejection->value ?? ($ack !== null && ! $ack->accepted ? "venue: {$ack->reason}" : null),
            quantity: $verdict->quantity === null ? null : (string) $verdict->quantity,
            quoteQuantity: $verdict->quoteQuantity === null ? null : (string) $verdict->quoteQuantity,
            adjustments: $verdict->adjustments,
            orders: $ack !== null && $ack->accepted ? [$ack->clientOrderId->value] : [],
        );
    }

    private function rulesAt(Symbol $symbol, CarbonImmutable $time): InstrumentRules
    {
        return ($this->rules[(string) $symbol] ?? throw new InvalidArgumentException("No exchange filters for {$symbol}."))->at($time);
    }

    private function mark(Symbol $symbol): Price
    {
        return $this->marks->priceOf($symbol->base) ?? throw new ReconciliationMismatch("No price for {$symbol} yet.");
    }
}
