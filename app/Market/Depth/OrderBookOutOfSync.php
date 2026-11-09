<?php

declare(strict_types=1);

namespace App\Market\Depth;

use RuntimeException;

/** The local order book missed an update or its snapshot is too old: it must start again from a new snapshot. */
final class OrderBookOutOfSync extends RuntimeException {}
