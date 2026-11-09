<?php

declare(strict_types=1);

namespace App\Execution\Journal;

use RuntimeException;

/** A replayed record differs from the journaled one: the account's state cannot be trusted (D-68). */
final class ReplayDivergence extends RuntimeException {}
