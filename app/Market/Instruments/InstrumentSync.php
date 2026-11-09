<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Data\InstrumentDefinition;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Illuminate\Database\ConnectionInterface;

/**
 * Brings the instruments table in line with what the exchange lists. Filters get a new row only when they
 * change; instruments the exchange no longer lists are marked delisted, never deleted.
 */
final readonly class InstrumentSync
{
    public function __construct(private Clock $clock, private ConnectionInterface $db) {}

    /** @param list<InstrumentDefinition> $definitions */
    public function sync(string $exchange, MarketType $market, array $definitions): SyncResult
    {
        return $this->db->transaction(function () use ($exchange, $market, $definitions): SyncResult {
            $now = $this->clock->now();
            $seen = [];
            $filterChanges = 0;

            foreach ($definitions as $definition) {
                $instrument = Instrument::register($exchange, $market, $definition->symbol, $now, $definition->status);
                $seen[] = $instrument->id;

                if ($this->filtersChanged($instrument, $definition)) {
                    InstrumentFilter::query()->create([
                        'instrument_id' => $instrument->id,
                        'valid_from' => $now,
                        'tick_size' => (string) $definition->tickSize,
                        'step_size' => (string) $definition->stepSize,
                        'min_qty' => (string) $definition->minQty,
                        'max_qty' => $definition->maxQty === null ? null : (string) $definition->maxQty,
                        'min_notional' => $definition->minNotional === null ? null : (string) $definition->minNotional,
                        'raw' => $definition->rawFilters,
                    ]);
                    $filterChanges++;
                }
            }

            $delisted = Instrument::query()
                ->where('exchange', $exchange)
                ->where('market', $market)
                ->whereNotIn('id', $seen)
                ->where('status', '!=', InstrumentStatus::Delisted)
                ->update(['status' => InstrumentStatus::Delisted]);

            return new SyncResult(count($definitions), $filterChanges, $delisted);
        });
    }

    private function filtersChanged(Instrument $instrument, InstrumentDefinition $definition): bool
    {
        $latest = InstrumentFilter::query()
            ->where('instrument_id', $instrument->id)
            ->orderByDesc('valid_from')
            ->first();

        return $latest === null
            || ! Decimal::of($latest->tick_size)->equals($definition->tickSize)
            || ! Decimal::of($latest->step_size)->equals($definition->stepSize)
            || ! Decimal::of($latest->min_qty)->equals($definition->minQty)
            || ! self::sameOptional($latest->max_qty, $definition->maxQty)
            || ! self::sameOptional($latest->min_notional, $definition->minNotional);
    }

    private static function sameOptional(?string $stored, ?Decimal $incoming): bool
    {
        if ($stored === null || $incoming === null) {
            return $stored === null && $incoming === null;
        }

        return Decimal::of($stored)->equals($incoming);
    }
}
