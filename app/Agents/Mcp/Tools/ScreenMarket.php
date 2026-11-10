<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\HeavyCalls;
use App\Agents\Mcp\Refused;
use App\Agents\Mcp\TradingTool;
use App\Market\Data\ScreenMeasure;
use App\Market\Instruments\ResearchUniverse;
use App\Market\MarketScreen;
use App\Support\Number\Decimal;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('screen_market')]
#[Description('Ranks USDT perpetuals by what they did over the days before a date, from daily stats: return (last close over first open), quote volume (summed), funding (average daily rate), open interest change (last day over first) or volatility (average daily range over the open). It reads only the days before the date, so a past date gives what was known then. An instrument without the measure is left out. Answers a few times a minute for a token.')]
#[IsReadOnly]
final class ScreenMarket extends TradingTool
{
    public const int MAX_WINDOW_DAYS = 365;

    public const int MAX_ROWS = 50;

    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema->string()->description('A day, YYYY-MM-DD: the screen reads the days before it, never the day itself. Without it, the day after the last one stored.'),
            'window_days' => $schema->integer()->description('How many days before the date are read, 1 to '.self::MAX_WINDOW_DAYS.'; 30 without it.'),
            'by' => $schema->string()->enum(array_column(ScreenMeasure::cases(), 'value'))->description('What ranks them; return without it.'),
            'scope' => $schema->string()->enum(['universe', 'all'])->description('universe (the default): the members of the research universe in the month of the date. all: every USDT crypto perpetual with daily stats, delisted ones included.'),
            'order' => $schema->string()->enum(['desc', 'asc'])->description('desc (the default) puts the highest first.'),
            'limit' => $schema->integer()->description('How many rows, 1 to '.self::MAX_ROWS.'; 20 without it.'),
            'whole_window' => $schema->boolean()->description('true (the default) ranks only instruments with every day of the window stored; false also ranks ones listed or delisted inside it, over the days they have.'),
        ];
    }

    public function handle(Request $request, MarketScreen $screen, ResearchUniverse $universe, HeavyCalls $calls, BoundedRead $read): Response
    {
        return $this->heavy($calls, $read, function () use ($request, $screen, $universe): Response {
            $by = ScreenMeasure::tryFrom(self::word($request->get('by'), 'return'))
                ?? throw new Refused('by must be one of '.implode(', ', array_column(ScreenMeasure::cases(), 'value')).'.');
            $days = self::whole($request->get('window_days'), 30, self::MAX_WINDOW_DAYS, 'window_days');
            $limit = self::whole($request->get('limit'), 20, self::MAX_ROWS, 'limit');
            $scope = self::word($request->get('scope'), 'universe');
            $order = self::word($request->get('order'), 'desc');
            $whole = $request->get('whole_window') ?? true;
            $asked = $request->get('date');

            if (! in_array($scope, ['universe', 'all'], true)) {
                throw new Refused('scope must be universe or all.');
            }

            if (! in_array($order, ['desc', 'asc'], true)) {
                throw new Refused('order must be desc or asc.');
            }

            if (! is_bool($whole)) {
                throw new Refused('whole_window is true or false.');
            }

            $asked = $asked === null ? null : self::day($asked, 'date is a day, YYYY-MM-DD.');
            $stored = $screen->lastDay() ?? throw new Refused('No daily stats are stored; the operator fills them (market:daily).');
            $date = $asked ?? $stored->addDay();

            if ($date->greaterThan($stored->addDay())) {
                throw new Refused("Daily stats are stored up to {$stored->toDateString()}; date is at most {$stored->addDay()->toDateString()}.");
            }

            $members = null;

            if ($scope === 'universe') {
                $members = array_map(fn (object $member): int => $member->instrument_id, $universe->membersOf($date->startOfMonth()));

                if ($members === []) {
                    throw new Refused("The universe is not built for {$date->format('Y-m')}; ask with scope all, or the operator builds it (market:universe:build).");
                }
            }

            $last = $date->subDay();
            $from = $date->subDays($days);
            $rows = $screen->rank($members, $last, $days, $by, $order === 'desc', $limit, $whole);

            if ($rows === []) {
                throw new Refused("Nothing to rank by {$by->value} from {$from->toDateString()} to {$last->toDateString()}: no instrument of that scope has the daily stats for it. Try whole_window false or another window; the operator fills daily stats (market:daily).");
            }

            $number = fn (?string $value): ?Decimal => $value === null ? null : Decimal::of($value);

            return $this->json([
                'screen' => [
                    'by' => $by->value, 'date' => $date->toDateString(), 'from' => $from->toDateString(), 'to' => $last->toDateString(),
                    'window_days' => $days, 'scope' => $scope, 'order' => $order, 'whole_window' => $whole,
                ],
                'rows' => array_map(fn (object $row, int $index): array => [
                    'rank' => $index + 1,
                    'symbol' => $row->symbol,
                    'days' => $row->days,
                    'return' => $number($row->price_return),
                    'quote_volume' => Decimal::of($row->quote_volume),
                    'funding' => $number($row->funding),
                    'open_interest_change' => $number($row->open_interest_change),
                    'volatility' => $number($row->volatility),
                ], $rows, array_keys($rows)),
            ]);
        });
    }

    private static function word(mixed $value, string $default): string
    {
        return match (true) {
            $value === null => $default,
            is_string($value) => $value,
            default => '',
        };
    }

    private static function whole(mixed $value, int $default, int $most, string $name): int
    {
        $value ??= $default;

        return is_int($value) && $value >= 1 && $value <= $most ? $value : throw new Refused("{$name} is a whole number from 1 to {$most}.");
    }
}
