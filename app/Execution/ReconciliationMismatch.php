<?php

declare(strict_types=1);

namespace App\Execution;

use RuntimeException;

/** The ledger and the venue disagree (D-10, D-41). In a backtest both are our code, so this is a bug. */
final class ReconciliationMismatch extends RuntimeException {}
