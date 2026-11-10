<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\Refused;
use App\Agents\Mcp\TradingTool;
use App\Market\CandleRepository;
use App\Market\Instruments\ResearchUniverse;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list_universe')]
#[Description('The research universe in the month of a day: the USDT perpetuals ranked top N by 30-day quote volume on the first day of that month, as they were ranked then (ones delisted since are still listed), each with its listing and delisting time and the first and last 1-minute candle stored. A universe strategy trades these members.')]
#[IsReadOnly]
final class ListUniverse extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema->string()->description('A day, YYYY-MM-DD; the answer is for its month. Without it, the last month built.'),
        ];
    }

    public function handle(Request $request, ResearchUniverse $universe, CandleRepository $candles, BoundedRead $read): Response
    {
        return $this->bounded($read, function () use ($request, $universe, $candles): Response {
            $built = $universe->built() ?? throw new Refused('The research universe is not built yet; the operator builds it (market:universe:build).');
            $date = $request->get('date');
            $month = $date === null ? CarbonImmutable::parse("{$built['to']}-01", 'UTC') : self::day($date, 'date is a day, YYYY-MM-DD.')->startOfMonth();
            $key = $month->format('Y-m');

            if ($key < $built['from'] || $key > $built['to']) {
                throw new Refused("The universe is built from {$built['from']} to {$built['to']}; {$key} is outside it.");
            }

            $members = $universe->membersOf($month);
            $stored = $candles->coverage(array_map(fn (object $member): int => $member->instrument_id, $members));
            $time = fn (?string $value): ?CarbonImmutable => $value === null ? null : CarbonImmutable::parse($value)->utc();

            return $this->json([
                'universe' => ['rule' => 'top', 'n' => $built['top'], 'by' => 'quote_volume_30d', 'from' => $built['from'], 'to' => $built['to']],
                'month' => $key,
                'members' => array_map(fn (object $member): array => [
                    'rank' => $member->rank,
                    'symbol' => $member->symbol,
                    'status' => $member->status,
                    'listed_at' => $time($member->listed_at),
                    'delisted_at' => $time($member->delisted_at),
                    'quote_volume_30d' => Decimal::of($member->quote_volume_30d),
                    'first_minute' => $stored[$member->instrument_id][0] ?? null,
                    'last_minute' => $stored[$member->instrument_id][1] ?? null,
                ], $members),
            ]);
        });
    }
}
