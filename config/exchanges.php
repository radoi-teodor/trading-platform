<?php

declare(strict_types=1);

// Exchange endpoints (D-30, docs/market-data.md section 1). No secrets here: keys live encrypted in the database.
return [
    'binance' => [
        'spot' => [
            'rest' => env('BINANCE_SPOT_REST', 'https://data-api.binance.vision'),
            'stream' => env('BINANCE_SPOT_STREAM', 'wss://data-stream.binance.vision'),
        ],
        'perp' => [
            'rest' => env('BINANCE_PERP_REST', 'https://fapi.binance.com'),
            // USD-M market streams (klines, aggregated trades, mark price) are served under /market; the bare host
            // still accepts a connection and sends nothing (found 2026-11-09).
            'stream' => env('BINANCE_PERP_STREAM', 'wss://fstream.binance.com/market'),
        ],
        'archive' => env('BINANCE_ARCHIVE', 'https://data.binance.vision'),
        // Verified archives are kept here and checked against their checksum again on reuse.
        'archive_cache' => storage_path('app/binance-archive'),
        'timeout_seconds' => 15,
        // Testnet accounts (Phase 4): the same APIs on Binance's test networks, with test funds only.
        'testnet' => [
            'spot' => env('BINANCE_TESTNET_SPOT', 'https://testnet.binance.vision'),
            'perp' => env('BINANCE_TESTNET_PERP', 'https://testnet.binancefuture.com'),
        ],
        // Live accounts (Phase 6, D-82): real money, behind the operator's approval and the key checks.
        'live' => [
            'spot' => env('BINANCE_LIVE_SPOT', 'https://api.binance.com'),
            'perp' => env('BINANCE_LIVE_PERP', 'https://fapi.binance.com'),
        ],
    ],
];
