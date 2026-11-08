<?php

declare(strict_types=1);

namespace App\Market\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/** One OHLCV candle. Refuses values the exchange could not have produced. */
final readonly class Candle
{
    public function __construct(
        public CarbonImmutable $openTime,
        public Timeframe $timeframe,
        public Price $open,
        public Price $high,
        public Price $low,
        public Price $close,
        public Decimal $volume,
        public Decimal $quoteVolume,
        public int $tradeCount,
        public Decimal $takerBuyVolume,
    ) {
        $this->assertAligned();
        $this->assertPricesConsistent();
        $this->assertVolumesConsistent();
    }

    public static function fromStrings(
        CarbonImmutable $openTime,
        Timeframe $timeframe,
        string $open,
        string $high,
        string $low,
        string $close,
        string $volume,
        string $quoteVolume,
        int $tradeCount,
        string $takerBuyVolume,
    ): self {
        return new self(
            $openTime->utc(),
            $timeframe,
            Price::of($open),
            Price::of($high),
            Price::of($low),
            Price::of($close),
            Decimal::of($volume),
            Decimal::of($quoteVolume),
            $tradeCount,
            Decimal::of($takerBuyVolume),
        );
    }

    /** The moment the candle is complete and known: its open time plus its timeframe (docs/market-data.md, section 2). */
    public function endsAt(): CarbonImmutable
    {
        return $this->timeframe->nextOpen($this->openTime);
    }

    /** Binance's close time: one millisecond before endsAt(). */
    public function closesAt(): CarbonImmutable
    {
        return $this->timeframe->closeTime($this->openTime);
    }

    public function sameValuesAs(self $other): bool
    {
        return $this->open->value->equals($other->open->value)
            && $this->high->value->equals($other->high->value)
            && $this->low->value->equals($other->low->value)
            && $this->close->value->equals($other->close->value)
            && $this->volume->equals($other->volume)
            && $this->quoteVolume->equals($other->quoteVolume)
            && $this->tradeCount === $other->tradeCount
            && $this->takerBuyVolume->equals($other->takerBuyVolume);
    }

    private function assertAligned(): void
    {
        try {
            $this->timeframe->nextOpen($this->openTime);
        } catch (InvalidArgumentException $notAligned) {
            throw new InvalidCandle($notAligned->getMessage(), previous: $notAligned);
        }
    }

    private function assertPricesConsistent(): void
    {
        foreach ([$this->open, $this->close] as $price) {
            if ($price->value->isLessThan($this->low->value) || $price->value->isGreaterThan($this->high->value)) {
                throw new InvalidCandle("Candle at {$this->openTime->toIso8601String()}: open and close must lie between low {$this->low} and high {$this->high}.");
            }
        }
    }

    private function assertVolumesConsistent(): void
    {
        if ($this->volume->isNegative() || $this->quoteVolume->isNegative() || $this->tradeCount < 0) {
            throw new InvalidCandle("Candle at {$this->openTime->toIso8601String()}: volumes and trade count cannot be negative.");
        }

        if ($this->takerBuyVolume->isNegative() || $this->takerBuyVolume->isGreaterThan($this->volume)) {
            throw new InvalidCandle("Candle at {$this->openTime->toIso8601String()}: taker buy volume must lie between 0 and the volume.");
        }
    }
}
