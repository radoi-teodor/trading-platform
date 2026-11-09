<?php

declare(strict_types=1);

namespace App\Execution;

use App\Execution\Contracts\DecisionLog;
use App\Execution\Contracts\Venue;
use App\Execution\Data\CommandKind;
use App\Execution\Data\DecisionRecord;
use App\Execution\Data\EngineCommand;
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
use App\Market\Data\FundingRate;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MinuteCandles;
use App\Market\Data\RulesHistory;
use App\Market\Feed\CandleAggregator;
use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\PerpPosition;
use App\Portfolio\Data\Position;
use App\Portfolio\Ledger;
use App\Risk\Breakers\MarketBreakers;
use App\Risk\Data\AccountView;
use App\Risk\Data\RejectionReason;
use App\Risk\Data\RiskEventKind;
use App\Risk\Data\RiskProfile;
use App\Risk\Data\Verdict;
use App\Risk\RiskEngine;
use App\Risk\RiskPolicy;
use App\Risk\RiskState;
use App\Strategy\Contracts\CrossSectionalStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\PerpetualTracker;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyLimits;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Perpetual;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use InvalidArgumentException;
use LogicException;

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

    /** How long before an instrument stops trading its positions are closed and entries refused (D-92). */
    private const int DELISTING_LEAD_MINUTES = 60;

    private readonly RiskState $riskState;

    private readonly RiskEngine $risk;

    private readonly OrderBook $book;

    private readonly OrderManager $orders;

    private Marks $marks;

    private int $cursor = 0;

    /** @var array<string, array<string, CandleAggregator>> by symbol, then timeframe */
    private array $aggregators = [];

    /** @var array<string, array<string, Symbol>> the candles each universe strategy follows: by strategy key, then "symbol|timeframe" */
    private array $followed = [];

    /** @var array<string, list<Intent>> a universe strategy's entries waiting for its closes to fill, by strategy key (D-92) */
    private array $held = [];

    /** @var array<string, Symbol> parsed once */
    private array $symbols = [];

    /** @var array<string, FundingRate> the last funding rate seen, by perpetual */
    private array $funding = [];

    /** The perpetuals' data for scalping as known at the current event (D-79). */
    private PerpetualTracker $perpetuals;

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
        ?Decimal $maintenanceRate = null,
        /** @var array<string, CarbonImmutable> when an instrument stops trading, by unified symbol; one that goes on trading is absent */
        private readonly array $delistings = [],
    ) {
        if ($policy->profile === RiskProfile::Benchmark && $mode !== EngineMode::Backtest) {
            throw new InvalidArgumentException("The benchmark risk profile is for backtests only (D-40); this engine runs {$mode->value}.");
        }

        $this->riskState = new RiskState($policy);
        $this->breakers = new MarketBreakers;
        $this->perpetuals = new PerpetualTracker;
        $this->risk = new RiskEngine($policy, $this->riskState, $maintenanceRate);
        $this->book = new OrderBook;
        $this->orders = new OrderManager($venue, $this->book);
        $this->marks = new Marks($ledger->quote);

        $owners = [];

        foreach ($strategies as $strategy) {
            if ($strategy instanceof LimitedStrategy) {
                $this->limits[$strategy->identity()->key()] = $strategy->limits();
            }

            foreach ($strategy->subscriptions() as $subscription) {
                // A universe's instruments come and go with their membership and lifetime, so their candles may restart.
                $this->aggregators[(string) $subscription->symbol][$subscription->timeframe->value] ??= new CandleAggregator($subscription->timeframe, $strategy instanceof CrossSectionalStrategy);

                if ($strategy instanceof CrossSectionalStrategy) {
                    $this->followed[$strategy->identity()->key()]["{$subscription->symbol}|{$subscription->timeframe->value}"] = $subscription->symbol;
                }

                $owner = $owners[(string) $subscription->symbol] ??= $strategy->identity()->key();

                if ($subscription->symbol->isPerpetual() && $owner !== $strategy->identity()->key()) {
                    throw new InvalidArgumentException("Two strategies trade {$subscription->symbol}; at most one strategy per perpetual (D-43).");
                }
            }
        }
    }

    /** The time of the event being handled. */
    private ?CarbonImmutable $time = null;

    private MarketBreakers $breakers;

    /** @var array<string, true> strategies paused by the operator, by label */
    private array $paused = [];

    /** Events in a row whose reconciliation found a difference (testnet and live). */
    private int $mismatches = 0;

    /** @var array<string, StrategyLimits> each limited strategy's own limits, by key (D-54) */
    private array $limits = [];

    /** @var array<string, array<string, int>> entries placed per strategy and UTC day */
    private array $entries = [];

    /** Handles the minute that closed at $event->closedAt; returns the account's equity marked at that moment. */
    /**
     * Handles the minute that closed at $event->closedAt; returns the account's equity marked at that moment.
     *
     * @param  list<EngineCommand>  $commands  applied once the venue is reconciled (docs/engine.md, section 9)
     */
    public function handle(MinuteCandles $event, array $commands = []): Decimal
    {
        $time = $event->closedAt;
        $this->time = $time;
        $this->reconcileOrTolerate($time);

        foreach ($this->orders->expireEntries($time) as $order) {
            $this->log->event(new EngineRecord($time, 'entry_expired', "{$order->id} of {$order->strategy}"));
        }

        $closes = [];

        foreach ($event->candles as $symbol => $candle) {
            $parsed = $this->symbols[$symbol] ??= Symbol::parse($symbol);
            $closes[(string) $parsed] = $candle->close;
            $this->ledger->observe($parsed, $candle->high, $candle->low);
        }

        $this->marks = $this->marks->withAll($closes);

        foreach ($event->funding as $symbol => $rate) {
            $this->funding[$symbol] = $rate;
        }

        $this->perpetuals->absorb($event);

        foreach ($commands as $command) {
            $this->apply($command, $time);
        }

        foreach ($this->policy->marketBreakers ? $this->breakers->observe($event) : [] as $breaker) {
            $this->log->event(new EngineRecord($time, $breaker->tripped ? 'breaker_tripped' : 'breaker_cleared', "{$breaker->kind} {$breaker->symbol}: {$breaker->detail}"));
        }

        $equity = $this->ledger->equity($this->marks);
        $this->applyRiskEvents($time, $equity);
        $this->closeDelisted($time);
        $this->protectAll($time);
        $this->runStrategies($event);

        return $equity;
    }

    /**
     * The account as the UI shows it: equity, balances, positions, working orders, halts, paused strategies and the
     * instruments a breaker blocks.
     *
     * @return array<string, mixed>
     */
    public function snapshot(): array
    {
        $time = $this->time;

        return [
            'time' => $time,
            'mode' => $this->mode->value,
            'equity' => $this->ledger->equity($this->marks),
            'balances' => $this->ledger->balances(),
            'futures_wallet' => $this->ledger->futuresWallet(),
            'positions' => [
                ...array_map(fn ($p): array => ['strategy' => $p->strategy, 'symbol' => (string) $p->symbol, 'side' => 'buy', 'quantity' => $p->quantity, 'entry' => $p->entryPrice(), 'stop' => $this->orders->plan($p->strategy, $p->symbol)?->stop?->value], $this->ledger->positions()),
                ...array_map(fn ($p): array => ['strategy' => $p->strategy, 'symbol' => (string) $p->symbol, 'side' => $p->side->value, 'quantity' => $p->quantity, 'entry' => $p->entryPrice, 'stop' => $this->orders->plan($p->strategy, $p->symbol)?->stop?->value], $this->ledger->perpPositions()),
            ],
            'orders' => array_map(fn (TrackedOrder $o): array => ['id' => $o->id->value, 'strategy' => $o->strategy, 'symbol' => (string) $o->symbol, 'role' => $o->role->value], $this->book->working()),
            'halt' => $time === null ? null : $this->riskState->entryBlock($time),
            'paused' => array_keys($this->paused),
            'blocked' => $time === null ? [] : array_values(array_filter(array_map(
                fn (string $symbol): ?string => ($why = $this->breakers->block($symbol, $time)) === null ? null : "{$symbol}: {$why}",
                array_keys($this->symbols),
            ))),
            'trips' => count($this->ledger->roundTrips()),
        ];
    }

    public function ledger(): Ledger
    {
        return $this->ledger;
    }

    public function marks(): Marks
    {
        return $this->marks;
    }

    /**
     * A backtest or paper engine and its venue are both our code, so a difference is a bug and stops the run. On
     * testnet (and later live) the venue is the truth (D-10): a difference is journaled, and one that lasts two
     * events halts the account (docs/risk-policy.md, section 3).
     */
    private function reconcileOrTolerate(CarbonImmutable $time): void
    {
        if ($this->mode !== EngineMode::Testnet && $this->mode !== EngineMode::Live) {
            $this->reconcile();

            return;
        }

        try {
            $this->reconcile();
            $this->mismatches = 0;
        } catch (ReconciliationMismatch $mismatch) {
            $this->mismatches++;
            $this->log->event(new EngineRecord($time, 'reconciliation_mismatch', $mismatch->getMessage()));

            if ($this->mismatches === 2) {
                $this->riskState->halt();
                $this->log->event(new EngineRecord($time, 'reconciliation_halt', 'a difference with the venue lasted two events'));
            }
        }
    }

    /** Step 2: a command of the operator or the command line (docs/risk-policy.md, section 5). */
    private function apply(EngineCommand $command, CarbonImmutable $time): void
    {
        $this->log->event(new EngineRecord($time, 'command', $command->describe()));

        match ($command->kind) {
            CommandKind::Halt => $this->halt($time),
            CommandKind::Resume => $this->riskState->resume(),
            CommandKind::Flatten => $this->flatten($time),
            CommandKind::Pause => $this->paused[$command->strategy ?? ''] = true,
            CommandKind::ResumeStrategy => (function () use ($command): void {
                unset($this->paused[$command->strategy ?? '']);
            })(),
        };
    }

    private function halt(CarbonImmutable $time): void
    {
        $this->riskState->halt();
        $this->cancelEntries($time);
    }

    private function cancelEntries(CarbonImmutable $time): void
    {
        foreach ($this->orders->cancelEntries() as $order) {
            $this->log->event(new EngineRecord($time, 'entry_cancelled', "{$order->id} of {$order->strategy}"));
        }
    }

    /** Spot first, then the perpetuals, so a carry's hedge is closed last (docs/risk-policy.md, section 5). */
    private function flatten(CarbonImmutable $time): void
    {
        $this->halt($time);

        foreach ($this->ledger->positions() as $position) {
            $this->exit($position, OrderRole::Flatten, $time);
        }

        foreach ($this->ledger->perpPositions() as $position) {
            $this->orders->exitPerpetual($position, OrderRole::Flatten, $time);
        }
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

        if (! $this->ledger->futuresWallet()->equals($this->venue->futuresWallet())) {
            throw new ReconciliationMismatch("The ledger's USD-M wallet holds {$this->ledger->futuresWallet()}; the venue's {$this->venue->futuresWallet()}.");
        }

        $positions = [];

        foreach ($this->ledger->perpPositions() as $position) {
            $positions[(string) $position->symbol] = "{$position->side->value} {$position->quantity}@{$position->entryPrice} margin {$position->margin}";
        }

        $venuePositions = array_map(fn ($p): string => "{$p->side->value} {$p->quantity}@{$p->entryPrice} margin {$p->margin}", $this->venue->positions());
        ksort($positions, SORT_STRING);
        ksort($venuePositions, SORT_STRING);

        if ($positions !== $venuePositions) {
            throw new ReconciliationMismatch(sprintf(
                'Perpetual positions differ: the ledger has [%s], the venue has [%s].',
                implode(', ', array_map(fn ($k, $v) => "{$k} {$v}", array_keys($positions), $positions)),
                implode(', ', array_map(fn ($k, $v) => "{$k} {$v}", array_keys($venuePositions), $venuePositions)),
            ));
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
            leverage: $order->role === OrderRole::Entry ? $this->orders->plan($order->strategy, $order->symbol)?->leverage : null,
        );
    }

    private function applyRiskEvents(CarbonImmutable $time, Decimal $equity): void
    {
        foreach ($this->riskState->update($time, $equity) as $event) {
            $this->log->event(new EngineRecord($time, $event->kind->value, "equity {$event->equity}"));

            // A halt stops new entries: working ones are cancelled, protection stays (docs/engine.md, section 5).
            $this->cancelEntries($time);

            if ($event->kind !== RiskEventKind::DrawdownFlatten) {
                continue;
            }

            foreach ($this->ledger->positions() as $position) {
                $this->exit($position, OrderRole::Flatten, $time);
            }

            foreach ($this->ledger->perpPositions() as $position) {
                $this->orders->exitPerpetual($position, OrderRole::Flatten, $time);
            }
        }
    }

    /** Step 6: every position keeps its protective orders on the venue; remainders too small to sell become dust. */
    /** Whether $symbol stops trading within the hour: nothing is opened in it and what is open is closed (D-92). */
    private function leaving(Symbol $symbol, CarbonImmutable $time): bool
    {
        $delisting = $this->delistings[(string) $symbol] ?? null;

        return $delisting !== null && $time >= $delisting->subMinutes(self::DELISTING_LEAD_MINUTES);
    }

    /**
     * Closes what is held in an instrument that is about to stop trading, while it still has a price: after its
     * last candle nothing could fill. An exit already working is left alone.
     */
    private function closeDelisted(CarbonImmutable $time): void
    {
        if ($this->delistings === []) {
            return;
        }

        foreach ([...$this->ledger->positions(), ...$this->ledger->perpPositions()] as $position) {
            if (! $this->leaving($position->symbol, $time)) {
                continue;
            }

            foreach ($this->book->workingFor($position->strategy, $position->symbol) as $order) {
                if (! $order->role->isProtective()) {
                    continue 2;
                }
            }

            $position instanceof PerpPosition ? $this->orders->exitPerpetual($position, OrderRole::Exit, $time) : $this->exit($position, OrderRole::Exit, $time);
            $this->log->event(new EngineRecord($time, 'delisting_close', "{$position->symbol} of {$position->strategy} stops trading at {$this->delistings[(string) $position->symbol]->format('Y-m-d H:i')}"));
        }
    }

    private function protectAll(CarbonImmutable $time): void
    {
        foreach ($this->ledger->positions() as $position) {
            $this->protect($position, $time);
        }

        foreach ($this->ledger->perpPositions() as $position) {
            $this->protectPerpetual($position, $time);
        }

        // A perpetual's stop or take-profit whose position is gone: the other leg filled, or a liquidation (D-46).
        foreach ($this->book->working() as $order) {
            if ($order->role->isProtective() && $order->symbol->isPerpetual() && $this->ledger->perpPosition($order->strategy, $order->symbol) === null) {
                $this->orders->cancelAll($order->strategy, $order->symbol);
            }
        }
    }

    private function protectPerpetual(PerpPosition $position, CarbonImmutable $time): void
    {
        $outcome = $this->orders->protectPerpetual($position, $this->rulesAt($position->symbol, $time), $time);

        if ($outcome instanceof OrderAck) {
            $this->log->event(new EngineRecord($time, 'venue_rejected', "protection of {$position->symbol} for {$position->strategy}: {$outcome->reason}"));
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
            foreach ($this->aggregators[$symbol] ?? [] as $timeframe => $aggregator) {
                $closed["{$symbol}|{$timeframe}"] = $aggregator->push($minute);
            }
        }

        // Before its own warmup a strategy is not called, so a run that reads further back for a benchmark costs it
        // nothing; its candles are still aggregated, so its periods stay aligned.
        if ($this->decideFrom !== null && $event->closedAt <= $this->decideFrom) {
            return;
        }

        foreach ($this->strategies as $strategy) {
            if ($strategy instanceof CrossSectionalStrategy) {
                $this->release($strategy, $event->closedAt);
                $this->decideTogether($strategy, $closed, $event->closedAt);

                continue;
            }

            foreach ($strategy->subscriptions() as $subscription) {
                $candle = $closed["{$subscription->symbol}|{$subscription->timeframe->value}"] ?? null;

                if ($candle !== null) {
                    $this->decide($strategy, $subscription->symbol, $candle, $event->closedAt);
                }
            }
        }
    }

    /**
     * A universe strategy decides for all its instruments at once (D-92): it is given every candle of its own that
     * closed at this moment, and its closes and stop moves are acted on first. When a close went to the venue, the
     * entries wait for the next minute, when it has filled, so the position and the risk it frees go to the best
     * ranked; a batch that closes nothing enters in the same minute. Entries keep the order the strategy gave.
     *
     * @param  array<string, ?Candle>  $closed  by "symbol|timeframe"
     */
    private function decideTogether(CrossSectionalStrategy $strategy, array $closed, CarbonImmutable $time): void
    {
        $key = $strategy->identity()->key();
        $contexts = [];
        $quiet = [];

        foreach ($closed as $candleKey => $candle) {
            $symbol = $candle === null ? null : ($this->followed[$key][$candleKey] ?? null);

            if ($candle !== null && $symbol !== null) {
                $contexts[] = $this->context($key, $symbol, $candle, $time);
                $quiet[(string) $symbol] = $symbol;
            }
        }

        if ($contexts === []) {
            return;
        }

        $managed = [];
        $entries = [];

        foreach ($strategy->onCloses($contexts) as $intent) {
            if ($intent instanceof OpenLong || $intent instanceof OpenShort || $intent instanceof OpenCarry) {
                $entries[] = $intent;
            } else {
                $managed[] = $intent;
            }

            unset($quiet[(string) $intent->symbol()]);
        }

        $entered = [];
        $records = $this->actTogether($strategy, $managed, $time, $entered);
        $closing = false;

        foreach ($records as [$intent, $record]) {
            $closing = $closing || ($intent instanceof Close && $record->approved);
        }

        if ($closing && $entries !== []) {
            $this->held[$key] = $entries;
        } else {
            $records = [...$records, ...$this->actTogether($strategy, $entries, $time, $entered)];
        }

        $this->logTogether($strategy, $records, $time);

        foreach ($quiet as $symbol) {
            $this->log->decision(new DecisionRecord(self::decisionId($key, $time), $time, $strategy->identity()->label(), $symbol, (string) $this->mark($symbol), []));
        }
    }

    /** Submits the entries a universe strategy's last batch left waiting, at the prices of this minute (D-92). */
    private function release(CrossSectionalStrategy $strategy, CarbonImmutable $time): void
    {
        $key = $strategy->identity()->key();
        $entries = $this->held[$key] ?? [];
        unset($this->held[$key]);

        if ($entries !== []) {
            $entered = [];
            $this->logTogether($strategy, $this->actTogether($strategy, $entries, $time, $entered), $time);
        }
    }

    /**
     * @param  list<Intent>  $intents
     * @param  array<string, true>  $entered
     * @return list<array{Intent, IntentRecord}>
     */
    private function actTogether(Strategy $strategy, array $intents, CarbonImmutable $time, array &$entered): array
    {
        $key = $strategy->identity()->key();
        $paused = isset($this->paused[$strategy->identity()->label()]);
        $records = [];

        foreach ($intents as $intent) {
            $records[] = [$intent, match (true) {
                $time < $this->tradingFrom => $this->record($intent, Verdict::rejected(RejectionReason::Warmup)),
                $paused => $this->record($intent, Verdict::rejected(RejectionReason::Paused)),
                default => $this->act($key, $intent, $this->mark($intent->symbol()), $time, $entered),
            }];
        }

        return $records;
    }

    /**
     * One decision record per instrument, in the order its first intent was handled.
     *
     * @param  list<array{Intent, IntentRecord}>  $records
     */
    private function logTogether(Strategy $strategy, array $records, CarbonImmutable $time): void
    {
        $bySymbol = [];

        foreach ($records as [$intent, $record]) {
            $bySymbol[(string) $intent->symbol()][] = $record;
        }

        foreach ($bySymbol as $symbol => $list) {
            $parsed = $this->symbols[$symbol] ??= Symbol::parse($symbol);
            $this->log->decision(new DecisionRecord(self::decisionId($strategy->identity()->key(), $time), $time, $strategy->identity()->label(), $parsed, (string) $this->mark($parsed), $list));
        }
    }

    private static function decisionId(string $key, CarbonImmutable $time): string
    {
        return sprintf('%s-%s', $key, base_convert((string) $time->getTimestamp(), 10, 36));
    }

    /** What a strategy is shown of $symbol when $candle closes: its position, the last funding and the data for scalping. */
    private function context(string $key, Symbol $symbol, Candle $candle, CarbonImmutable $time): StrategyContext
    {
        $plan = $this->orders->plan($key, $symbol);

        if ($symbol->isPerpetual()) {
            $perpetual = $this->ledger->perpPosition($key, $symbol);
            $view = $perpetual === null ? null : new PositionView($perpetual->quantity, $perpetual->entryPrice, $plan?->stop, $perpetual->openedAt, $perpetual->side);
        } else {
            $position = $this->ledger->position($key, $symbol);
            $view = $position === null ? null : new PositionView($position->quantity, $position->entryPrice(), $plan?->stop, $position->openedAt);
        }

        return new StrategyContext(
            $time, $symbol, $candle, $view, $this->funding[(string) $symbol] ?? null,
            $symbol->isPerpetual() ? $this->perpetuals->view((string) $symbol, $candle) : null,
        );
    }

    private function decide(Strategy $strategy, Symbol $symbol, Candle $candle, CarbonImmutable $time): void
    {
        $key = $strategy->identity()->key();
        $intents = $strategy->onCandle($this->context($key, $symbol, $candle, $time));
        $records = [];
        $entered = [];
        $paused = isset($this->paused[$strategy->identity()->label()]);

        foreach ($intents as $intent) {
            $records[] = match (true) {
                $time < $this->tradingFrom => $this->record($intent, Verdict::rejected(RejectionReason::Warmup)),
                $paused => $this->record($intent, Verdict::rejected(RejectionReason::Paused)),
                default => $this->act($key, $intent, $candle->close, $time, $entered),
            };
        }

        $this->log->decision(new DecisionRecord(
            self::decisionId($key, $time),
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

        if ($intent instanceof OpenLong || $intent instanceof OpenShort || $intent instanceof OpenCarry) {
            if (isset($entered[(string) $symbol])) {
                return $this->record($intent, Verdict::rejected(RejectionReason::DuplicateIntent));
            }

            $entered[(string) $symbol] = true;

            if ($this->leaving($symbol, $time)) {
                return $this->record($intent, Verdict::rejected(RejectionReason::Delisting));
            }

            if ($this->breakers->block((string) $symbol, $time) !== null) {
                return $this->record($intent, Verdict::rejected(RejectionReason::Breaker));
            }

            return $intent instanceof OpenCarry ? $this->openCarry($strategy, $intent, $price, $time) : $this->open($strategy, $intent, $price, $time);
        }

        $perpetual = $symbol->isPerpetual() ? $this->ledger->perpPosition($strategy, $symbol) : null;
        $position = $symbol->isPerpetual() ? null : $this->ledger->position($strategy, $symbol);

        if ($perpetual === null && $position === null) {
            return $this->record($intent, Verdict::rejected(RejectionReason::NoPosition));
        }

        if ($intent instanceof Close) {
            $ack = $perpetual !== null ? $this->orders->exitPerpetual($perpetual, OrderRole::Exit, $time) : $this->exit($position ?? throw new LogicException('No position.'), OrderRole::Exit, $time);

            return $this->record($intent, Verdict::approved(), $ack);
        }

        if (! $intent instanceof MoveStop) {
            throw new InvalidArgumentException('Unknown intent '.$intent::class.'.');
        }

        $verdict = $this->risk->assessStopMove($intent, $this->orders->plan($strategy, $symbol)?->stop, $price, $perpetual->side ?? Side::Buy);

        if ($verdict->approved) {
            $this->orders->moveStop($strategy, $symbol, $intent->stop);
            $perpetual !== null ? $this->protectPerpetual($perpetual, $time) : $this->protect($position ?? throw new LogicException('No position.'), $time);
        }

        return $this->record($intent, $verdict);
    }

    private function open(string $strategy, OpenLong|OpenShort $intent, Price $price, CarbonImmutable $time): IntentRecord
    {
        $symbol = $intent->symbol;

        if ($this->orders->hasWorkingEntry($strategy, $symbol)) {
            return $this->record($intent, Verdict::rejected(RejectionReason::EntryWorking));
        }

        $side = $intent instanceof OpenLong ? Side::Buy : Side::Sell;
        $rules = $this->rulesAt($symbol, $time);
        $limit = $intent->order === null ? null : self::limitPrice($price, $intent->order->offset, $side, $rules);
        $verdict = $this->risk->assessEntry($intent, $this->account($strategy, [$symbol]), $rules, $limit ?? $price, $time, $this->limits[$strategy] ?? null);

        if ($verdict->approved && $limit !== null && $verdict->quoteQuantity !== null) {
            // The benchmark profile sizes by quote amount; a limit order needs a quantity.
            $verdict = Verdict::quantity($verdict->quoteQuantity->dividedBy($limit->value, 18)->roundDownToMultipleOf($rules->stepSize), null, $verdict->adjustments, $verdict->leverage);
        }

        if (! $verdict->approved) {
            return $this->record($intent, $verdict);
        }

        $expiresAt = $intent->order === null ? null : $time->addSeconds($intent->order->expirySeconds);
        $ack = $this->orders->enter($strategy, $symbol, $side, $verdict, $intent->stop, $intent->target, $time, $limit, $expiresAt);

        if ($ack->accepted) {
            $day = $time->format('Y-m-d');
            $this->entries[$strategy][$day] = ($this->entries[$strategy][$day] ?? 0) + 1;
        }

        return $this->record($intent, $ack->accepted ? $verdict : Verdict::rejected(RejectionReason::VenueRejected), $ack);
    }

    /** The decision price moved by the offset, rounded to the tick so that the order never pays more than asked. */
    private static function limitPrice(Price $price, Decimal $offset, Side $side, InstrumentRules $rules): Price
    {
        $limit = $price->value->times(Decimal::of(1)->plus($offset));

        return Price::of($side === Side::Buy ? $limit->roundDownToMultipleOf($rules->tickSize) : $limit->roundUpToMultipleOf($rules->tickSize));
    }

    private function openCarry(string $strategy, OpenCarry $intent, Price $perpetualPrice, CarbonImmutable $time): IntentRecord
    {
        $spotPrice = $this->marks->priceOfSymbol($intent->spot) ?? throw new ReconciliationMismatch("No price for {$intent->spot} yet.");
        $verdict = $this->risk->assessCarry(
            $intent,
            $this->account($strategy, [$intent->spot, $intent->perpetual]),
            $this->rulesAt($intent->spot, $time),
            $this->rulesAt($intent->perpetual, $time),
            $spotPrice,
            $perpetualPrice,
            $time,
        );

        if (! $verdict->approved) {
            return $this->record($intent, $verdict);
        }

        [$spotAck, $perpetualAck] = $this->orders->enterCarry($strategy, $intent->spot, $intent->perpetual, $verdict, $time);
        $accepted = $spotAck->accepted && $perpetualAck !== null && $perpetualAck->accepted;

        if (! $accepted) {
            $this->log->event(new EngineRecord($time, 'venue_rejected', "carry of {$strategy}: ".($perpetualAck->reason ?? $spotAck->reason)));
        }

        return $this->record($intent, $accepted ? $verdict : Verdict::rejected(RejectionReason::VenueRejected), $perpetualAck ?? $spotAck);
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

    /** @param list<Symbol> $symbols the instruments of the intent; holding any of them blocks it */
    private function account(string $strategy, array $symbols): AccountView
    {
        $openRisk = Decimal::zero();
        $gross = Decimal::zero();
        $net = Decimal::zero();
        $positions = 0;
        $holds = false;
        $concerns = fn (string $owner, Symbol $symbol): bool => $owner === $strategy
            && array_filter($symbols, fn (Symbol $s): bool => $s->equals($symbol)) !== [];

        foreach ($this->ledger->positions() as $position) {
            $positions++;
            $holds = $holds || $concerns($position->strategy, $position->symbol);
            $stop = $this->orders->plan($position->strategy, $position->symbol)?->stop;
            $perUnit = $stop === null ? Decimal::zero() : $position->entryPrice()->minus($stop->value);
            $openRisk = $perUnit->isPositive() ? $openRisk->plus($perUnit->times($position->quantity)) : $openRisk;
            $notional = $position->quantity->times($this->mark($position->symbol)->value);
            $gross = $gross->plus($notional);
            $net = $net->plus($notional);
        }

        foreach ($this->ledger->perpPositions() as $position) {
            $positions++;
            $holds = $holds || $concerns($position->strategy, $position->symbol);
            $stop = $this->orders->plan($position->strategy, $position->symbol)?->stop;
            $perUnit = $stop === null ? Decimal::zero() : Perpetual::pnl($position->side, Decimal::of(1), $stop->value, $position->entryPrice);
            $openRisk = $perUnit->isPositive() ? $openRisk->plus($perUnit->times($position->quantity)) : $openRisk;
            $notional = $position->quantity->times($this->mark($position->symbol)->value);
            $gross = $gross->plus($notional);
            $net = $position->side === Side::Buy ? $net->plus($notional) : $net->minus($notional);
        }

        foreach ($this->book->working() as $order) {
            if ($order->role === OrderRole::Entry) {
                $positions++;
                $holds = $holds || $concerns($order->strategy, $order->symbol);
                $openRisk = $openRisk->plus($this->orders->plan($order->strategy, $order->symbol)->plannedRisk ?? Decimal::zero());
            }
        }

        $quote = $this->venue->balances()[(string) $this->ledger->quote] ?? null;
        $mine = count(array_filter($this->ledger->positions(), fn ($p): bool => $p->strategy === $strategy))
            + count(array_filter($this->ledger->perpPositions(), fn ($p): bool => $p->strategy === $strategy));
        $lastLoss = null;

        foreach ($this->ledger->roundTrips() as $trip) {
            if ($trip->strategy === $strategy && $trip->netProfit->isNegative() && ($lastLoss === null || $trip->closedAt > $lastLoss)) {
                $lastLoss = $trip->closedAt;
            }
        }

        $now = $this->time;

        return new AccountView(
            $this->ledger->equity($this->marks),
            $quote === null ? Decimal::zero() : $quote->free,
            $positions,
            $openRisk,
            $holds,
            $this->venue->futuresWallet(),
            $gross,
            $net,
            $mine,
            $now === null ? 0 : $this->entries[$strategy][$now->format('Y-m-d')] ?? 0,
            $lastLoss,
        );
    }

    private function record(Intent $intent, Verdict $verdict, ?OrderAck $ack = null): IntentRecord
    {
        return new IntentRecord(
            type: match (true) {
                $intent instanceof OpenLong => 'open_long',
                $intent instanceof OpenShort => 'open_short',
                $intent instanceof OpenCarry => 'open_carry',
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
        return ($symbol->isPerpetual() ? $this->marks->priceOfSymbol($symbol) : $this->marks->priceOf($symbol->base))
            ?? throw new ReconciliationMismatch("No price for {$symbol} yet.");
    }
}
