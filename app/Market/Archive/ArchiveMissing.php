<?php

declare(strict_types=1);

namespace App\Market\Archive;

use RuntimeException;

/** Binance has not published this archive (yet). The backfill plan moves to daily archives or REST. */
final class ArchiveMissing extends RuntimeException {}
