<?php

declare(strict_types=1);

namespace App\Market\Stream;

use App\Market\Binance\EpochTime;
use App\Market\Binance\Json;
use App\Market\Data\Candle;
use App\Support\Timeframe;
use JsonException;
use UnexpectedValueException;

/** A kline event from a Binance combined stream (spot and USD-M use the same layout). */
final readonly class KlineMessage
{
    private function __construct(public string $venueSymbol, public bool $closed, public Candle $candle) {}

    /** Null for any message that is not a kline; an exception for a kline that cannot be read. */
    public static function parse(string $payload): ?self
    {
        try {
            $message = json_decode($payload, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $invalid) {
            throw new UnexpectedValueException("Stream message is not JSON: {$invalid->getMessage()}", previous: $invalid);
        }

        $data = is_array($message) ? ($message['data'] ?? null) : null;

        if (! is_array($data) || ($data['e'] ?? null) !== 'kline') {
            return null;
        }

        $kline = Json::arrayAt($data, 'k');
        $closed = $kline['x'] ?? null;

        if (! is_bool($closed)) {
            throw new UnexpectedValueException('Stream kline without its closed flag.');
        }

        return new self(
            Json::string($data, 's'),
            $closed,
            Candle::fromStrings(
                EpochTime::parse(Json::int($kline, 't')),
                Timeframe::from(Json::string($kline, 'i')),
                Json::number($kline, 'o'),
                Json::number($kline, 'h'),
                Json::number($kline, 'l'),
                Json::number($kline, 'c'),
                Json::number($kline, 'v'),
                Json::number($kline, 'q'),
                Json::int($kline, 'n'),
                Json::number($kline, 'V'),
            ),
        );
    }
}
