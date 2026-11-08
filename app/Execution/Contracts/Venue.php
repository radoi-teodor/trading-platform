<?php

declare(strict_types=1);

namespace App\Execution\Contracts;

use App\Execution\Data\Balance;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\Income;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenueOrder;
use App\Execution\Data\VenuePosition;
use App\Support\Number\Decimal;
use App\Support\Symbol;

/** What the engine knows of an exchange (docs/engine.md, section 3). The venue is the source of truth (D-10). */
interface Venue
{
    public function submit(OrderRequest $request): OrderAck;

    public function submitOco(OcoRequest $request): OrderAck;

    /** True when the order was working and is now cancelled; false when it was already final. */
    public function cancel(ClientOrderId $id): bool;

    /** @return list<OrderUpdate|Income> order updates and perpetual income numbered after $sequence, in order */
    public function updatesSince(int $sequence): array;

    /** @return list<VenueOrder> working orders, in submission order */
    public function openOrders(): array;

    /** @return array<string, Balance> spot balances by asset code, in code order */
    public function balances(): array;

    /** USDT in the USD-M wallet, outside the positions' margins, working orders' reservations included. */
    public function futuresWallet(): Decimal;

    /** @return array<string, VenuePosition> open perpetual positions by unified symbol, in symbol order */
    public function positions(): array;

    /** Sets the leverage of the next perpetual position on $symbol; an open position keeps its own. */
    public function setLeverage(Symbol $symbol, int $leverage): void;
}
