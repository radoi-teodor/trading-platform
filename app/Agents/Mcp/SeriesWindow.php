<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Throwable;

/**
 * The window of a series an agent asks for (D-93): from $from to before $to, one row every $every, and never more
 * than MAX_ROWS rows, so an answer stays small enough to read.
 */
final readonly class SeriesWindow
{
    public const int MAX_ROWS = 1000;

    private function __construct(public CarbonImmutable $from, public CarbonImmutable $to, public Timeframe $every) {}

    /**
     * @param  non-empty-list<Timeframe>  $steps  the steps the series can be read in; the first when none is asked for
     */
    public static function of(mixed $from, mixed $to, mixed $every, array $steps): self
    {
        $start = self::time($from);
        $end = self::time($to);

        if (! $start->lessThan($end)) {
            throw new Refused('from is before to; both are times in UTC, such as 2026-09-01T00:00:00Z.');
        }

        $step = $every === null ? $steps[0] : Timeframe::tryFrom(is_string($every) ? $every : '');

        if ($step === null || ! in_array($step, $steps, true)) {
            throw new Refused('every must be one of '.implode(', ', array_map(fn (Timeframe $t): string => $t->value, $steps)).'.');
        }

        $rows = intdiv($end->getTimestamp() - $start->getTimestamp() + $step->seconds() - 1, $step->seconds());

        if ($rows > self::MAX_ROWS) {
            throw new Refused("That is {$rows} rows; ask for at most ".self::MAX_ROWS.', with a shorter window or a longer step in every.');
        }

        return new self($start, $end, $step);
    }

    /** The last moment inside the window, which ends before $to. */
    public function last(): CarbonImmutable
    {
        return $this->to->subSecond();
    }

    private static function time(mixed $value): CarbonImmutable
    {
        $unreadable = 'from and to are times in UTC, such as 2026-09-01T00:00:00Z.';

        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}/', $value) !== 1) {
            throw new Refused($unreadable);
        }

        try {
            return CarbonImmutable::parse($value, 'UTC')->utc();
        } catch (Throwable $failure) {
            throw new Refused($unreadable, previous: $failure);
        }
    }
}
