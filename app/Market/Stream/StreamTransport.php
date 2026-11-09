<?php

declare(strict_types=1);

namespace App\Market\Stream;

interface StreamTransport
{
    public function connect(string $url): void;

    /** The next text payload, or null when the server ended the connection. Throws StreamFailed on errors. */
    public function receive(): ?string;

    public function close(): void;
}
