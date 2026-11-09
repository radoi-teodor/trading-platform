<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Candles partitioned by month on open_time (D-90): 50 perpetuals over two years are about 52 million rows, past the
 * 50 million at which D-31 promised to revisit the storage. Same columns, primary key, check and foreign key; a
 * partition per month from 2017 (Binance's oldest archives) to 2035, and a default partition so an insert never
 * fails. The old table stays as candles_unpartitioned until a later migration drops it.
 */
return new class extends Migration
{
    private const string FIRST_MONTH = '2017-01-01';

    private const string LAST_MONTH = '2035-12-01';

    public function up(): void
    {
        DB::statement('ALTER TABLE candles RENAME TO candles_unpartitioned');
        DB::statement('ALTER TABLE candles_unpartitioned RENAME CONSTRAINT candles_pkey TO candles_unpartitioned_pkey');
        DB::statement('ALTER TABLE candles_unpartitioned RENAME CONSTRAINT candles_consistent TO candles_unpartitioned_consistent');
        DB::statement('ALTER TABLE candles_unpartitioned RENAME CONSTRAINT candles_instrument_id_foreign TO candles_unpartitioned_instrument_id_foreign');

        DB::statement(<<<'SQL'
            CREATE TABLE candles (
                instrument_id bigint NOT NULL,
                timeframe varchar(4) NOT NULL,
                open_time timestamp(0) with time zone NOT NULL,
                open numeric(36,18) NOT NULL,
                high numeric(36,18) NOT NULL,
                low numeric(36,18) NOT NULL,
                close numeric(36,18) NOT NULL,
                volume numeric(36,18) NOT NULL,
                quote_volume numeric(36,18) NOT NULL,
                trade_count bigint NOT NULL,
                taker_buy_volume numeric(36,18) NOT NULL,
                source smallint NOT NULL,
                received_at timestamp(0) with time zone NOT NULL,
                CONSTRAINT candles_pkey PRIMARY KEY (instrument_id, timeframe, open_time),
                CONSTRAINT candles_instrument_id_foreign FOREIGN KEY (instrument_id) REFERENCES instruments(id) ON DELETE CASCADE,
                CONSTRAINT candles_consistent CHECK (
                    low > 0
                    AND low <= open AND low <= close AND low <= high
                    AND high >= open AND high >= close
                    AND volume >= 0 AND quote_volume >= 0 AND trade_count >= 0
                    AND taker_buy_volume >= 0 AND taker_buy_volume <= volume
                )
            ) PARTITION BY RANGE (open_time)
            SQL);

        for ($month = CarbonImmutable::parse(self::FIRST_MONTH, 'UTC'); $month->lessThanOrEqualTo(CarbonImmutable::parse(self::LAST_MONTH, 'UTC')); $month = $month->addMonth()) {
            DB::statement(sprintf(
                "CREATE TABLE candles_%s PARTITION OF candles FOR VALUES FROM ('%s+00') TO ('%s+00')",
                $month->format('Y_m'),
                $month->format('Y-m-d H:i:s'),
                $month->addMonth()->format('Y-m-d H:i:s'),
            ));
        }

        DB::statement('CREATE TABLE candles_default PARTITION OF candles DEFAULT');
        DB::statement('INSERT INTO candles SELECT * FROM candles_unpartitioned');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE candles');
        DB::statement('ALTER TABLE candles_unpartitioned RENAME TO candles');
        DB::statement('ALTER TABLE candles RENAME CONSTRAINT candles_unpartitioned_pkey TO candles_pkey');
        DB::statement('ALTER TABLE candles RENAME CONSTRAINT candles_unpartitioned_consistent TO candles_consistent');
        DB::statement('ALTER TABLE candles RENAME CONSTRAINT candles_unpartitioned_instrument_id_foreign TO candles_instrument_id_foreign');
    }
};
