<?php

declare(strict_types=1);

namespace App\Simulation\Validation;

use App\Support\Number\Decimal;
use InvalidArgumentException;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * Monte Carlo of a strategy's results (docs/simulation.md, section 5; D-58): resamples of the trade sequence with
 * replacement, of the daily returns in blocks of 5 days, and runs that skip a random 10 % of the trades, from a
 * seeded Xoshiro256** so that every machine draws the same numbers. Each method gives the percentiles 5, 50 and 95
 * of the final return, the maximum drawdown and the longest losing streak.
 */
final class MonteCarlo
{
    public const int RESAMPLES = 2_000;

    public const int BLOCK_DAYS = 5;

    private const int SCALE = 18;

    /**
     * @param  list<Decimal>  $trades  net results of the trades in quote, in the order they closed
     * @param  list<Decimal>  $daily  daily returns as fractions
     * @return array<string, array<string, array{p5: Decimal, p50: Decimal, p95: Decimal}>> by method, then by measure
     */
    public static function run(array $trades, array $daily, Decimal $equity, int $seed, int $resamples = self::RESAMPLES): array
    {
        if (! $equity->isPositive() || $resamples < 1) {
            throw new InvalidArgumentException('Monte Carlo needs a positive equity and at least one resample.');
        }

        $random = new Randomizer(new Xoshiro256StarStar($seed));
        $result = [];

        if ($trades !== []) {
            $result['trades'] = self::summarize(self::repeat($resamples, fn (): array => self::tradePath(self::draw($random, $trades), $equity)));
            $skip = intdiv(count($trades), 10);
            $result['skip_10_percent'] = self::summarize(self::repeat($resamples, function () use ($random, $trades, $skip, $equity): array {
                $dropped = $skip < 1 ? [] : array_flip((array) $random->pickArrayKeys($trades, $skip));

                return self::tradePath(array_values(array_diff_key($trades, $dropped)), $equity);
            }));
        }

        if ($daily !== []) {
            $result['daily_blocks'] = self::summarize(self::repeat($resamples, fn (): array => self::dailyPath(self::blocks($random, $daily), $equity)));
        }

        return $result;
    }

    /**
     * @param  callable(): array{return: Decimal, drawdown: Decimal, streak: int}  $path
     * @return list<array{return: Decimal, drawdown: Decimal, streak: int}>
     */
    private static function repeat(int $times, callable $path): array
    {
        $paths = [];

        for ($i = 0; $i < $times; $i++) {
            $paths[] = $path();
        }

        return $paths;
    }

    /**
     * @param  list<Decimal>  $values
     * @return list<Decimal>
     */
    private static function draw(Randomizer $random, array $values): array
    {
        $last = count($values) - 1;
        $drawn = [];

        foreach ($values as $_) {
            $drawn[] = $values[$random->getInt(0, $last)];
        }

        return $drawn;
    }

    /**
     * Blocks of consecutive days, starting at random days, until the series is as long as the original.
     *
     * @param  list<Decimal>  $daily
     * @return list<Decimal>
     */
    private static function blocks(Randomizer $random, array $daily): array
    {
        $count = count($daily);
        $block = min(self::BLOCK_DAYS, $count);
        $drawn = [];

        while (count($drawn) < $count) {
            $start = $random->getInt(0, $count - $block);
            array_push($drawn, ...array_slice($daily, $start, $block));
        }

        return array_slice($drawn, 0, $count);
    }

    /**
     * @param  list<Decimal>  $trades
     * @return array{return: Decimal, drawdown: Decimal, streak: int}
     */
    private static function tradePath(array $trades, Decimal $equity): array
    {
        $balance = $equity;
        $peak = $equity;
        $drawdown = Decimal::zero();
        $streak = 0;
        $longest = 0;

        foreach ($trades as $net) {
            $balance = $balance->plus($net);
            [$peak, $drawdown] = self::mark($balance, $peak, $drawdown);
            $streak = $net->isNegative() ? $streak + 1 : 0;
            $longest = max($longest, $streak);
        }

        return ['return' => $balance->minus($equity)->dividedBy($equity, self::SCALE), 'drawdown' => $drawdown, 'streak' => $longest];
    }

    /**
     * @param  list<Decimal>  $daily
     * @return array{return: Decimal, drawdown: Decimal, streak: int}
     */
    private static function dailyPath(array $daily, Decimal $equity): array
    {
        $balance = $equity;
        $peak = $equity;
        $drawdown = Decimal::zero();
        $streak = 0;
        $longest = 0;

        foreach ($daily as $return) {
            $balance = $balance->times(Decimal::of(1)->plus($return))->truncatedTo(self::SCALE);
            [$peak, $drawdown] = self::mark($balance, $peak, $drawdown);
            $streak = $return->isNegative() ? $streak + 1 : 0;
            $longest = max($longest, $streak);
        }

        return ['return' => $balance->minus($equity)->dividedBy($equity, self::SCALE), 'drawdown' => $drawdown, 'streak' => $longest];
    }

    /** @return array{Decimal, Decimal} the new peak and the deepest drawdown so far */
    private static function mark(Decimal $balance, Decimal $peak, Decimal $drawdown): array
    {
        if ($balance->isGreaterThan($peak)) {
            return [$balance, $drawdown];
        }

        $depth = $peak->isPositive() ? $peak->minus($balance)->dividedBy($peak, self::SCALE) : Decimal::of(1);

        return [$peak, $depth->isGreaterThan($drawdown) ? $depth : $drawdown];
    }

    /**
     * @param  list<array{return: Decimal, drawdown: Decimal, streak: int}>  $paths
     * @return array<string, array{p5: Decimal, p50: Decimal, p95: Decimal}>
     */
    private static function summarize(array $paths): array
    {
        $summary = [];

        foreach (['return', 'drawdown', 'streak'] as $measure) {
            $values = array_map(fn (array $path): Decimal => $path[$measure] instanceof Decimal ? $path[$measure] : Decimal::of($path[$measure]), $paths);
            usort($values, fn (Decimal $a, Decimal $b): int => $a->compare($b));

            if ($values === []) {
                throw new InvalidArgumentException('Monte Carlo needs at least one path.');
            }

            $summary[$measure === 'streak' ? 'losing_streak' : $measure] = [
                'p5' => self::percentile($values, 5), 'p50' => self::percentile($values, 50), 'p95' => self::percentile($values, 95),
            ];
        }

        return $summary;
    }

    /**
     * The nearest-rank percentile of sorted values.
     *
     * @param  non-empty-list<Decimal>  $sorted
     */
    public static function percentile(array $sorted, int $percent): Decimal
    {
        $rank = max(1, intdiv($percent * count($sorted) + 99, 100));

        return $sorted[$rank - 1];
    }
}
