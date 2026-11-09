<?php

declare(strict_types=1);

namespace App\Simulation\Charts;

use App\Market\Contracts\CandleFeed;
use App\Market\Data\Candle;
use App\Market\Data\FundingRate;
use App\Market\Feed\CandleAggregator;
use App\Strategy\Data\PerpetualTracker;
use App\Strategy\Data\PerpetualView;
use App\Strategy\Dsl\Compiler\Compiler;
use App\Strategy\Dsl\Compiler\Frame;
use App\Strategy\Dsl\Compiler\Program;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Parser;
use App\Strategy\Dsl\Scope;
use App\Strategy\Dsl\TypeChecker;
use App\Strategy\Dsl\Types\Kind;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * Candles of any timeframe, built from the stored 1-minute candles, with DSL expressions evaluated on them by the
 * strategies' own compiler (D-53): what an agent or the chart reads is exactly what a backtest computes. Each
 * expression gets its warmup before the window starts.
 */
final readonly class ChartSeries
{
    public const int MAX_CANDLES = 1000;

    public const int MAX_EXPRESSIONS = 20;

    public function __construct(private CandleFeed $feed) {}

    /**
     * @param  list<string>  $expressions
     * @return array{candles: list<array{time: string, open: string, high: string, low: string, close: string, volume: string}>, series: array<string, list<?string>>, types: array<string, string>} types say what each expression measures, such as "a price series" or "a condition"
     */
    public function compute(Symbol $symbol, Timeframe $timeframe, CarbonImmutable $from, CarbonImmutable $to, array $expressions): array
    {
        if (count($expressions) > self::MAX_EXPRESSIONS) {
            throw new InvalidArgumentException('Ask for at most '.self::MAX_EXPRESSIONS.' expressions at a time.');
        }

        $from = $timeframe->openTimeContaining($from);

        if (intdiv($to->getTimestamp() - $from->getTimestamp(), $timeframe->seconds()) > self::MAX_CANDLES) {
            throw new InvalidArgumentException('A chart has at most '.self::MAX_CANDLES.' candles; ask for a shorter window or a longer timeframe.');
        }

        $scope = new Scope($timeframe, $symbol->isPerpetual());
        $compiler = new Compiler($scope, []);
        $program = new Program;
        $history = 0;
        $types = [];

        foreach (array_values($expressions) as $index => $expression) {
            $node = Parser::parse($expression);
            $type = (new TypeChecker)->check($node, $scope);

            if (! in_array($type->kind, [Kind::Series, Kind::Condition], true)) {
                throw new DslError($node->position(), "{$expression} is {$type->describe()}; a chart shows series and conditions.");
            }

            $types[$expression] = $type->describe();
            $compiler->add($program, "e{$index}", $node);
            $history = max($history, $compiler->history($node));
        }

        $timeframes = [$timeframe, ...array_map(fn (string $v): Timeframe => Timeframe::from($v), $program->timeframes())];
        usort($timeframes, fn (Timeframe $a, Timeframe $b): int => $b->seconds() <=> $a->seconds());
        $start = $timeframes[0]->openTimeContaining($from->subSeconds($history + $timeframes[0]->seconds()));
        $aggregators = [];

        foreach ($timeframes as $each) {
            $aggregators[$each->value] = new CandleAggregator($each);
        }

        $candles = [];
        $series = array_fill_keys(array_values($expressions), []);
        $funding = null;
        $perpetuals = new PerpetualTracker;

        foreach ($this->feed->minutes([$symbol], $start, $to) as $minute) {
            $candle = $minute->candles[(string) $symbol] ?? null;
            $funding = $minute->funding[(string) $symbol] ?? $funding;
            $perpetuals->absorb($minute);

            if ($candle === null) {
                continue;
            }

            $closed = array_filter(array_map(fn (CandleAggregator $aggregator): ?Candle => $aggregator->push($candle), $aggregators));

            foreach ($closed as $value => $bar) {
                if ($value !== $timeframe->value) {
                    $program->update((string) $value, $this->frame($bar, $funding, $symbol->isPerpetual() ? $perpetuals->view((string) $symbol, $bar) : null));
                }
            }

            $bar = $closed[$timeframe->value] ?? null;

            if ($bar === null) {
                continue;
            }

            $values = $program->evaluate($this->frame($bar, $funding, $symbol->isPerpetual() ? $perpetuals->view((string) $symbol, $bar) : null));

            if ($bar->openTime < $from) {
                continue;
            }

            $candles[] = [
                'time' => $bar->openTime->format('Y-m-d\TH:i:s\Z'), 'open' => (string) $bar->open, 'high' => (string) $bar->high,
                'low' => (string) $bar->low, 'close' => (string) $bar->close, 'volume' => (string) $bar->volume,
            ];

            foreach (array_values($expressions) as $index => $expression) {
                $series[$expression][] = self::text($values["e{$index}"] ?? null);
            }
        }

        return ['candles' => $candles, 'series' => $series, 'types' => $types];
    }

    private function frame(Candle $bar, ?FundingRate $funding, ?PerpetualView $perpetual): Frame
    {
        return new Frame($bar, $bar->endsAt(), null, $funding, null, $perpetual);
    }

    private static function text(mixed $value): ?string
    {
        return match (true) {
            $value instanceof Decimal => (string) $value,
            is_bool($value) => $value ? 'true' : 'false',
            is_string($value) => $value,
            default => null,
        };
    }
}
