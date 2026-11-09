<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * Reduces aggregated trades, in time order, to one TradeFlow a minute (D-79). The archive, the REST repair and the
 * live stream all use it, so a minute is reduced the same way whatever its source.
 */
final class TradeFlowBuilder
{
    private ?int $minute = null;

    /** @var array{buy: Decimal, sell: Decimal, buys: int, sells: int, largest: Decimal} */
    private array $sums;

    /** @var list<TradeFlow> */
    private array $finished = [];

    public function __construct(private readonly Decimal $threshold)
    {
        $this->sums = self::empty();
    }

    /** One trade: its time in milliseconds, price, base quantity, and whether the buyer was the maker (the aggressor sold). */
    public function add(int $milliseconds, Decimal $price, Decimal $quantity, bool $buyerMaker): void
    {
        $minute = intdiv($milliseconds, 60_000) * 60;

        if ($minute !== $this->minute) {
            $this->close();
            $this->minute = $minute;
        }

        $notional = $price->times($quantity);
        $this->sums['largest'] = $notional->isGreaterThan($this->sums['largest']) ? $notional : $this->sums['largest'];

        if ($notional->isLessThan($this->threshold)) {
            return;
        }

        if ($buyerMaker) {
            $this->sums['sell'] = $this->sums['sell']->plus($quantity);
            $this->sums['sells']++;
        } else {
            $this->sums['buy'] = $this->sums['buy']->plus($quantity);
            $this->sums['buys']++;
        }
    }

    /**
     * The minutes complete before $before (a timestamp in seconds), taken out of the builder.
     *
     * @return list<TradeFlow>
     */
    public function takeBefore(int $before): array
    {
        if ($this->minute !== null && $this->minute + 60 <= $before) {
            $this->close();
        }

        $taken = $this->finished;
        $this->finished = [];

        return $taken;
    }

    /** @return list<TradeFlow> every minute seen, the last one included */
    public function finish(): array
    {
        $this->close();

        return $this->takeBefore(PHP_INT_MAX);
    }

    private function close(): void
    {
        if ($this->minute !== null) {
            $s = $this->sums;
            $this->finished[] = new TradeFlow(CarbonImmutable::createFromTimestampUTC($this->minute), $s['buy'], $s['sell'], $s['buys'], $s['sells'], $s['largest'], $this->threshold);
        }

        $this->minute = null;
        $this->sums = self::empty();
    }

    /** @return array{buy: Decimal, sell: Decimal, buys: int, sells: int, largest: Decimal} */
    private static function empty(): array
    {
        return ['buy' => Decimal::zero(), 'sell' => Decimal::zero(), 'buys' => 0, 'sells' => 0, 'largest' => Decimal::zero()];
    }
}
