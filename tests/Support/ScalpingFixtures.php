<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Serves the scalping archives of tests/Fixtures/binance/scalping for data.binance.vision (BTCUSDT, 2026-11-01): the
 * real metrics, mark price and premium index files, and the day's first 5000 aggregated trades (README there).
 */
final class ScalpingFixtures
{
    public static function fake(): void
    {
        $directory = dirname(__DIR__).'/Fixtures/binance/scalping';

        Http::fake(['data.binance.vision/*' => function (Request $request) use ($directory) {
            $path = (string) parse_url($request->url(), PHP_URL_PATH);
            $sub = match (true) {
                str_contains($path, '/markPriceKlines/') => '/markPriceKlines/',
                str_contains($path, '/premiumIndexKlines/') => '/premiumIndexKlines/',
                default => '/',
            };
            $file = $directory.$sub.basename($path);

            return is_file($file) ? Http::response((string) file_get_contents($file)) : Http::response('', 404);
        }]);

        if (! is_dir($directory)) {
            throw new RuntimeException('The scalping fixtures are missing.');
        }
    }
}
