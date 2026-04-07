<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public string $eventType,
        public array $payload = []
    ) {}

    public function handle(NotificationService $service): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            return;
        }

        $service->dispatch($user, $this->eventType, $this->payload);
    }
}
