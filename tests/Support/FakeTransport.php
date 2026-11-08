<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Market\Stream\StreamFailed;
use App\Market\Stream\StreamTransport;

/** A scripted WebSocket: each session is a list of payloads; the string FAIL makes that receive() throw. */
final class FakeTransport implements StreamTransport
{
    public const string FAIL = '__fail__';

    /** @var list<string> */
    public array $connectedTo = [];

    /** @var list<string> */
    private array $current = [];

    /** @param list<list<string>> $sessions */
    public function __construct(private array $sessions) {}

    public function connect(string $url): void
    {
        $this->connectedTo[] = $url;
        $this->current = array_shift($this->sessions) ?? [];
    }

    public function receive(): ?string
    {
        $next = array_shift($this->current);

        if ($next === self::FAIL) {
            throw new StreamFailed('scripted failure');
        }

        return $next;
    }

    public function close(): void {}
}
