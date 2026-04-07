<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if demo user already exists
        $existing = DB::table('users')->where('email', 'demo@example.com')->first();
        if ($existing) {
            $this->command->info('Demo user already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        // 1. Demo user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Default user settings
        DB::table('user_settings')->insert([
            'user_id' => $userId,
            'risk_tolerance' => 'moderate',
            'max_daily_trades' => 10,
            'max_position_pct' => 20,
            'max_daily_loss_pct' => 5,
            'preferred_markets' => json_encode(['us_stocks']),
            'custom_instructions' => null,
            'analysis_schedule' => 'premarket',
            'auto_execute' => false,
            'timezone' => 'America/New_York',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. Default portfolio with $100,000 paper balance
        DB::table('portfolios')->insert([
            'user_id' => $userId,
            'name' => 'Main Portfolio',
            'balance_cents' => 10000000,
            'initial_balance_cents' => 10000000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Empty watchlist
        DB::table('watchlists')->insert([
            'user_id' => $userId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. Paper broker credential (active by default)
        DB::table('broker_credentials')->insert([
            'user_id' => $userId,
            'broker' => 'paper',
            'credentials' => json_encode(['mode' => 'paper']),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 6. Email notification channel
        DB::table('notification_channels')->insert([
            'user_id' => $userId,
            'type' => 'email',
            'config' => json_encode(['email' => 'demo@example.com']),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->command->info('Demo user created: demo@example.com / password');
        $this->command->info('Portfolio: $100,000 paper balance');
        $this->command->info('Configure your scrapers, watchlist, and strategy settings to get started.');
    }
}
