<?php

declare(strict_types=1);

namespace App\Strategy\Versions;

use App\Strategy\Dsl\Document\CheckResult;

/** What saving a document gave: its version (null when it did not compile), whether it is new, and the check. */
final readonly class SaveResult
{
    public function __construct(public ?StrategyVersion $version, public bool $created, public CheckResult $check) {}
}
