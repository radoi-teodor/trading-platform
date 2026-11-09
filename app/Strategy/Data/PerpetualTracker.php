<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Market\Data\BookDepth;
use App\Market\Data\Candle;
use App\Market\Data\Liquidations;
use App\Market\Data\MinuteCandles;
use App\Market\Data\OpenInterest;
use App\Market\Data\Positioning;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Support\Number\Decimal;

/**
 * Follows the data for scalping of each perpetual minute by minute and says what a candle that just closed sees
 * (D-79). The engine and the chart both use it, so a backtest, paper and the chart read the same values (D-8).
 */
final class PerpetualTracker
{
    /** Minutes of trade flow kept: a day, the longest candle a strategy reads. */
    private const int FLOW_MINUTES = 1440;

    /** @var array<string, PriceMinute> */
    private array $marks = [];

    /** @var array<string, PriceMinute> */
    private array $premium = [];

    /** @var array<string, OpenInterest> */
    private array $openInterest = [];

    /** @var array<string, array<int, Liquidations>> by symbol, then by minute open timestamp */
    private array $liquidations = [];

    /** @var array<string, BookDepth> */
    private array $depth = [];

    /** @var array<string, Positioning> */
    private array $positioning = [];

    /** @var array<string, array<int, TradeFlow>> by symbol, then by minute open timestamp */
    private array $flow = [];

    public function absorb(MinuteCandles $minute): void
    {
        foreach ($minute->marks as $symbol => $mark) {
            $this->marks[$symbol] = $mark;
        }

        foreach ($minute->premium as $symbol => $premium) {
            $this->premium[$symbol] = $premium;
        }

        foreach ($minute->openInterest as $symbol => $interest) {
            $this->openInterest[$symbol] = $interest;
        }

        foreach ($minute->liquidations as $symbol => $liquidations) {
            $this->liquidations[$symbol][$liquidations->openTime->getTimestamp()] = $liquidations;

            if (count($this->liquidations[$symbol]) > self::FLOW_MINUTES) {
                $this->liquidations[$symbol] = array_slice($this->liquidations[$symbol], -self::FLOW_MINUTES, preserve_keys: true);
            }
        }

        foreach ($minute->depth as $symbol => $depth) {
            $this->depth[$symbol] = $depth;
        }

        foreach ($minute->positioning as $symbol => $positioning) {
            $this->positioning[$symbol] = $positioning;
        }

        foreach ($minute->flow as $symbol => $flow) {
            $this->flow[$symbol][$flow->openTime->getTimestamp()] = $flow;

            if (count($this->flow[$symbol]) > self::FLOW_MINUTES) {
                $this->flow[$symbol] = array_slice($this->flow[$symbol], -self::FLOW_MINUTES, preserve_keys: true);
            }
        }
    }

    /**
     * The long and short notional liquidated over the minutes from $from to $to (timestamps, $to excluded): null
     * when a minute was not heard, since none and unknown differ (D-91).
     *
     * @return array{?Decimal, ?Decimal}
     */
    private function liquidated(string $symbol, int $from, int $to): array
    {
        $long = $short = Decimal::zero();

        for ($at = $from; $at < $to; $at += 60) {
            $minute = $this->liquidations[$symbol][$at] ?? null;

            if ($minute === null) {
                return [null, null];
            }

            $long = $long->plus($minute->longNotional);
            $short = $short->plus($minute->shortNotional);
        }

        return [$long, $short];
    }

    /** What a candle of $symbol that just closed sees; the flow is summed over the candle's own minutes. */
    public function view(string $symbol, Candle $candle): PerpetualView
    {
        [$from, $to] = [$candle->openTime->getTimestamp(), $candle->endsAt()->getTimestamp()];
        $buy = $sell = null;

        foreach ($this->flow[$symbol] ?? [] as $at => $flow) {
            if ($at >= $from && $at < $to) {
                $buy = ($buy ?? Decimal::zero())->plus($flow->largeBuyVolume);
                $sell = ($sell ?? Decimal::zero())->plus($flow->largeSellVolume);
            }
        }

        return new PerpetualView($this->marks[$symbol]->close ?? null, $this->premium[$symbol]->close ?? null, $this->openInterest[$symbol]->contracts ?? null, $buy, $sell,
            $this->positioning[$symbol]->longShortRatio ?? null, $this->positioning[$symbol]->topTraderRatio ?? null, $this->positioning[$symbol]->takerRatio ?? null, $this->depth[$symbol] ?? null, ...$this->liquidated($symbol, $from, $to));
    }
}
