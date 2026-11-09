<?php

declare(strict_types=1);

namespace App\Support\Number;

use DomainException;

/** Raised when an operation would combine amounts of different assets (docs/conventions.md, section 3). */
final class AssetMismatch extends DomainException {}
