<?php

declare(strict_types=1);

use App\Execution\Data\ClientOrderId;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderUpdate;
use App\Execution\Venues\BinanceVenue;
use App\Market\Binance\SignedBinanceClient;
use App\Market\Data\MarketType;
use App\Support\Asset;
use App\Support\Clock\WallClock;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;

/*
 * The opt-in testnet suite (composer test:testnet): real orders on Binance's spot test network, with test funds only.
 * It reads a TESTNET key from TRADING_TESTNET_KEY and TRADING_TESTNET_SECRET in the shell that runs it; testnet keys
 * carry no money, and live keys are never read this way (docs/security.md, section 2).
 */

it('buys a little BTC, protects it with an OCO on the exchange, cancels it, and every fill reconciles', function (): void {
    $client = new SignedBinanceClient(app(Factory::class), new WallClock, 'https://testnet.binance.vision', 'https://testnet.binancefuture.com', (string) getenv('TRADING_TESTNET_KEY'), (string) getenv('TRADING_TESTNET_SECRET'));
    $symbol = Symbol::parse('BTC/USDT');
    $venue = new BinanceVenue($client, [$symbol], Asset::of('USDT'), CarbonImmutable::now('UTC'));
    $before = $venue->balances();
    $tag = substr(md5((string) microtime(true)), 0, 8);

    expect(abs($client->serverTime(MarketType::Spot)->getTimestampMs() - CarbonImmutable::now('UTC')->getTimestampMs()))->toBeLessThan(1000);

    $buy = $venue->submit(OrderRequest::market(ClientOrderId::of("tx-{$tag}-in"), $symbol, Side::Buy, Decimal::of('0.0002')));
    $fills = array_values(array_filter($venue->updatesSince(0), fn ($u): bool => $u instanceof OrderUpdate && $u->fill !== null));
    $bought = Decimal::zero();

    foreach ($fills as $update) {
        $fill = $update->fill;
        $bought = $fill === null ? $bought : $bought->plus($fill->quantity)->minus($fill->fee->asset->code === 'BTC' ? $fill->fee->amount : Decimal::zero());
    }

    $price = $fills[0]->fill->price->value ?? Decimal::zero();
    $oco = $venue->submitOco(new OcoRequest(
        ClientOrderId::of("tx-{$tag}-st"), ClientOrderId::of("tx-{$tag}-tp"), $symbol, Side::Sell, $bought->roundDownToMultipleOf(Decimal::of('0.00001')),
        Price::of((string) $price->times(Decimal::of('0.9'))->roundDownToMultipleOf(Decimal::of('0.01'))), Price::of((string) $price->times(Decimal::of('1.1'))->roundDownToMultipleOf(Decimal::of('0.01'))),
    ));

    expect($buy->accepted)->toBeTrue()
        ->and($fills)->not->toBe([])
        ->and($oco->accepted)->toBeTrue()
        ->and(count($venue->openOrders()))->toBe(2)
        ->and($venue->cancel(ClientOrderId::of("tx-{$tag}-st")))->toBeTrue()
        ->and($venue->openOrders())->toBe([])
        ->and($venue->balances()['BTC']->total()->isGreaterThan($before['BTC']->total()))->toBeTrue();
})->group('testnet')->skip(
    getenv('TRADING_TESTNET') !== '1' || ! is_string(getenv('TRADING_TESTNET_KEY')) || ! is_string(getenv('TRADING_TESTNET_SECRET')),
    'Set TRADING_TESTNET=1, TRADING_TESTNET_KEY and TRADING_TESTNET_SECRET to run the testnet suite.',
);
