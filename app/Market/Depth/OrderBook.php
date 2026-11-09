<?php

declare(strict_types=1);

namespace App\Market\Depth;

use App\Market\Data\BookDepth;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * A local copy of a USD-M order book (D-91), kept as Binance documents it: a REST snapshot, then the stream's diffs;
 * diffs older than the snapshot are dropped, the first kept one must straddle it, and each next one must follow the
 * previous (pu equals the previous u). Anything else throws OrderBookOutOfSync, and the caller starts again.
 *
 * Levels deeper than the snapshot's 1,000 appear as they change, so the 5 % band can read low for a while after a
 * new snapshot.
 */
final class OrderBook
{
    /** @var array<string, Decimal> quantity by price */
    private array $bids = [];

    /** @var array<string, Decimal> quantity by price */
    private array $asks = [];

    private ?int $last = null;

    private function __construct(private readonly int $snapshotId) {}

    /**
     * @param  list<array{Decimal, Decimal}>  $bids
     * @param  list<array{Decimal, Decimal}>  $asks
     */
    public static function fromSnapshot(int $lastUpdateId, array $bids, array $asks): self
    {
        $book = new self($lastUpdateId);
        $book->set($book->bids, $bids);
        $book->set($book->asks, $asks);

        return $book;
    }

    public function apply(DepthUpdate $update): void
    {
        if ($this->last === null) {
            if ($update->last < $this->snapshotId) {
                return;
            }

            if ($update->first > $this->snapshotId) {
                throw new OrderBookOutOfSync("{$update->venueSymbol}: snapshot {$this->snapshotId} is older than the first update, {$update->first}.");
            }
        } elseif ($update->previous !== $this->last) {
            throw new OrderBookOutOfSync("{$update->venueSymbol}: update {$update->first} to {$update->last} follows {$update->previous}, not {$this->last}.");
        }

        $this->set($this->bids, $update->bids);
        $this->set($this->asks, $update->asks);
        $this->last = $update->last;
    }

    /** The last update applied, null before the first. */
    public function lastUpdate(): ?int
    {
        return $this->last;
    }

    /**
     * The quote notional resting within 1, 2 and 5 % of the mid price on each side, as the bookDepth archives give
     * it; null while one side is empty.
     */
    public function depth(CarbonImmutable $time): ?BookDepth
    {
        if ($this->bids === [] || $this->asks === []) {
            return null;
        }

        $bestBid = $this->best($this->bids, true);
        $bestAsk = $this->best($this->asks, false);
        $mid = $bestBid->plus($bestAsk)->dividedBy(Decimal::of('2'), 18);

        return new BookDepth(
            $time,
            self::within($this->bids, true, 1, $mid), self::within($this->bids, true, 2, $mid), self::within($this->bids, true, 5, $mid),
            self::within($this->asks, false, 1, $mid), self::within($this->asks, false, 2, $mid), self::within($this->asks, false, 5, $mid),
        );
    }

    /**
     * @param  array<string, Decimal>  $side
     * @param  list<array{Decimal, Decimal}>  $levels
     */
    private function set(array &$side, array $levels): void
    {
        foreach ($levels as [$price, $quantity]) {
            if ($quantity->isPositive()) {
                $side[(string) $price] = $quantity;
            } else {
                unset($side[(string) $price]);
            }
        }
    }

    /**
     * The quote notional of a side's levels within $percent % of the mid price.
     *
     * @param  array<string, Decimal>  $levels  quantity by price
     */
    private static function within(array $levels, bool $bids, int $percent, Decimal $mid): Decimal
    {
        $fraction = Decimal::of((string) $percent)->dividedBy(Decimal::of('100'), 18);
        $limit = $bids ? $mid->times(Decimal::of('1')->minus($fraction)) : $mid->times(Decimal::of('1')->plus($fraction));
        $total = Decimal::zero();

        foreach ($levels as $price => $quantity) {
            $p = Decimal::of((string) $price);

            if ($bids ? ! $p->isLessThan($limit) : ! $p->isGreaterThan($limit)) {
                $total = $total->plus($p->times($quantity));
            }
        }

        return $total;
    }

    /** @param array<string, Decimal> $side */
    private function best(array $side, bool $highest): Decimal
    {
        $best = null;

        foreach (array_keys($side) as $price) {
            $p = Decimal::of((string) $price);
            $best = $best === null || ($highest ? $p->isGreaterThan($best) : $p->isLessThan($best)) ? $p : $best;
        }

        return $best ?? Decimal::zero();
    }
}
