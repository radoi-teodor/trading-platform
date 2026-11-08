<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Execution\Contracts\Venue;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Venues\SimulatedVenue;
use App\Support\Number\Decimal;
use App\Support\Symbol;

/** Passes everything to a simulated venue; tests extend it to falsify one answer. */
abstract class ForwardingVenue implements Venue
{
    public function __construct(protected readonly SimulatedVenue $inner) {}

    public function submit(OrderRequest $request): OrderAck
    {
        return $this->inner->submit($request);
    }

    public function submitOco(OcoRequest $request): OrderAck
    {
        return $this->inner->submitOco($request);
    }

    public function cancel(ClientOrderId $id): bool
    {
        return $this->inner->cancel($id);
    }

    public function updatesSince(int $sequence): array
    {
        return $this->inner->updatesSince($sequence);
    }

    public function openOrders(): array
    {
        return $this->inner->openOrders();
    }

    public function balances(): array
    {
        return $this->inner->balances();
    }

    public function futuresWallet(): Decimal
    {
        return $this->inner->futuresWallet();
    }

    public function positions(): array
    {
        return $this->inner->positions();
    }

    public function setLeverage(Symbol $symbol, int $leverage): void
    {
        $this->inner->setLeverage($symbol, $leverage);
    }
}
