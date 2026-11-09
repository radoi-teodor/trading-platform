<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Market\Data\Candle;
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
            $this->positioning[$symbol]->longShortRatio ?? null, $this->positioning[$symbol]->topTraderRatio ?? null, $this->positioning[$symbol]->takerRatio ?? null);
    }
}
