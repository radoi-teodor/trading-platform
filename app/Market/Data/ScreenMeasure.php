<?php

declare(strict_types=1);

namespace App\Market\Data;

/** What a market screen ranks instruments by over its window (D-93). */
enum ScreenMeasure: string
{
    /** The last close over the first open, less one. */
    case PriceReturn = 'return';

    /** The quote volume of the window's days, summed. */
    case QuoteVolume = 'quote_volume';

    /** The average funding rate of the window's days. */
    case Funding = 'funding';

    /** The open interest value of the last day over that of the first, less one. */
    case OpenInterestChange = 'open_interest_change';

    /** The average of each day's range, high less low, over its open. */
    case Volatility = 'volatility';

    /**
     * The column of MarketScreen's rows that holds it.
     *
     * @return literal-string
     */
    public function column(): string
    {
        return match ($this) {
            self::PriceReturn => 'price_return',
            self::QuoteVolume => 'quote_volume',
            self::Funding => 'funding',
            self::OpenInterestChange => 'open_interest_change',
            self::Volatility => 'volatility',
        };
    }
}
