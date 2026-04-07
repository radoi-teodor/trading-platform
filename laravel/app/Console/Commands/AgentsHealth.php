<?php

namespace App\Console\Commands;

use App\Models\AgentHealth;
use Illuminate\Console\Command;

class AgentsHealth extends Command
{
    protected $signature = 'agents:health';

    protected $description = 'Display the health status of all agents';

    public function handle(): int
    {
        $agents = AgentHealth::with('user')->orderBy('agent_type')->get();

        if ($agents->isEmpty()) {
            $this->info('No agents registered.');
            return self::SUCCESS;
        }

        $rows = $agents->map(function ($agent) {
            $heartbeatAgo = $agent->last_heartbeat_at
                ? $agent->last_heartbeat_at->diffForHumans()
                : 'Never';

            return [
                $agent->id,
                $agent->user->name ?? 'N/A',
                $agent->agent_type . ':' . $agent->agent_id,
                $agent->status,
                $heartbeatAgo,
            ];
        })->toArray();

        $this->table(
            ['ID', 'User', 'Agent', 'Status', 'Last Heartbeat'],
            $rows
        );

        $running = $agents->where('status', 'running')->count();
        $stopped = $agents->where('status', 'stopped')->count();
        $errored = $agents->where('status', 'error')->count();

        $this->newLine();
        $this->info("Summary: {$running} running, {$stopped} stopped, {$errored} errored");

        return self::SUCCESS;
    }
}
