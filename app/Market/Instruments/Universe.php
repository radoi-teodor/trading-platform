<?php

declare(strict_types=1);

namespace App\Market\Instruments;

use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/** The instruments the platform follows (D-33, config/market.php). */
final readonly class Universe
{
    public function __construct(private Clock $clock, private LoggerInterface $logger) {}

    /** @return list<Symbol> */
    public function symbols(MarketType $market): array
    {
        $symbols = [];

        foreach (Config::array("market.universe.{$market->value}") as $symbol) {
            if (! is_string($symbol)) {
                throw new InvalidArgumentException('config/market.php: every universe entry must be a symbol.');
            }

            $symbols[] = Symbol::parse($symbol);
        }

        return $symbols;
    }

    /** @return list<Instrument> */
    public function instruments(MarketType $market): array
    {
        return array_map(fn (Symbol $symbol): Instrument => $this->instrument($symbol), $this->symbols($market));
    }

    /** @return array<string, Instrument> the perpetuals whose data for scalping is collected (D-79), by venue symbol */
    public function scalping(): array
    {
        $instruments = [];

        foreach (Config::array('market.scalping') as $symbol) {
            $instrument = $this->instrument(Symbol::parse(is_string($symbol) ? $symbol : throw new InvalidArgumentException('config/market.php: every scalping entry must be a symbol.')));
            $instruments[$instrument->venue_symbol] = $instrument;
        }

        return $instruments;
    }

    /** The notional from which a trade of the instrument counts as large (D-79). */
    public function largeNotional(Instrument $instrument): Decimal
    {
        $defaults = Config::array('market.large_trade_notional');
        $value = $defaults[$instrument->base] ?? $defaults['default'] ?? null;

        return is_string($value) ? Decimal::of($value) : throw new InvalidArgumentException("config/market.php has no large_trade_notional for {$instrument->base} and no default.");
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
