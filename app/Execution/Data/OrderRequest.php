<?php

declare(strict_types=1);

namespace App\Execution\Data;

use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use InvalidArgumentException;

/** One order as sent to a venue. A market buy may name the quote amount to spend instead of a quantity. */
final readonly class OrderRequest
{
    private function __construct(
        public ClientOrderId $clientOrderId,
        public Symbol $symbol,
        public Side $side,
        public OrderType $type,
        public ?Decimal $quantity,
        public ?Decimal $quoteQuantity = null,
        public ?Price $price = null,
        public ?Price $stopPrice = null,
        public bool $reduceOnly = false,
    ) {
        $amount = $quantity ?? $quoteQuantity;

        if ($amount === null || ! $amount->isPositive()) {
            throw new InvalidArgumentException("Order {$clientOrderId} needs a positive quantity.");
        }
    }

    public static function market(ClientOrderId $id, Symbol $symbol, Side $side, Decimal $quantity): self
    {
        return new self($id, $symbol, $side, OrderType::Market, $quantity);
    }

    public static function marketForQuote(ClientOrderId $id, Symbol $symbol, Decimal $quoteQuantity): self
    {
        return new self($id, $symbol, Side::Buy, OrderType::Market, null, $quoteQuantity);
    }

    public static function limit(ClientOrderId $id, Symbol $symbol, Side $side, Decimal $quantity, Price $price): self
    {
        return new self($id, $symbol, $side, OrderType::Limit, $quantity, price: $price);
    }

    public static function stop(ClientOrderId $id, Symbol $symbol, Side $side, Decimal $quantity, Price $stopPrice): self
    {
        return new self($id, $symbol, $side, OrderType::StopMarket, $quantity, stopPrice: $stopPrice);
    }

    /** A perpetual take-profit: a market order triggered when the price reaches $trigger, always reduce-only (D-46). */
    public static function takeProfit(ClientOrderId $id, Symbol $symbol, Side $side, Decimal $quantity, Price $trigger): self
    {
        return new self($id, $symbol, $side, OrderType::TakeProfitMarket, $quantity, stopPrice: $trigger, reduceOnly: true);
    }

    /** The same order, allowed only to reduce a perpetual position. */
    public function reduceOnly(): self
    {
        return new self(
            $this->clientOrderId, $this->symbol, $this->side, $this->type, $this->quantity, $this->quoteQuantity,
            $this->price, $this->stopPrice, true,
        );
    }
}
