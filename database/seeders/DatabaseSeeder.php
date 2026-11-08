<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seeds reference data only. It never creates a user or a password: the operator account is created
     * on purpose by a command (docs/security.md, section 3).
     */
    public function run(): void
    {
        //
    }
}
