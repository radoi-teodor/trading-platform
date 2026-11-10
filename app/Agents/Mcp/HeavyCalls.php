<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository;
use LogicException;

/**
 * The limit of the tools that read the whole market (D-93): each token gets mcp.heavy_calls_per_minute answers a
 * minute from them, counted apart from the limit on all calls, so screening cannot crowd the database.
 */
final readonly class HeavyCalls
{
    public function __construct(private RateLimiter $limiter, private Repository $config) {}

    /** Counts one call of $author, or refuses it when the minute is used up. */
    public function take(string $author): void
    {
        $limit = $this->config->get('mcp.heavy_calls_per_minute');

        if (! is_int($limit) || $limit < 1) {
            throw new LogicException('mcp.heavy_calls_per_minute is a whole number above zero.');
        }

        $key = 'mcp-heavy:'.$author;

        if ($this->limiter->tooManyAttempts($key, $limit)) {
            throw new Refused("screen_market and get_listing_stats answer {$limit} times a minute for a token; ask again in a minute.");
        }

        $this->limiter->hit($key, 60);
    }
}
