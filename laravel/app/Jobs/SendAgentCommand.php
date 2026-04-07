<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class SendAgentCommand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $command,
        public array $payload = []
    ) {}

    public function handle(): void
    {
        $message = array_merge($this->payload, [
            'command' => $this->command,
            'timestamp' => now()->toISOString(),
        ]);

        Redis::publish('trading:commands', json_encode($message));
    }
}
