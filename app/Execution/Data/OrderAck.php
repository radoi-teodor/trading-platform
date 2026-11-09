<?php

declare(strict_types=1);

namespace App\Execution\Data;

/** The venue's immediate answer to a submission; $reason names the filter or check that refused it. */
final readonly class OrderAck
{
    private function __construct(public ClientOrderId $clientOrderId, public bool $accepted, public ?string $reason) {}

    public static function accepted(ClientOrderId $id): self
    {
        return new self($id, true, null);
    }

    public static function rejected(ClientOrderId $id, string $reason): self
    {
        return new self($id, false, $reason);
    }
}
