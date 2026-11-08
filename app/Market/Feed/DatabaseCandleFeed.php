<?php

declare(strict_types=1);

namespace App\Market\Feed;

use App\Market\CandleRepository;
use App\Market\Contracts\CandleFeed;
use App\Market\Data\MarketType;
use App\Market\Data\MinuteCandles;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;

/** Reads stored 1-minute candles chunk by chunk and hands them out minute by minute (docs/engine.md, section 1). */
final readonly class DatabaseCandleFeed implements CandleFeed
{
    public function __construct(
        private ConnectionInterface $db,
        private string $exchange = 'binance',
        private int $chunkMinutes = 1440,
    ) {}

    public function minutes(MarketType $market, array $symbols, CarbonImmutable $from, CarbonImmutable $to): iterable
    {
        $symbolsById = $this->instrumentIds($market, $symbols);

        for ($chunk = $from; $chunk < $to; $chunk = $chunk->addMinutes($this->chunkMinutes)) {
            $end = $chunk->addMinutes($this->chunkMinutes)->min($to);
            $minutes = [];

            foreach ($this->rows(array_keys($symbolsById), $chunk, $end) as $row) {
                $candle = CandleRepository::candleFromRow($row);
                $minutes[$candle->openTime->getTimestamp()][$symbolsById[Rows::int($row, 'instrument_id')]] = $candle;
            }

            ksort($minutes);

            foreach ($minutes as $candles) {
                ksort($candles, SORT_STRING);
                $first = reset($candles);

                yield new MinuteCandles($first->endsAt(), $candles);
            }
        }
    }

    /**
     * @param  list<Symbol>  $symbols
     * @return array<int, string> unified symbol by instrument id
     */
    private function instrumentIds(MarketType $market, array $symbols): array
    {
        $ids = [];

        foreach ($symbols as $symbol) {
            $instrument = Instrument::query()
                ->where('exchange', $this->exchange)
                ->where('market', $market->value)
                ->where('venue_symbol', $symbol->venueSymbol())
                ->first() ?? throw new InvalidArgumentException("Unknown instrument {$symbol} on {$this->exchange} {$market->value}.");

            $ids[$instrument->id] = (string) $symbol;
        }

        return $ids;
    }

    /**
     * @param  list<int>  $instrumentIds
     * @return iterable<object>
     */
    private function rows(array $instrumentIds, CarbonImmutable $from, CarbonImmutable $to): iterable
    {
        return $this->db->table('candles')
            ->whereIn('instrument_id', $instrumentIds)
            ->where('timeframe', Timeframe::OneMinute->value)
            ->where('open_time', '>=', Rows::timestamp($from))
            ->where('open_time', '<', Rows::timestamp($to))
            ->orderBy('open_time')
            ->get();
    }
}
