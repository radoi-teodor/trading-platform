<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\Refused;
use App\Agents\Mcp\SeriesWindow;
use App\Agents\Mcp\TradingTool;
use App\Market\Data\Liquidations;
use App\Market\LiquidationRepository;
use App\Support\Timeframe;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_liquidations')]
#[Description('Liquidations of a perpetual, counted and in quote notional: long positions closed by the exchange (long_count, long_notional) and short ones, by the minute as stored or summed over each longer step. Collected from the live stream only, so there is no history before the operator started it, and the numbers are a lower bound. incomplete_days are days of the window the stream was not heard for every minute.')]
#[IsReadOnly]
final class GetLiquidations extends TradingTool
{
    private const array STEPS = [Timeframe::OneMinute, Timeframe::FiveMinutes, Timeframe::FifteenMinutes, Timeframe::OneHour, Timeframe::FourHours, Timeframe::OneDay];

    private const string NOTE = 'A lower bound: the exchange streams at most one liquidation per symbol per second. A step without a row had none that was heard.';

    public function schema(JsonSchema $schema): array
    {
        return [
            'symbol' => $schema->string()->description('A perpetual, such as BTC/USDT:USDT')->required(),
            'from' => $schema->string()->description('Start, UTC, as 2026-11-09T00:00:00Z')->required(),
            'to' => $schema->string()->description('End, UTC, exclusive; at most 1000 rows after from')->required(),
            'every' => $schema->string()->description('The step of the rows: 1m (as stored, the default), 5m, 15m, 1h, 4h or 1d (sums)'),
        ];
    }

    public function handle(Request $request, LiquidationRepository $liquidations, BoundedRead $read): Response
    {
        return $this->bounded($read, function () use ($request, $liquidations): Response {
            $window = SeriesWindow::of($request->get('from'), $request->get('to'), $request->get('every'), self::STEPS);
            $perp = $this->perpetual($request->get('symbol'));

            if ($liquidations->heard($window->from, $window->last()) === 0) {
                throw new Refused('No liquidations were collected in that window: they come from the live stream only (market:stream perp), from the day it was started.');
            }

            $rows = $window->every === Timeframe::OneMinute
                ? $liquidations->minutes($perp, $window->from, $window->last())
                : $liquidations->sums($perp, $window->from, $window->last(), $window->every);

            return $this->json([
                'every' => $window->every,
                'liquidations' => array_map(fn (Liquidations $l): array => [
                    'time' => $l->openTime, 'long_count' => $l->longCount, 'long_notional' => $l->longNotional,
                    'short_count' => $l->shortCount, 'short_notional' => $l->shortNotional,
                ], $rows),
                'incomplete_days' => $liquidations->missingDays($window->from, $window->to),
                'note' => self::NOTE,
            ]);
        });
    }
}
