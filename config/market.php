<?php

declare(strict_types=1);

// The instruments the platform follows (D-33). Every listed instrument is loaded; only these are backfilled
// and streamed.
return [
    'universe' => [
        'spot' => ['BTC/USDT', 'ETH/USDT', 'SOL/USDT', 'BNB/USDT', 'XRP/USDT'],
        'perp' => ['BTC/USDT:USDT', 'ETH/USDT:USDT', 'SOL/USDT:USDT', 'BNB/USDT:USDT', 'XRP/USDT:USDT'],
    ],
];
