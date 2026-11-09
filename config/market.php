<?php

declare(strict_types=1);

// The instruments the platform follows (D-33). Every listed instrument is loaded; only these are backfilled
// and streamed.
return [
    'universe' => [
        'spot' => ['BTC/USDT', 'ETH/USDT', 'SOL/USDT', 'BNB/USDT', 'XRP/USDT'],
        'perp' => ['BTC/USDT:USDT', 'ETH/USDT:USDT', 'SOL/USDT:USDT', 'BNB/USDT:USDT', 'XRP/USDT:USDT'],
    ],
    // The notional, in quote, from which a trade counts as large in the trade flow (D-79), by base asset.
    'large_trade_notional' => ['BTC' => '100000', 'ETH' => '50000', 'default' => '25000'],
];
