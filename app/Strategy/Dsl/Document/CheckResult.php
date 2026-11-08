<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Document;

use App\Strategy\Dsl\DslError;

/** What checking a document gave: the spec when there was no error, every error found, and warnings. */
final readonly class CheckResult
{
    /**
     * @param  list<DslError>  $errors
     * @param  list<string>  $warnings
     */
    public function __construct(public ?StrategySpec $spec, public array $errors, public array $warnings) {}
}
