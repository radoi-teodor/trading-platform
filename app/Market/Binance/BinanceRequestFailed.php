<?php

declare(strict_types=1);

namespace App\Market\Binance;

use RuntimeException;

/** Base of every Binance request failure; catch this to handle them all. */
abstract class BinanceRequestFailed extends RuntimeException {}
