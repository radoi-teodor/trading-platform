<?php

declare(strict_types=1);

namespace App\Market\Binance;

use App\Market\Data\MarketType;
use App\Support\Clock\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

/**
 * Signed requests to Binance's spot and USD-M APIs (D-69): HMAC-SHA256 of the query with the account's secret, the
 * key in the X-MBX-APIKEY header. Errors name the status and Binance's code and message, never the key, the secret
 * or the signed query.
 */
final readonly class SignedBinanceClient
{
    private const int RECV_WINDOW = 5000;

    public function __construct(
        private HttpFactory $http,
        private Clock $clock,
        private string $spotBase,
        private string $futuresBase,
        private string $key,
        private string $secret,
    ) {}

    public static function sign(string $query, string $secret): string
    {
        return hash_hmac('sha256', $query, $secret);
    }

    /**
     * @param  array<string, string|int|bool>  $params
     * @return array<array-key, mixed>
     */
    public function signed(MarketType $market, string $method, string $path, array $params = []): array
    {
        $params = [...$params, 'recvWindow' => self::RECV_WINDOW, 'timestamp' => $this->clock->now()->getTimestamp() * 1000];
        $query = http_build_query(array_map(fn (string|int|bool $v): string => is_bool($v) ? ($v ? 'true' : 'false') : (string) $v, $params), '', '&', PHP_QUERY_RFC3986);
        $url = ($market === MarketType::Spot ? $this->spotBase : $this->futuresBase).$path.'?'.$query.'&signature='.self::sign($query, $this->secret);

        return $this->send($method, $url);
    }

    /** The exchange's clock, for the clock-skew breaker. */
    public function serverTime(MarketType $market): CarbonImmutable
    {
        $answer = $this->send('GET', ($market === MarketType::Spot ? $this->spotBase.'/api/v3/time' : $this->futuresBase.'/fapi/v1/time'));
        $ms = $answer['serverTime'] ?? null;

        return CarbonImmutable::createFromTimestampMsUTC(is_int($ms) ? $ms : 0);
    }

    /** @return array<array-key, mixed> */
    private function send(string $method, string $url): array
    {
        try {
            $response = $this->http->withHeaders(['X-MBX-APIKEY' => $this->key])->timeout(10)->send($method, $url);
        } catch (Throwable) {
            throw new BinanceUnreachable("Binance could not be reached for {$method} ".parse_url($url, PHP_URL_PATH).'.');
        }

        $body = $response->json();

        if ($response->serverError()) {
            throw new BinanceUnreachable("Binance answered {$response->status()} to {$method} ".parse_url($url, PHP_URL_PATH).'.');
        }

        if (! $response->successful()) {
            $code = is_array($body) && is_int($body['code'] ?? null) ? $body['code'] : 0;
            $message = is_array($body) && is_string($body['msg'] ?? null) ? $body['msg'] : 'no message';

            throw new BinanceApiError($response->status(), $code, $message);
        }

        return is_array($body) ? $body : [];
    }
}
