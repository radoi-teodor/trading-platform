<?php

declare(strict_types=1);

namespace App\Market\Feed;

use App\Market\CandleRepository;
use App\Market\Contracts\CandleFeed;
use App\Market\Data\FundingRate;
use App\Market\Data\MarketType;
use App\Market\Data\MinuteCandles;
use App\Market\Data\OpenInterest;
use App\Market\Data\PriceMinute;
use App\Market\Data\TradeFlow;
use App\Market\Models\Instrument;
use App\Market\Persistence\Rows;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
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

    public function minutes(array $symbols, CarbonImmutable $from, CarbonImmutable $to): iterable
    {
        $symbolsById = $this->instrumentIds($symbols);

        for ($chunk = $from; $chunk < $to; $chunk = $chunk->addMinutes($this->chunkMinutes)) {
            $end = $chunk->addMinutes($this->chunkMinutes)->min($to);
            $minutes = [];

            foreach ($this->rows(array_keys($symbolsById), $chunk, $end) as $row) {
                $candle = CandleRepository::candleFromRow($row);
                $minutes[$candle->openTime->getTimestamp()][$symbolsById[Rows::int($row, 'instrument_id')]] = $candle;
            }

            ksort($minutes);
            $funding = $this->funding($symbolsById, $chunk, $end);
            $perpetuals = array_filter($symbolsById, fn (string $symbol): bool => str_contains($symbol, ':'));
            [$flow, $marks, $premium, $interest] = $perpetuals === [] ? [[], [], [], []] : $this->scalping($perpetuals, $chunk, $end);

            foreach ($minutes as $candles) {
                ksort($candles, SORT_STRING);
                $first = reset($candles);
                $closedAt = $first->endsAt();
                $at = $closedAt->getTimestamp();

                yield new MinuteCandles($closedAt, $candles, $funding[$at] ?? [], $flow[$at] ?? [], $marks[$at] ?? [], $premium[$at] ?? [], $interest[$at] ?? []);
            }
        }
    }

    /**
     * Funding rates of the perpetuals whose time falls in the minutes that close in ($from, $to].
     *
     * @param  array<int, string>  $symbolsById
     * @return array<int, array<string, FundingRate>> by funding timestamp, then by symbol in symbol order
     */
    private function funding(array $symbolsById, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = $this->db->table('funding_rates')
            ->whereIn('instrument_id', array_keys($symbolsById))
            ->where('funding_time', '>', Rows::timestamp($from))
            ->where('funding_time', '<=', Rows::timestamp($to))
            ->get();
        $funding = [];

        foreach ($rows as $row) {
            $markPrice = Rows::nullableString($row, 'mark_price');
            $rate = new FundingRate(
                Rows::time($row, 'funding_time'),
                Decimal::of(Rows::string($row, 'rate')),
                Rows::int($row, 'interval_hours'),
                $markPrice === null ? null : Price::of($markPrice),
            );
            $funding[$rate->fundingTime->getTimestamp()][$symbolsById[Rows::int($row, 'instrument_id')]] = $rate;
        }

        foreach ($funding as &$rates) {
            ksort($rates, SORT_STRING);
        }

        return $funding;
    }

    /**
     * The scalping data of the minutes closing in ($from, $to] (D-79), each keyed by the close of the minute it is
     * known at: a minute's flow, mark and premium at its own close, open interest five minutes after its time.
     *
     * @param  array<int, string>  $perpetuals  unified symbol by instrument id
     * @return array{array<int, array<string, TradeFlow>>, array<int, array<string, PriceMinute>>, array<int, array<string, PriceMinute>>, array<int, array<string, OpenInterest>>}
     */
    private function scalping(array $perpetuals, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $ids = array_keys($perpetuals);
        $minutes = fn (string $table) => $this->db->table($table)->whereIn('instrument_id', $ids)
            ->where('open_time', '>=', Rows::timestamp($from))->where('open_time', '<', Rows::timestamp($to))->get();
        [$flow, $marks, $premium, $interest] = [[], [], [], []];

        foreach ($minutes('trade_flow') as $row) {
            $f = new TradeFlow(
                Rows::time($row, 'open_time'), Decimal::of(Rows::string($row, 'large_buy_volume')), Decimal::of(Rows::string($row, 'large_sell_volume')),
                Rows::int($row, 'large_buy_count'), Rows::int($row, 'large_sell_count'), Decimal::of(Rows::string($row, 'largest_notional')), Decimal::of(Rows::string($row, 'threshold')),
            );
            $flow[$f->openTime->getTimestamp() + 60][$perpetuals[Rows::int($row, 'instrument_id')]] = $f;
        }

        $prices = ['mark_price_minutes' => [], 'premium_index_minutes' => []];

        foreach (array_keys($prices) as $table) {
            foreach ($minutes($table) as $row) {
                $p = new PriceMinute(Rows::time($row, 'open_time'), Decimal::of(Rows::string($row, 'open')), Decimal::of(Rows::string($row, 'high')), Decimal::of(Rows::string($row, 'low')), Decimal::of(Rows::string($row, 'close')));
                $prices[$table][$p->openTime->getTimestamp() + 60][$perpetuals[Rows::int($row, 'instrument_id')]] = $p;
            }
        }

        [$marks, $premium] = [$prices['mark_price_minutes'], $prices['premium_index_minutes']];

        $known = $this->db->table('open_interest')->whereIn('instrument_id', $ids)
            ->where('time', '>', Rows::timestamp($from->subSeconds(OpenInterest::KNOWN_AFTER_SECONDS)))
            ->where('time', '<=', Rows::timestamp($to->subSeconds(OpenInterest::KNOWN_AFTER_SECONDS)))->get();

        foreach ($known as $row) {
            $o = new OpenInterest(Rows::time($row, 'time'), Decimal::of(Rows::string($row, 'contracts')), Decimal::of(Rows::string($row, 'value')));
            $interest[$o->knownAt()->getTimestamp()][$perpetuals[Rows::int($row, 'instrument_id')]] = $o;
        }

        return [$flow, $marks, $premium, $interest];
    }

    /**
     * @param  list<Symbol>  $symbols
     * @return array<int, string> unified symbol by instrument id
     */
    private function instrumentIds(array $symbols): array
    {
        $ids = [];

        foreach ($symbols as $symbol) {
            $market = $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot;
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
