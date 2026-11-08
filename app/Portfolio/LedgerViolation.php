<?php

declare(strict_types=1);

namespace App\Portfolio;

use RuntimeException;

/** A fill or a request the ledger cannot book: a negative balance, an unknown position, a fill seen twice. */
final class LedgerViolation extends RuntimeException {}
