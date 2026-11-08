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
            'stream' => env('BINANCE_PERP_STREAM', 'wss://fstream.binance.com'),
        ],
        'archive' => env('BINANCE_ARCHIVE', 'https://data.binance.vision'),
        // Verified archives are kept here and checked against their checksum again on reuse.
        'archive_cache' => storage_path('app/binance-archive'),
        'timeout_seconds' => 15,
    ],
];
