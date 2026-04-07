<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TriggerAnalysis extends Command
{
    protected $signature = 'analysis:trigger {user_id?}';

    protected $description = 'Trigger a Claude analysis for a user (or all users if no ID given)';

    public function handle(): int
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return self::FAILURE;
            }
            $this->triggerForUser($user);
        } else {
            $users = User::has('settings')->get();
            if ($users->isEmpty()) {
                $this->info('No users with settings found.');
                return self::SUCCESS;
            }

            foreach ($users as $user) {
                $this->triggerForUser($user);
            }
        }

        return self::SUCCESS;
    }

    private function triggerForUser(User $user): void
    {
        try {
            Redis::publish('trading:commands', json_encode([
                'type' => 'trigger_analysis',
                'user_id' => $user->id,
                'timestamp' => now()->toISOString(),
            ]));

            $this->info("Analysis triggered for user: {$user->name} (ID: {$user->id})");
        } catch (\Exception $e) {
            $this->error("Failed to trigger analysis for {$user->name}: {$e->getMessage()}");
        }
    }
}
