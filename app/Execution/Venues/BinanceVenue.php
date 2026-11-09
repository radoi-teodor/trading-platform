<?php

declare(strict_types=1);

namespace App\Execution\Venues;

use App\Execution\Contracts\Venue;
use App\Execution\Data\Balance;
use App\Execution\Data\ClientOrderId;
use App\Execution\Data\Income;
use App\Execution\Data\IncomeKind;
use App\Execution\Data\Liquidity;
use App\Execution\Data\OcoRequest;
use App\Execution\Data\OrderAck;
use App\Execution\Data\OrderRequest;
use App\Execution\Data\OrderStatus;
use App\Execution\Data\OrderType;
use App\Execution\Data\OrderUpdate;
use App\Execution\Data\VenueFill;
use App\Execution\Data\VenueOrder;
use App\Execution\Data\VenuePosition;
use App\Market\Binance\BinanceApiError;
use App\Market\Binance\SignedBinanceClient;
use App\Market\Data\MarketType;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use LogicException;

/**
 * Binance spot and USD-M behind the engine's Venue contract (D-69), for testnet now and live in phase 6. Spot
 * protection is an exchange OCO; a perpetual's is a reduce-only stop and target that the engine cancels in turn
 * (docs/engine.md, section 8). Fills are read when the engine asks for updates, at its next minute, from each working
 * order's trades: what a live engine sees is what happened, after it happened (docs/engine.md, section 1). Only the
 * account's own assets and instruments are reported, so the ledger reconciles with them and nothing else.
 */
final class BinanceVenue implements Venue
{
    /** @var array<string, array{symbol: Symbol, side: Side, type: OrderType, status: OrderStatus, ocoWith: ?string, trades: array<string, true>, quantity: ?Decimal, price: ?Price, stop: ?Price, filled: Decimal}> by client order id */
    private array $orders = [];

    /** @var list<OrderUpdate|Income> */
    private array $updates = [];

    private int $sequence = 0;

    private int $incomeFrom;

    /** @var array<string, true> */
    private array $isolated = [];

    /**
     * @param  list<Symbol>  $symbols  the instruments the account trades
     */
    public function __construct(private readonly SignedBinanceClient $client, private readonly array $symbols, private readonly Asset $quote, CarbonImmutable $since)
    {
        $this->incomeFrom = $since->getTimestamp() * 1000;
    }

    public function submit(OrderRequest $request): OrderAck
    {
        $perpetual = $request->symbol->isPerpetual();
        $params = [
            'symbol' => $request->symbol->venueSymbol(),
            'side' => $request->side === Side::Buy ? 'BUY' : 'SELL',
            'newClientOrderId' => $request->clientOrderId->value,
        ];
        $params += match ($request->type) {
            OrderType::Market => $request->quantity === null && $request->quoteQuantity !== null
                ? ['type' => 'MARKET', 'quoteOrderQty' => (string) $request->quoteQuantity]
                : ['type' => 'MARKET', 'quantity' => (string) $request->quantity],
            OrderType::Limit => ['type' => 'LIMIT', 'timeInForce' => 'GTC', 'quantity' => (string) $request->quantity, 'price' => (string) $request->price],
            OrderType::StopMarket => $perpetual
                ? ['type' => 'STOP_MARKET', 'quantity' => (string) $request->quantity, 'stopPrice' => (string) $request->stopPrice]
                : ['type' => 'STOP_LOSS', 'quantity' => (string) $request->quantity, 'stopPrice' => (string) $request->stopPrice],
            OrderType::TakeProfitMarket => ['type' => 'TAKE_PROFIT_MARKET', 'quantity' => (string) $request->quantity, 'stopPrice' => (string) $request->stopPrice],
        };

        if ($perpetual && $request->reduceOnly) {
            $params['reduceOnly'] = true;
        }

        try {
            $this->client->signed($perpetual ? MarketType::Perp : MarketType::Spot, 'POST', $perpetual ? '/fapi/v1/order' : '/api/v3/order', $params);
        } catch (BinanceApiError $error) {
            if ($error->isAuthentication()) {
                throw $error;
            }

            return OrderAck::rejected($request->clientOrderId, $error->getMessage());
        }

        $this->track($request->clientOrderId, $request->symbol, $request->side, $request->type, $request->quantity, $request->price, $request->stopPrice, null);

        return OrderAck::accepted($request->clientOrderId);
    }

    public function submitOco(OcoRequest $request): OrderAck
    {
        if ($request->symbol->isPerpetual()) {
            throw new LogicException('A perpetual is protected by a reduce-only stop and target, not an OCO (D-46).');
        }

        $selling = $request->side === Side::Sell;

        try {
            $this->client->signed(MarketType::Spot, 'POST', '/api/v3/orderList/oco', [
                'symbol' => $request->symbol->venueSymbol(),
                'side' => $selling ? 'SELL' : 'BUY',
                'quantity' => (string) $request->quantity,
                'aboveType' => $selling ? 'LIMIT_MAKER' : 'STOP_LOSS',
                'abovePrice' => (string) ($selling ? $request->targetPrice : $request->stopPrice),
                'aboveClientOrderId' => ($selling ? $request->targetId : $request->stopId)->value,
                'belowType' => $selling ? 'STOP_LOSS' : 'LIMIT_MAKER',
                'belowStopPrice' => (string) ($selling ? $request->stopPrice : $request->targetPrice),
                'belowClientOrderId' => ($selling ? $request->stopId : $request->targetId)->value,
            ]);
        } catch (BinanceApiError $error) {
            if ($error->isAuthentication()) {
                throw $error;
            }

            return OrderAck::rejected($request->stopId, $error->getMessage());
        }

        $this->track($request->stopId, $request->symbol, $request->side, OrderType::StopMarket, $request->quantity, null, $request->stopPrice, $request->targetId->value);
        $this->track($request->targetId, $request->symbol, $request->side, OrderType::Limit, $request->quantity, $request->targetPrice, null, $request->stopId->value);

        return OrderAck::accepted($request->stopId);
    }

    public function cancel(ClientOrderId $id): bool
    {
        $order = $this->orders[$id->value] ?? null;

        if ($order === null || ! $order['status']->isWorking()) {
            return false;
        }

        $perpetual = $order['symbol']->isPerpetual();

        try {
            $this->client->signed($perpetual ? MarketType::Perp : MarketType::Spot, 'DELETE', $perpetual ? '/fapi/v1/order' : '/api/v3/order', [
                'symbol' => $order['symbol']->venueSymbol(), 'origClientOrderId' => $id->value,
            ]);
        } catch (BinanceApiError $error) {
            // -2011: unknown order, already final on the exchange; the next update says how it ended.
            if ($error->binanceCode === -2011) {
                return false;
            }

            throw $error;
        }

        return true;
    }

    public function updatesSince(int $sequence): array
    {
        $this->poll();

        return array_values(array_filter($this->updates, fn (OrderUpdate|Income $u): bool => $u->sequence > $sequence));
    }

    public function openOrders(): array
    {
        $orders = [];

        foreach ($this->symbols as $symbol) {
            $perpetual = $symbol->isPerpetual();

            foreach ($this->client->signed($perpetual ? MarketType::Perp : MarketType::Spot, 'GET', $perpetual ? '/fapi/v1/openOrders' : '/api/v3/openOrders', ['symbol' => $symbol->venueSymbol()]) as $row) {
                $row = is_array($row) ? $row : [];
                $id = self::text($row, 'clientOrderId');
                $known = $this->orders[$id] ?? null;
                $orders[] = new VenueOrder(
                    ClientOrderId::of($id),
                    $symbol,
                    self::text($row, 'side') === 'BUY' ? Side::Buy : Side::Sell,
                    $known['type'] ?? OrderType::Limit,
                    Decimal::of(self::text($row, 'origQty')),
                    Decimal::of(self::text($row, 'executedQty')),
                    self::price($row, 'price'),
                    self::price($row, 'stopPrice'),
                    self::status(self::text($row, 'status')),
                    ($known['ocoWith'] ?? null) === null ? null : ClientOrderId::of($known['ocoWith']),
                );
            }
        }

        return $orders;
    }

    public function balances(): array
    {
        $assets = [$this->quote->code => true];

        foreach ($this->symbols as $symbol) {
            if (! $symbol->isPerpetual()) {
                $assets[$symbol->base->code] = true;
            }
        }

        $balances = [];

        $account = $this->client->signed(MarketType::Spot, 'GET', '/api/v3/account');

        foreach (is_array($account['balances'] ?? null) ? $account['balances'] : [] as $row) {
            $row = is_array($row) ? $row : [];
            $code = self::text($row, 'asset');

            if (isset($assets[$code])) {
                $balances[$code] = new Balance(Asset::of($code), Decimal::of(self::text($row, 'free')), Decimal::of(self::text($row, 'locked')));
            }
        }

        ksort($balances, SORT_STRING);

        return $balances;
    }

    public function futuresWallet(): Decimal
    {
        if (array_filter($this->symbols, fn (Symbol $s): bool => $s->isPerpetual()) === []) {
            return Decimal::zero();
        }

        foreach ($this->client->signed(MarketType::Perp, 'GET', '/fapi/v2/balance') as $row) {
            if (is_array($row) && ($row['asset'] ?? null) === $this->quote->code) {
                return Decimal::of(self::text($row, 'crossWalletBalance'));
            }
        }

        return Decimal::zero();
    }

    public function positions(): array
    {
        $positions = [];

        foreach ($this->symbols as $symbol) {
            if (! $symbol->isPerpetual()) {
                continue;
            }

            foreach ($this->client->signed(MarketType::Perp, 'GET', '/fapi/v2/positionRisk', ['symbol' => $symbol->venueSymbol()]) as $row) {
                $row = is_array($row) ? $row : [];
                $amount = Decimal::of(self::text($row, 'positionAmt'));

                if ($amount->isZero()) {
                    continue;
                }

                $positions[(string) $symbol] = new VenuePosition(
                    $symbol, $amount->isNegative() ? Side::Sell : Side::Buy, $amount->abs(), Decimal::of(self::text($row, 'entryPrice')),
                    Decimal::of(self::text($row, 'isolatedMargin')), (int) self::text($row, 'leverage'),
                );
            }
        }

        ksort($positions, SORT_STRING);

        return $positions;
    }

    public function setLeverage(Symbol $symbol, int $leverage): void
    {
        if (! isset($this->isolated[(string) $symbol])) {
            try {
                $this->client->signed(MarketType::Perp, 'POST', '/fapi/v1/marginType', ['symbol' => $symbol->venueSymbol(), 'marginType' => 'ISOLATED']);
            } catch (BinanceApiError $error) {
                // -4046: the margin type is already isolated.
                if ($error->binanceCode !== -4046) {
                    throw $error;
                }
            }

            $this->isolated[(string) $symbol] = true;
        }

        $this->client->signed(MarketType::Perp, 'POST', '/fapi/v1/leverage', ['symbol' => $symbol->venueSymbol(), 'leverage' => $leverage]);
    }

    /** Reads every working order's trades and status, and the perpetuals' funding, into numbered updates. */
    private function poll(): void
    {
        foreach ($this->orders as $id => $order) {
            if (! $order['status']->isWorking()) {
                continue;
            }

            $perpetual = $order['symbol']->isPerpetual();
            $market = $perpetual ? MarketType::Perp : MarketType::Spot;
            $state = $this->client->signed($market, 'GET', $perpetual ? '/fapi/v1/order' : '/api/v3/order', ['symbol' => $order['symbol']->venueSymbol(), 'origClientOrderId' => $id]);
            $status = self::status(self::text($state, 'status'));
            $orderId = self::text($state, 'orderId');
            $trades = $this->client->signed($market, 'GET', $perpetual ? '/fapi/v1/userTrades' : '/api/v3/myTrades', ['symbol' => $order['symbol']->venueSymbol(), 'orderId' => $orderId]);

            foreach ($trades as $trade) {
                $trade = is_array($trade) ? $trade : [];
                $tradeId = self::text($trade, 'id');

                if (isset($order['trades'][$tradeId])) {
                    continue;
                }

                $this->orders[$id]['trades'][$tradeId] = true;
                $quantity = Decimal::of(self::text($trade, 'qty'));
                $this->orders[$id]['filled'] = $this->orders[$id]['filled']->plus($quantity);
                $time = CarbonImmutable::createFromTimestampMsUTC((int) self::text($trade, 'time'));
                $this->updates[] = new OrderUpdate(++$this->sequence, ClientOrderId::of($id), $status === OrderStatus::Filled ? OrderStatus::PartiallyFilled : $status, $time, new VenueFill(
                    "{$order['symbol']->venueSymbol()}-{$tradeId}", ClientOrderId::of($id), $order['symbol'], $order['side'], $quantity,
                    Price::of(self::text($trade, 'price')),
                    Money::of(self::text($trade, 'commission'), Asset::of(self::text($trade, 'commissionAsset'))),
                    ($trade['isMaker'] ?? $trade['maker'] ?? false) === true ? Liquidity::Maker : Liquidity::Taker,
                    Decimal::zero(),
                    $time,
                ));
            }

            if ($status !== $order['status']) {
                $this->orders[$id]['status'] = $status;
                $time = CarbonImmutable::createFromTimestampMsUTC((int) (self::text($state, 'updateTime') ?: '0'));
                $this->updates[] = new OrderUpdate(++$this->sequence, ClientOrderId::of($id), $status, $time);
            }
        }

        if (array_filter($this->symbols, fn (Symbol $s): bool => $s->isPerpetual()) === []) {
            return;
        }

        foreach ($this->client->signed(MarketType::Perp, 'GET', '/fapi/v1/income', ['incomeType' => 'FUNDING_FEE', 'startTime' => $this->incomeFrom]) as $row) {
            $row = is_array($row) ? $row : [];
            $time = (int) self::text($row, 'time');
            $symbol = array_values(array_filter($this->symbols, fn (Symbol $s): bool => $s->isPerpetual() && $s->venueSymbol() === self::text($row, 'symbol')))[0] ?? null;

            if ($symbol === null) {
                continue;
            }

            $this->updates[] = new Income(++$this->sequence, 'funding-'.self::text($row, 'tranId'), IncomeKind::Funding, $symbol, Decimal::of(self::text($row, 'income')), null, CarbonImmutable::createFromTimestampMsUTC($time));
            $this->incomeFrom = max($this->incomeFrom, $time + 1);
        }
    }

    private function track(ClientOrderId $id, Symbol $symbol, Side $side, OrderType $type, ?Decimal $quantity, ?Price $price, ?Price $stop, ?string $ocoWith): void
    {
        $this->orders[$id->value] = [
            'symbol' => $symbol, 'side' => $side, 'type' => $type, 'status' => OrderStatus::New, 'ocoWith' => $ocoWith, 'trades' => [],
            'quantity' => $quantity, 'price' => $price, 'stop' => $stop, 'filled' => Decimal::zero(),
        ];
    }

    private static function status(string $status): OrderStatus
    {
        return match ($status) {
            'NEW' => OrderStatus::New,
            'PARTIALLY_FILLED' => OrderStatus::PartiallyFilled,
            'FILLED' => OrderStatus::Filled,
            'CANCELED', 'PENDING_CANCEL', 'REJECTED' => OrderStatus::Cancelled,
            default => OrderStatus::Expired,
        };
    }

    /** @param array<array-key, mixed> $row */
    private static function text(array $row, string $key): string
    {
        $value = $row[$key] ?? '';

        return is_scalar($value) ? (string) $value : '';
    }

    /** @param array<array-key, mixed> $row */
    private static function price(array $row, string $key): ?Price
    {
        $value = self::text($row, $key);

        return $value === '' || Decimal::of($value)->isZero() ? null : Price::of($value);
    }
}
