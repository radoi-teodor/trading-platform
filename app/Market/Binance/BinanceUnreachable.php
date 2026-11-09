<?php

declare(strict_types=1);

namespace App\Market\Binance;

/** Binance could not be reached, or answered with a server error. */
final class BinanceUnreachable extends BinanceRequestFailed {}
