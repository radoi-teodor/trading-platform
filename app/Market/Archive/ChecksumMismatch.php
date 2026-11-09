<?php

declare(strict_types=1);

namespace App\Market\Archive;

use RuntimeException;

/** The downloaded archive does not match its published SHA-256. Nothing of it is used or kept. */
final class ChecksumMismatch extends RuntimeException {}
