<?php

declare(strict_types=1);

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderUpdate;
use App\Execution\Venues\BinanceVenue;
use App\Market\Binance\BinanceApiError;
use App\Market\Binance\BinanceUnreachable;
use App\Market\Binance\SignedBinanceClient;
use App\Support\Asset;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

const TEST_KEY = 'test-key-not-real';
const TEST_SECRET = 'test-secret-not-real';

function binanceVenue(string ...$symbols): BinanceVenue
{
    $client = new SignedBinanceClient(app(Factory::class), new SimClock(CarbonImmutable::parse('2026-11-09 10:00', 'UTC')), 'https://spot.test', 'https://fut.test', TEST_KEY, TEST_SECRET);

    return new BinanceVenue($client, array_values(array_map(Symbol::parse(...), $symbols ?: ['BTC/USDT'])), Asset::of('USDT'), CarbonImmutable::parse('2026-11-09 10:00', 'UTC'));
}

/** @return array<string, string> */
function queryOf(Request $request): array
{
    parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);
    $strings = [];

    foreach ($query as $key => $value) {
        $strings[(string) $key] = is_string($value) ? $value : '';
    }

    return $strings;
}

it('signs a query as Binance\'s documentation does', function (): void {
    // The example of Binance's API documentation (SIGNED endpoint security).
    expect(SignedBinanceClient::sign(
        'symbol=LTCBTC&side=BUY&type=LIMIT&timeInForce=GTC&quantity=1&price=0.1&recvWindow=5000&timestamp=1499827319559',
        'NhqPtmdSJYdKjVHjA7PZj4Mge3R5YNiP1e3UZjInClVN65XAbvqqM6A7H5fATj0j',
    ))->toBe('c8db56825ae71d6d79447849e617115f4a920fa2acdcab2b053c4b2838bd6b71');
});

it('places a market order, then reads its fill and its end from the exchange, once', function (): void {
    Http::fake([
        'spot.test/api/v3/order?*' => function (Request $request) {
            return $request->method() === 'POST'
                ? Http::response(['orderId' => 7, 'clientOrderId' => 'k1-entry', 'status' => 'FILLED'])
                : Http::response(['orderId' => 7, 'status' => 'FILLED', 'updateTime' => 1794218460000]);
        },
        'spot.test/api/v3/myTrades*' => Http::response([['id' => 99, 'orderId' => 7, 'price' => '76000.10', 'qty' => '0.002', 'commission' => '0.000002', 'commissionAsset' => 'BTC', 'time' => 1794218460000, 'isMaker' => false]]),
    ]);
    $venue = binanceVenue();

    $ack = $venue->submit(OrderRequest::market(ClientOrderId::of('k1-entry'), Symbol::parse('BTC/USDT'), Side::Buy, Decimal::of('0.002')));
    $updates = $venue->updatesSince(0);
    $fill = $updates[0] instanceof OrderUpdate ? $updates[0]->fill : null;

    Http::assertSent(function (Request $r): bool {
        $q = queryOf($r);

        return $r->method() === 'POST' && $q['symbol'] === 'BTCUSDT' && $q['side'] === 'BUY' && $q['type'] === 'MARKET' && $q['quantity'] === '0.002'
            && $q['newClientOrderId'] === 'k1-entry' && $q['timestamp'] === '1794218400000' && $r->header('X-MBX-APIKEY') === [TEST_KEY]
            && $q['signature'] === SignedBinanceClient::sign(explode('&signature=', (string) parse_url($r->url(), PHP_URL_QUERY))[0], TEST_SECRET);
    });
    expect($ack->accepted)->toBeTrue()
        ->and($updates)->toHaveCount(2)
        ->and((string) $fill?->price)->toBe('76000.1')
        ->and((string) $fill?->fee)->toBe('0.000002 BTC')
        ->and($updates[1] instanceof OrderUpdate ? $updates[1]->status : null)->toBe(OrderStatus::Filled)
        ->and($venue->updatesSince(2))->toBe([]);
});

it('protects a spot position with an exchange OCO: the target as limit maker above, the stop below', function (): void {
    Http::fake(['spot.test/*' => Http::response(['orderListId' => 1])]);

    binanceVenue()->submitOco(new OcoRequest(ClientOrderId::of('k1-stop'), ClientOrderId::of('k1-target'), Symbol::parse('BTC/USDT'), Side::Sell, Decimal::of('0.002'), Price::of('75000'), Price::of('78000')));

    Http::assertSent(fn (Request $r): bool => str_contains($r->url(), '/api/v3/orderList/oco') && array_intersect_key(queryOf($r), array_flip(['aboveType', 'abovePrice', 'aboveClientOrderId', 'belowType', 'belowStopPrice', 'belowClientOrderId'])) === [
        'aboveType' => 'LIMIT_MAKER', 'abovePrice' => '78000', 'aboveClientOrderId' => 'k1-target', 'belowType' => 'STOP_LOSS', 'belowStopPrice' => '75000', 'belowClientOrderId' => 'k1-stop',
    ]);
});

it('rejects an order the exchange refuses, halts on a bad key, and never shows the key or the secret', function (): void {
    Http::fake(['spot.test/api/v3/order*' => Http::sequence()
        ->push(['code' => -2010, 'msg' => 'Account has insufficient balance for requested action.'], 400)
        ->push(['code' => -2015, 'msg' => 'Invalid API-key, IP, or permissions for action.'], 401)
        ->push('down', 503)]);
    $venue = binanceVenue();
    $order = fn (string $id): OrderRequest => OrderRequest::market(ClientOrderId::of($id), Symbol::parse('BTC/USDT'), Side::Buy, Decimal::of('1'));
    $ack = $venue->submit($order('a'));
    $messages = [$ack->reason];

    foreach (['b', 'c'] as $id) {
        try {
            $venue->submit($order($id));
        } catch (BinanceApiError|BinanceUnreachable $failure) {
            $messages[] = $failure::class.': '.$failure->getMessage();
        }
    }

    expect($ack->accepted)->toBeFalse()
        ->and($messages)->toBe([
            'Binance 400 (-2010): Account has insufficient balance for requested action.',
            BinanceApiError::class.': Binance 401 (-2015): Invalid API-key, IP, or permissions for action.',
            BinanceUnreachable::class.': Binance answered 503 to POST /api/v3/order.',
        ])
        ->and(str_contains(implode(' ', $messages), TEST_KEY) || str_contains(implode(' ', $messages), TEST_SECRET))->toBeFalse();
});

it('reports only the account\'s own assets, and its isolated perpetual positions', function (): void {
    Http::fake([
        'spot.test/api/v3/account*' => Http::response(['balances' => [
            ['asset' => 'BTC', 'free' => '1.00000000', 'locked' => '0.00000000'], ['asset' => 'BNB', 'free' => '1000', 'locked' => '0'],
            ['asset' => 'USDT', 'free' => '9000.5', 'locked' => '1000'],
        ]]),
        'fut.test/fapi/v2/positionRisk*' => Http::response([['symbol' => 'ETHUSDT', 'positionAmt' => '-0.500', 'entryPrice' => '3000.1', 'isolatedMargin' => '750.2', 'leverage' => '2']]),
        'fut.test/fapi/v2/balance*' => Http::response([['asset' => 'USDT', 'crossWalletBalance' => '4250.75']]),
    ]);
    $venue = binanceVenue('BTC/USDT', 'ETH/USDT:USDT');
    $position = $venue->positions()['ETH/USDT:USDT'];

    expect(array_keys($venue->balances()))->toBe(['BTC', 'USDT'])
        ->and((string) $venue->balances()['USDT']->total())->toBe('10000.5')
        ->and([$position->side, (string) $position->quantity, (string) $position->margin, $position->leverage])->toBe([Side::Sell, '0.5', '750.2', 2])
        ->and((string) $venue->futuresWallet())->toBe('4250.75');
});
