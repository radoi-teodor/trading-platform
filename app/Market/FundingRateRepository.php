<?php

declare(strict_types=1);

namespace App\Market;

use App\Market\Data\CandleSource;
use App\Market\Data\FundingRate;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/** Stores and reads funding rates of perpetuals, with the same source ranking as candles. */
final readonly class FundingRateRepository
{
    public function __construct(private ConnectionInterface $db, private Clock $clock) {}

    /** @param iterable<FundingRate> $rates */
    public function upsert(Instrument $instrument, CandleSource $source, iterable $rates): int
    {
        $receivedAt = Rows::timestamp($this->clock->now());
        $placeholders = [];
        $bindings = [];

        foreach ($rates as $rate) {
            $placeholders[] = '(?, ?, ?, ?, ?, ?, ?)';
            array_push(
                $bindings,
                $instrument->id,
                Rows::timestamp($rate->fundingTime),
                (string) $rate->rate,
                $rate->intervalHours,
                $rate->markPrice === null ? null : (string) $rate->markPrice,
                $source->value,
                $receivedAt,
            );
        }

        if ($placeholders === []) {
            return 0;
        }

        $this->db->statement(
            'INSERT INTO funding_rates (instrument_id, funding_time, rate, interval_hours, mark_price, source, received_at)
            VALUES '.implode(', ', $placeholders).'
            ON CONFLICT (instrument_id, funding_time) DO UPDATE SET
                rate = EXCLUDED.rate, interval_hours = EXCLUDED.interval_hours, mark_price = EXCLUDED.mark_price,
                source = EXCLUDED.source, received_at = EXCLUDED.received_at
            WHERE funding_rates.source < EXCLUDED.source',
            $bindings,
        );

        return count($placeholders);
    }

    /** @return list<FundingRate> rates with $from <= funding time <= $to, oldest first */
    public function range(Instrument $instrument, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = $this->db->table('funding_rates')
            ->where('instrument_id', $instrument->id)
            ->whereBetween('funding_time', [Rows::timestamp($from), Rows::timestamp($to)])
            ->orderBy('funding_time')
            ->get();

        return array_values($rows->map(function (object $row): FundingRate {
            $markPrice = Rows::nullableString($row, 'mark_price');

            return new FundingRate(
                Rows::time($row, 'funding_time'),
                Decimal::of(Rows::string($row, 'rate')),
                Rows::int($row, 'interval_hours'),
                $markPrice === null ? null : Price::of($markPrice),
            );
        })->all());
    }
}
