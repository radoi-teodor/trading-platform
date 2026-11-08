<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Contracts\CandleFeed;
use App\Market\Data\Candle;
use App\Market\Data\MarketType;
use App\Market\Data\MinuteCandles;
use Carbon\CarbonImmutable;

/** Candles held in memory, handed out minute by minute like the database feed. */
final readonly class InMemoryCandleFeed implements CandleFeed
{
    /** @param array<string, list<Candle>> $candles 1-minute candles by unified symbol */
    public function __construct(private array $candles) {}

    public function minutes(MarketType $market, array $symbols, CarbonImmutable $from, CarbonImmutable $to): iterable
    {
        $minutes = [];

        foreach ($symbols as $symbol) {
            foreach ($this->candles[(string) $symbol] ?? [] as $candle) {
                if ($candle->openTime >= $from && $candle->openTime < $to) {
                    $minutes[$candle->openTime->getTimestamp()][(string) $symbol] = $candle;
                }
            }
        }

        ksort($minutes);

        foreach ($minutes as $candles) {
            ksort($candles, SORT_STRING);
            yield new MinuteCandles(reset($candles)->endsAt(), $candles);
        }
    }
}
