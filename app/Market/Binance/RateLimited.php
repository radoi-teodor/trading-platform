<?php

declare(strict_types=1);

namespace App\Market\Binance;

/** HTTP 429 or 418: stop, do not retry in a loop, or Binance bans the address for longer. */
final class RateLimited extends BinanceRequestFailed {}
