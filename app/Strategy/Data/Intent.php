<?php

declare(strict_types=1);

namespace App\Strategy\Data;

use App\Support\Symbol;

/** What a strategy wants; the risk engine decides whether and how much (D-7, docs/engine.md, section 5). */
interface Intent
{
    public function symbol(): Symbol;

    public function reason(): string;
}
