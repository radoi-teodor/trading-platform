<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\Candle;
use App\Market\Data\FormingCandle;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/**
 * The minute still forming of each streamed instrument, one row each, for the live chart (D-87). Strategies, the
 * engine and backtests never read it (an architecture test says so): they decide on closed candles (D-67).
 */
final readonly class FormingCandles
{
    public function __construct(private ConnectionInterface $db) {}

    public function put(Instrument $instrument, Candle $candle, CarbonImmutable $receivedAt): void
    {
        $this->db->table('forming_candles')->upsert([[
            'instrument_id' => $instrument->id,
            'open_time' => Rows::timestamp($candle->openTime),
            'open' => (string) $candle->open,
            'high' => (string) $candle->high,
            'low' => (string) $candle->low,
            'close' => (string) $candle->close,
            'volume' => (string) $candle->volume,
            'quote_volume' => (string) $candle->quoteVolume,
            'trade_count' => $candle->tradeCount,
            'taker_buy_volume' => (string) $candle->takerBuyVolume,
            'received_at' => Rows::timestamp($receivedAt),
        ]], ['instrument_id'], ['open_time', 'open', 'high', 'low', 'close', 'volume', 'quote_volume', 'trade_count', 'taker_buy_volume', 'received_at']);
    }

    public function latest(Instrument $instrument): ?FormingCandle
    {
        $row = $this->db->table('forming_candles')->where('instrument_id', $instrument->id)->first();

        if ($row === null) {
            return null;
        }

        return new FormingCandle(
            Candle::fromStrings(
                Rows::time($row, 'open_time'),
                Timeframe::OneMinute,
                Rows::string($row, 'open'),
                Rows::string($row, 'high'),
                Rows::string($row, 'low'),
                Rows::string($row, 'close'),
                Rows::string($row, 'volume'),
                Rows::string($row, 'quote_volume'),
                Rows::int($row, 'trade_count'),
                Rows::string($row, 'taker_buy_volume'),
            ),
            Rows::time($row, 'received_at'),
        );
    }
}
