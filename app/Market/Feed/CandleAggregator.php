<?php

declare(strict_types=1);

namespace App\Market\Feed;

use App\Market\Data\Candle;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;

/**
 * Builds candles of one timeframe from consecutive 1-minute candles (D-36). A candle is emitted when the last
 * minute of its period arrives, never earlier and never partial: a missing minute or a start inside a period is an
 * error, because a strategy deciding on an incomplete candle would decide on something the exchange never showed.
 */
final class CandleAggregator
{
    private ?CarbonImmutable $periodOpen = null;

    /** Timestamps, compared as integers on every minute. */
    private ?int $periodEnd = null;

    private ?int $expected = null;

    private ?Price $open = null;

    private ?Price $high = null;

    private ?Price $low = null;

    private Decimal $volume;

    private Decimal $quoteVolume;

    private Decimal $takerBuyVolume;

    private int $tradeCount = 0;

    /**
     * @param  bool  $restarts  for an instrument of a universe (D-92), whose candles come and go with its membership
     *                          and its lifetime: a missing minute drops the period it broke, and a start inside a
     *                          period waits for the next one to open. A partial candle is still never emitted.
     */
    public function __construct(public readonly Timeframe $timeframe, private readonly bool $restarts = false)
    {
        $this->volume = $this->quoteVolume = $this->takerBuyVolume = Decimal::zero();
    }

    /** Takes the next closed 1-minute candle; returns the candle of the timeframe when this minute completes it. */
    public function push(Candle $minute): ?Candle
    {
        if ($minute->timeframe !== Timeframe::OneMinute) {
            throw new CandleSequenceBroken("Only 1-minute candles can be aggregated, got {$minute->timeframe->value}.");
        }

        if ($this->timeframe === Timeframe::OneMinute) {
            return $minute;
        }

        $this->accept($minute);

        if ($minute->openTime->getTimestamp() + 60 !== $this->periodEnd) {
            return null;
        }

        $candle = new Candle(
            $this->periodOpen(),
            $this->timeframe,
            $this->open ?? $minute->open,
            $this->high ?? $minute->high,
            $this->low ?? $minute->low,
            $minute->close,
            $this->volume,
            $this->quoteVolume,
            $this->tradeCount,
            $this->takerBuyVolume,
        );
        $this->periodOpen = null;
        $this->periodEnd = null;

        return $candle;
    }

    private function accept(Candle $minute): void
    {
        $opens = $minute->openTime->getTimestamp();

        if ($this->expected !== null && $opens !== $this->expected) {
            if (! $this->restarts || $opens < $this->expected) {
                throw new CandleSequenceBroken(sprintf(
                    'Expected the minute opening at %s, got %s.',
                    CarbonImmutable::createFromTimestampUTC($this->expected)->format('Y-m-d H:i'),
                    $minute->openTime->format('Y-m-d H:i'),
                ));
            }

            $this->periodOpen = null;
            $this->periodEnd = null;
        }

        $this->expected = $opens + 60;

        if ($this->periodOpen === null) {
            $this->start($minute);

            return;
        }

        if ($this->high === null || $minute->high->value->isGreaterThan($this->high->value)) {
            $this->high = $minute->high;
        }

        if ($this->low === null || $minute->low->value->isLessThan($this->low->value)) {
            $this->low = $minute->low;
        }

        $this->volume = $this->volume->plus($minute->volume);
        $this->quoteVolume = $this->quoteVolume->plus($minute->quoteVolume);
        $this->takerBuyVolume = $this->takerBuyVolume->plus($minute->takerBuyVolume);
        $this->tradeCount += $minute->tradeCount;
    }

    private function start(Candle $minute): void
    {
        if (! $this->timeframe->isOpenTime($minute->openTime->getTimestamp())) {
            if ($this->restarts) {
                return;
            }

            $periodOpen = $this->timeframe->openTimeContaining($minute->openTime);

            throw new CandleSequenceBroken(sprintf(
                'The %s candle opening at %s starts inside the minutes: the first minute given opens at %s.',
                $this->timeframe->value,
                $periodOpen->format('Y-m-d H:i'),
                $minute->openTime->format('Y-m-d H:i'),
            ));
        }

        $this->periodOpen = $minute->openTime;
        $this->periodEnd = $minute->openTime->getTimestamp() + $this->timeframe->seconds();
        $this->open = $minute->open;
        $this->high = $minute->high;
        $this->low = $minute->low;
        $this->volume = $minute->volume;
        $this->quoteVolume = $minute->quoteVolume;
        $this->takerBuyVolume = $minute->takerBuyVolume;
        $this->tradeCount = $minute->tradeCount;
    }

    private function periodOpen(): CarbonImmutable
    {
        return $this->periodOpen ?? throw new CandleSequenceBroken('No period is open.');
    }
}
