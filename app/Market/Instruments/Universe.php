<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Models\UniverseEntry;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * The instruments the platform follows (D-33, D-80): the market_universe table, seeded once from config/market.php
 * and edited on the Settings screen. All of them are backfilled with --universe; the streamed ones are streamed.
 */
final readonly class Universe
{
    public function __construct(private Clock $clock, private LoggerInterface $logger) {}

    /** @return list<Symbol> every instrument of the market, streamed or history only, in the order they were added */
    public function symbols(MarketType $market): array
    {
        return array_values(UniverseEntry::query()->where('market', $market)->orderBy('id')->get()->map(fn (UniverseEntry $e): Symbol => Symbol::parse($e->symbol))->all());
    }

    /** @return list<Instrument> the instruments of the market that are streamed */
    public function instruments(MarketType $market): array
    {
        return array_values(UniverseEntry::query()->where('market', $market)->where('streamed', true)->orderBy('id')->get()
            ->map(fn (UniverseEntry $e): Instrument => $this->instrument(Symbol::parse($e->symbol)))->all());
    }

    /** @return array<string, Instrument> the streamed perpetuals whose data for scalping is collected (D-79), by venue symbol */
    public function scalping(): array
    {
        $instruments = [];

        foreach (UniverseEntry::query()->where('market', MarketType::Perp)->where('streamed', true)->where('scalping', true)->orderBy('id')->get() as $entry) {
            $instrument = $this->instrument(Symbol::parse($entry->symbol));
            $instruments[$instrument->venue_symbol] = $instrument;
        }

        return $instruments;
    }

    /** The notional from which a trade of the instrument counts as large (D-79): its own, or the default by base asset. */
    public function largeNotional(Instrument $instrument): Decimal
    {
        $own = UniverseEntry::query()->where('market', $instrument->market)->where('symbol', $instrument->symbol)->value('large_notional');

        if (is_string($own)) {
            return Decimal::of($own);
        }

        $defaults = Config::array('market.large_trade_notional');
        $value = $defaults[$instrument->base] ?? $defaults['default'] ?? null;

        return is_string($value) ? Decimal::of($value) : throw new InvalidArgumentException("config/market.php has no large_trade_notional for {$instrument->base} and no default.");
    }

    /** Fills an empty universe from config/market.php, once (D-80); a universe with entries is left as it is. */
    public function seedFromConfig(): void
    {
        if (UniverseEntry::query()->exists()) {
            return;
        }

        $scalping = Config::array('market.scalping');

        foreach (MarketType::cases() as $market) {
            foreach (Config::array("market.universe.{$market->value}") as $symbol) {
                UniverseEntry::query()->create([
                    'market' => $market, 'symbol' => is_string($symbol) ? $symbol : throw new InvalidArgumentException('config/market.php: every universe entry must be a symbol.'),
                    'streamed' => true, 'scalping' => in_array($symbol, $scalping, true), 'created_at' => $this->clock->now(),
                ]);
            }
        }
    }

    /** Adds a stored instrument (D-80); one the exchange does not list is refused. */
    public function add(Symbol $symbol): UniverseEntry
    {
        $market = $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot;

        if (! (new StoredInstruments)->isTradable($symbol)) {
            throw new InvalidArgumentException("{$symbol} is not a stored instrument; run market:instruments first.");
        }

        return UniverseEntry::query()->firstOrCreate(['market' => $market, 'symbol' => (string) $symbol], ['streamed' => true, 'scalping' => false, 'created_at' => $this->clock->now()]);
    }

    /** The instrument loaded by market:instruments, or registered from its symbol when that was not possible. */
    public function instrument(Symbol $symbol): Instrument
    {
        $market = $symbol->isPerpetual() ? MarketType::Perp : MarketType::Spot;
        $instrument = Instrument::query()
            ->where('exchange', 'binance')
            ->where('market', $market)
            ->where('symbol', (string) $symbol)
            ->first();

        if ($instrument !== null) {
            return $instrument;
        }

        $this->logger->warning("{$symbol} is not loaded from exchangeInfo; registered without filters. Run market:instruments when the exchange is reachable.");

        return Instrument::register('binance', $market, $symbol, $this->clock->now());
    }
}
