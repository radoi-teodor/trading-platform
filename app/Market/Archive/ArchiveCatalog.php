<?php

declare(strict_types=1);

namespace App\Market\Archive;

use App\Market\Binance\RequestFailed;
use App\Support\Symbol;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as HttpFactory;
use SimpleXMLElement;
use UnexpectedValueException;

/**
 * What Binance's public archive holds (D-89): its S3 listing names every USD-M symbol that ever had a monthly kline
 * file, delisted ones included, which exchangeInfo forgets after a while.
 */
final readonly class ArchiveCatalog
{
    private const string KLINES = 'data/futures/um/monthly/klines/';

    /** Quote assets of USD-M perpetuals, longest first so FDUSD is not read as USD. */
    private const array QUOTES = ['FDUSD', 'USDT', 'USDC', 'BUSD'];

    public function __construct(private HttpFactory $http, private string $listingUrl, private int $timeoutSeconds) {}

    /** @return list<string> venue symbols of every perpetual with archives, in the listing's order */
    public function perpetuals(): array
    {
        $symbols = [];
        $marker = '';

        do {
            $page = $this->page(self::KLINES, $marker);

            foreach ($page->CommonPrefixes as $prefix) {
                $symbol = rtrim(substr((string) $prefix->Prefix, strlen(self::KLINES)), '/');

                // Delivery contracts carry their expiry (BTCUSDT_250328); only perpetuals are kept.
                if ($symbol !== '' && ! str_contains($symbol, '_')) {
                    $symbols[] = $symbol;
                }
            }

            $marker = (string) $page->NextMarker;
        } while ((string) $page->IsTruncated === 'true' && $marker !== '');

        return $symbols;
    }

    /** The unified symbol of a USD-M venue symbol, settled in its quote: SRMUSDT is SRM/USDT:USDT. */
    public static function unified(string $venueSymbol): ?Symbol
    {
        foreach (self::QUOTES as $quote) {
            if (str_ends_with($venueSymbol, $quote) && strlen($venueSymbol) > strlen($quote)) {
                return Symbol::parse(substr($venueSymbol, 0, -strlen($quote))."/{$quote}:{$quote}");
            }
        }

        return null;
    }

    private function page(string $prefix, string $marker): SimpleXMLElement
    {
        $query = array_filter(['delimiter' => '/', 'prefix' => $prefix, 'marker' => $marker], fn (string $value): bool => $value !== '');

        try {
            $response = $this->http->timeout($this->timeoutSeconds)->get($this->listingUrl, $query);
        } catch (ConnectionException $failure) {
            throw new RequestFailed("Binance archive listing: {$failure->getMessage()}", previous: $failure);
        }

        if (! $response->successful()) {
            throw new RequestFailed("Binance archive listing failed with {$response->status()}.");
        }

        $xml = simplexml_load_string($response->body());

        return $xml === false ? throw new UnexpectedValueException('Binance archive listing: not XML.') : $xml;
    }
}
