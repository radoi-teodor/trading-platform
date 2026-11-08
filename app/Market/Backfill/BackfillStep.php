<?php

declare(strict_types=1);

namespace App\Market\Backfill;

use App\Market\Data\UpsertResult;

final readonly class BackfillStep
{
    public function __construct(public string $source, public UpsertResult $result) {}
}
