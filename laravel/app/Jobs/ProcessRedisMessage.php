<?php

namespace App\Jobs;

use App\Models\AgentHealth;
use App\Models\BotLog;
use App\Models\ClaudeAnalysis;
use App\Models\ClaudeAnalysisTrade;
use App\Models\Order;
use App\Models\Position;
use App\Models\Portfolio;
use App\Models\Trade;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRedisMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $message
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $type = $this->message['type'] ?? null;
        $userId = $this->message['user_id'] ?? null;

        if (!$type || !$userId) {
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        match ($type) {
            'heartbeat' => $this->handleHeartbeat($user),
            'agent_status' => $this->handleAgentStatus($user),
            'analysis_complete' => $this->handleAnalysisComplete($user, $notificationService),
            'trade_executed' => $this->handleTradeExecuted($user, $notificationService),
            'error' => $this->handleError($user, $notificationService),
            default => $this->logUnknown($user),
        };
    }

    private function handleHeartbeat(User $user): void
    {
        AgentHealth::updateOrCreate(
            [
                'user_id' => $user->id,
                'agent_type' => $this->message['agent_type'] ?? 'unknown',
                'agent_id' => $this->message['agent_id'] ?? 'unknown',
            ],
            [
                'status' => 'running',
                'last_heartbeat_at' => now(),
                'metadata' => $this->message['metadata'] ?? [],
            ]
        );
    }

    private function handleAgentStatus(User $user): void
    {
        AgentHealth::updateOrCreate(
            [
                'user_id' => $user->id,
                'agent_type' => $this->message['agent_type'] ?? 'unknown',
                'agent_id' => $this->message['agent_id'] ?? 'unknown',
            ],
            [
                'status' => $this->message['status'] ?? 'stopped',
                'last_heartbeat_at' => now(),
                'metadata' => $this->message['metadata'] ?? [],
            ]
        );

        BotLog::create([
            'user_id' => $user->id,
            'level' => 'info',
            'message' => "Agent status changed to: " . ($this->message['status'] ?? 'unknown'),
            'context' => $this->message,
            'created_at' => now(),
        ]);
    }

    private function handleAnalysisComplete(User $user, NotificationService $service): void
    {
        $analysisId = $this->message['analysis_id'] ?? null;

        if ($analysisId) {
            ClaudeAnalysis::where('id', $analysisId)
                ->where('user_id', $user->id)
                ->update(['status' => 'completed']);
        }

        BotLog::create([
            'user_id' => $user->id,
            'level' => 'info',
            'message' => 'Analysis completed successfully.',
            'context' => $this->message,
            'created_at' => now(),
        ]);

        $service->dispatch($user, 'analysis_complete', [
            'message' => 'Claude analysis completed. Check your strategies page for details.',
            'analysis_id' => $analysisId,
        ]);
    }

    private function handleTradeExecuted(User $user, NotificationService $service): void
    {
        BotLog::create([
            'user_id' => $user->id,
            'level' => 'trade',
            'message' => sprintf(
                'Trade executed: %s %s %s @ $%.2f',
                $this->message['side'] ?? '',
                $this->message['quantity'] ?? '',
                $this->message['symbol'] ?? '',
                ($this->message['price_cents'] ?? 0) / 100
            ),
            'context' => $this->message,
            'created_at' => now(),
        ]);

        $service->dispatch($user, 'trade_executed', [
            'message' => sprintf(
                'Trade executed: %s %s %s @ $%.2f',
                $this->message['side'] ?? '',
                $this->message['quantity'] ?? '',
                $this->message['symbol'] ?? '',
                ($this->message['price_cents'] ?? 0) / 100
            ),
        ]);
    }

    private function handleError(User $user, NotificationService $service): void
    {
        BotLog::create([
            'user_id' => $user->id,
            'level' => 'error',
            'message' => $this->message['error_message'] ?? 'Unknown error',
            'context' => $this->message,
            'created_at' => now(),
        ]);

        $service->dispatch($user, 'agent_error', [
            'message' => 'Agent error: ' . ($this->message['error_message'] ?? 'Unknown error'),
        ]);
    }

    private function logUnknown(User $user): void
    {
        BotLog::create([
            'user_id' => $user->id,
            'level' => 'warning',
            'message' => 'Received unknown message type: ' . ($this->message['type'] ?? 'null'),
            'context' => $this->message,
            'created_at' => now(),
        ]);
    }
}
