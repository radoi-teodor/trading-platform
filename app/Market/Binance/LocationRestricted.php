<?php

declare(strict_types=1);

namespace App\Market\Binance;

/** HTTP 451: Binance refuses this location. Not an outage (Known traps); use the binance.vision hosts. */
final class LocationRestricted extends BinanceRequestFailed {}
