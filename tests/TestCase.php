<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** The suite renders pages without the front-end build: CI's test job does not run `npm run build`. */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }
}
