<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\HeavyCalls;
use App\Agents\Mcp\Refused;
use App\Agents\Mcp\TradingTool;
use App\Market\ListingStudy;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_listing_stats')]
#[Description('What the USDT perpetuals listed in a window did after their listing, from daily stats: quote volume and return (close over the first open) after 1, 7 and 30 days, the return up to the last stored day, and max_drawdown. A 7 or 30 day value is null until every one of those days is stored. max_drawdown measures each low against the highest high of the days before it (or the day\'s own open), so a fall inside one day from that day\'s own high is not seen: the true fall is at least this deep. Delisted ones are included. Symbols whose stored days do not begin on their listing day are named in not_covered instead. Answers a few times a minute for a token.')]
#[IsReadOnly]
final class GetListingStats extends TradingTool
{
    public const int MAX_LISTINGS = 50;

    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()->description('The first listing day of the window, YYYY-MM-DD (UTC).')->required(),
            'to' => $schema->string()->description('The day the window ends before, YYYY-MM-DD (UTC). At most '.self::MAX_LISTINGS.' listings an answer.')->required(),
        ];
    }

    public function handle(Request $request, ListingStudy $study, HeavyCalls $calls, BoundedRead $read): Response
    {
        return $this->heavy($calls, $read, function () use ($request, $study): Response {
            $unreadable = 'from is before to; both are days, YYYY-MM-DD.';
            $from = self::day($request->get('from'), $unreadable);
            $to = self::day($request->get('to'), $unreadable);

            if (! $from->lessThan($to)) {
                throw new Refused($unreadable);
            }

            $count = $study->count($from, $to);

            if ($count === 0) {
                throw new Refused("No stored USDT perpetual was listed from {$from->toDateString()} to {$to->toDateString()}.");
            }

            if ($count > self::MAX_LISTINGS) {
                throw new Refused("That is {$count} listings; ask for at most ".self::MAX_LISTINGS.', with a shorter window.');
            }

            $number = fn (?string $value): ?Decimal => $value === null ? null : Decimal::of($value);
            $listings = [];
            $uncovered = [];

            foreach ($study->of($from, $to) as $row) {
                if ($row->first_age !== 0) {
                    $uncovered[] = $row->symbol;

                    continue;
                }

                $listings[] = [
                    'symbol' => $row->symbol,
                    'status' => $row->status,
                    'listed_at' => CarbonImmutable::parse($row->listed_at)->utc(),
                    'delisted_at' => $row->delisted_at === null ? null : CarbonImmutable::parse($row->delisted_at)->utc(),
                    'first_day' => $row->first_day,
                    'last_day' => $row->last_day,
                    'days' => $row->days,
                    'volume_1d' => $number($row->volume_1d),
                    'volume_7d' => $number($row->volume_7d),
                    'volume_30d' => $number($row->volume_30d),
                    'return_1d' => $number($row->return_1d),
                    'return_7d' => $number($row->return_7d),
                    'return_30d' => $number($row->return_30d),
                    'return_since_listing' => $number($row->return_since_listing),
                    'max_drawdown' => $number($row->max_drawdown),
                ];
            }

            return $this->json([
                'window' => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
                'listings' => $listings,
                'not_covered' => $uncovered,
            ]);
        });
    }
}
