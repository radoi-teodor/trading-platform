<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Contracts\Venue;
use App\Execution\Data\Balance;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use LogicException;

/**
 * An exchange venue while the engine warms its indicators up on stored minutes: the account as read once at the
 * start, so that the warmup costs no request per minute. It places nothing: warmup intents are rejected and the
 * account starts clean. After warmup it passes everything to the exchange.
 */
final class WarmingVenue implements Venue
{
    private bool $warm = false;

    /** @var array<string, Balance> */
    private array $balances;

    private Decimal $wallet;

    public function __construct(private readonly Venue $inner)
    {
        $this->balances = $inner->balances();
        $this->wallet = $inner->futuresWallet();
    }

    public function warmedUp(): void
    {
        $this->warm = true;
    }

    public function submit(OrderRequest $request): OrderAck
    {
        return $this->warm ? $this->inner->submit($request) : throw new LogicException('Nothing is placed during warmup.');
    }

    public function submitOco(OcoRequest $request): OrderAck
    {
        return $this->warm ? $this->inner->submitOco($request) : throw new LogicException('Nothing is placed during warmup.');
    }

    public function cancel(ClientOrderId $id): bool
    {
        return $this->warm ? $this->inner->cancel($id) : throw new LogicException('Nothing is cancelled during warmup.');
    }

    public function updatesSince(int $sequence): array
    {
        return $this->warm ? $this->inner->updatesSince($sequence) : [];
    }

    public function openOrders(): array
    {
        return $this->warm ? $this->inner->openOrders() : [];
    }

    public function balances(): array
    {
        return $this->warm ? $this->inner->balances() : $this->balances;
    }

    public function futuresWallet(): Decimal
    {
        return $this->warm ? $this->inner->futuresWallet() : $this->wallet;
    }

    public function positions(): array
    {
        return $this->warm ? $this->inner->positions() : [];
    }

    public function setLeverage(Symbol $symbol, int $leverage): void
    {
        $this->warm ? $this->inner->setLeverage($symbol, $leverage) : throw new LogicException('Nothing is set during warmup.');
    }
}
