<?php

declare(strict_types=1);

namespace App\Strategy\Indicators;

use App\Support\Number\Decimal;
use InvalidArgumentException;

/** MACD: line = EMA(fast) - EMA(slow); signal = EMA(signal) of the line; hist = line - signal. */
final class Macd
{
    private Ema $fast;

    private Ema $slow;

    private Ema $signal;

    public function __construct(int $fast, int $slow, int $signal)
    {
        if ($fast >= $slow) {
            throw new InvalidArgumentException("The fast period of a MACD must be shorter than the slow one, got {$fast} and {$slow}.");
        }

        $this->fast = new Ema($fast);
        $this->slow = new Ema($slow);
        $this->signal = new Ema($signal);
    }

    /** @return array{line: ?Decimal, signal: ?Decimal, hist: ?Decimal} */
    public function push(Decimal $value): array
    {
        $fast = $this->fast->push($value);
        $slow = $this->slow->push($value);
        $line = $fast === null || $slow === null ? null : $fast->minus($slow);
        $signal = $line === null ? null : $this->signal->push($line);

        return ['line' => $line, 'signal' => $signal, 'hist' => $line === null || $signal === null ? null : $line->minus($signal)];
    }
}
