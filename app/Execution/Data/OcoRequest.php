<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;

/** A protective pair: a stop and a take-profit limit on the same quantity, where a fill of one cancels the other. */
final readonly class OcoRequest
{
    public function __construct(
        public ClientOrderId $stopId,
        public ClientOrderId $targetId,
        public Symbol $symbol,
        public Side $side,
        public Decimal $quantity,
        public Price $stopPrice,
        public Price $targetPrice,
    ) {}

    public function stopLeg(): OrderRequest
    {
        return OrderRequest::stop($this->stopId, $this->symbol, $this->side, $this->quantity, $this->stopPrice);
    }

    public function targetLeg(): OrderRequest
    {
        return OrderRequest::limit($this->targetId, $this->symbol, $this->side, $this->quantity, $this->targetPrice);
    }
}
