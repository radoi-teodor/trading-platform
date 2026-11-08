<?php

declare(strict_types=1);

namespace App\Market\Stream;

use WebSocket\Client;
use WebSocket\Exception\ExceptionInterface as WebSocketException;
use WebSocket\Message\Close;
use WebSocket\Message\Text;
use WebSocket\Middleware\CloseHandler;
use WebSocket\Middleware\PingResponder;

/** A blocking WebSocket client (phrity/websocket); pings are answered by its middleware. */
final class PhrityTransport implements StreamTransport
{
    private ?Client $client = null;

    public function __construct(private readonly int $timeoutSeconds = 90) {}

    public function connect(string $url): void
    {
        try {
            $client = new Client($url);
            $client->addMiddleware(new CloseHandler)->addMiddleware(new PingResponder);
            $client->setTimeout($this->timeoutSeconds);
            $client->connect();
            $this->client = $client;
        } catch (WebSocketException $failure) {
            throw new StreamFailed("Cannot connect to {$url}: {$failure->getMessage()}", previous: $failure);
        }
    }

    public function receive(): ?string
    {
        if ($this->client === null) {
            throw new StreamFailed('Not connected.');
        }

        try {
            while (true) {
                $message = $this->client->receive();

                if ($message instanceof Text) {
                    return $message->getContent();
                }

                if ($message instanceof Close) {
                    return null;
                }
            }
        } catch (WebSocketException $failure) {
            throw new StreamFailed($failure->getMessage(), previous: $failure);
        }
    }

    public function close(): void
    {
        try {
            $this->client?->close();
        } catch (WebSocketException) {
            // The connection is being dropped anyway.
        }

        $this->client = null;
    }
}
