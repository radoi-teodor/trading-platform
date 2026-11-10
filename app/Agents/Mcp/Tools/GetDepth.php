<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\Refused;
use App\Agents\Mcp\SeriesWindow;
use App\Agents\Mcp\TradingTool;
use App\Market\Data\BookDepth;
use App\Market\ScalpingRepository;
use App\Support\Timeframe;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_depth')]
#[Description('Order book depth of a perpetual: the quote notional resting within 1, 2 and 5 % below the price (bid_1, bid_2, bid_5) and above it (ask_1, ask_2, ask_5), one row per 5 minutes as stored, or the average of the rows of each longer step. The same numbers bid_depth(pct), ask_depth(pct) and depth_imbalance(pct) read in a strategy. missing_days are days of the window with no row.')]
#[IsReadOnly]
final class GetDepth extends TradingTool
{
    private const array STEPS = [Timeframe::FiveMinutes, Timeframe::FifteenMinutes, Timeframe::OneHour, Timeframe::FourHours, Timeframe::OneDay];

    public function schema(JsonSchema $schema): array
    {
        return [
            'symbol' => $schema->string()->description('A perpetual, such as BTC/USDT:USDT')->required(),
            'from' => $schema->string()->description('Start, UTC, as 2026-09-01T00:00:00Z')->required(),
            'to' => $schema->string()->description('End, UTC, exclusive; at most 1000 rows after from')->required(),
            'every' => $schema->string()->description('The step of the rows: 5m (as stored, the default), 15m, 1h, 4h or 1d (averages)'),
        ];
    }

    public function handle(Request $request, ScalpingRepository $scalping, BoundedRead $read): Response
    {
        return $this->bounded($read, function () use ($request, $scalping): Response {
            $window = SeriesWindow::of($request->get('from'), $request->get('to'), $request->get('every'), self::STEPS);
            $perp = $this->perpetual($request->get('symbol'));
            $rows = $window->every === Timeframe::FiveMinutes
                ? $scalping->depth($perp, $window->from, $window->last())
                : $scalping->depthAverages($perp, $window->from, $window->last(), $window->every);

            if ($rows === []) {
                throw new Refused("No depth is stored for {$perp->symbol} in that window; the operator imports it (market:backfill --depth).");
            }

            return $this->json([
                'every' => $window->every,
                'depth' => array_map(fn (BookDepth $d): array => [
                    'time' => $d->time, 'bid_1' => $d->bid1, 'bid_2' => $d->bid2, 'bid_5' => $d->bid5, 'ask_1' => $d->ask1, 'ask_2' => $d->ask2, 'ask_5' => $d->ask5,
                ], $rows),
                'missing_days' => $scalping->missingDays('book_depth', $perp, $window->from, $window->last()),
            ]);
        });
    }
}
