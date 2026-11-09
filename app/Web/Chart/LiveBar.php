<?php

declare(strict_types=1);

namespace App\Web\Chart;

use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\FormingCandles;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * What the live chart needs every second (D-87): whether a candle of its timeframe has closed since it was computed,
 * so the screen recomputes candles and layers, and otherwise the bar still forming, made of the period's stored
 * minutes and the stream's forming minute. Layers get no value on that bar, as a strategy sees them.
 */
final readonly class LiveBar
{
    /** A forming candle older than this means market:stream is not writing. */
    public const int LIVE_SECONDS = 5;

    public function __construct(private CandleRepository $candles, private FormingCandles $forming, private Clock $clock) {}

    /**
     * @param  string  $through  the last stored 1-minute open time the chart was computed through (ISO 8601)
     * @return array{closed: bool, bar: array{time: string, open: string, high: string, low: string, close: string, volume: string}|null, status: string}
     */
    public function read(string $symbol, string $timeframe, string $through): array
    {
        $frame = Timeframe::tryFrom($timeframe) ?? throw new InvalidArgumentException('Unknown timeframe.');
        $instrument = self::instrument(Symbol::parse($symbol));
        $latest = $instrument === null ? null : $this->candles->latestOpenTime($instrument, Timeframe::OneMinute);

        if ($instrument === null || $latest === null) {
            return ['closed' => false, 'bar' => null, 'status' => self::notLive()];
        }

        $period = $frame->openTimeContaining($latest->addMinute());
        $computed = CarbonImmutable::parse($through)->utc();

        if ($period->greaterThan($frame->openTimeContaining($computed->addMinute()))) {
            return ['closed' => true, 'bar' => null, 'status' => ''];
        }

        $live = $this->forming->latest($instrument);
        $age = $live === null ? null : (int) $live->receivedAt->diffInSeconds($this->clock->now(), true);
        $minutes = $latest->greaterThanOrEqualTo($period) ? $this->candles->range($instrument, Timeframe::OneMinute, $period, $latest) : [];

        if ($live !== null && $live->candle->openTime->greaterThan($latest) && $live->candle->openTime->lessThan($period->addSeconds($frame->seconds()))) {
            $minutes[] = $live->candle;
        }

        return [
            'closed' => false,
            'bar' => self::partial($period, $minutes),
            'status' => $age !== null && $age <= self::LIVE_SECONDS ? "Live, {$age} s ago" : self::notLive(),
        ];
    }

    private static function notLive(): string
    {
        return 'Not live: market:stream is not running (start-local.ps1 -Stream)';
    }

    private static function instrument(Symbol $symbol): ?Instrument
    {
        return Instrument::query()->where('symbol', (string) $symbol)->where('market', $symbol->isPerpetual() ? 'perp' : 'spot')->first();
    }

    /**
     * @param  list<Candle>  $minutes  oldest first
     * @return array{time: string, open: string, high: string, low: string, close: string, volume: string}|null
     */
    private static function partial(CarbonImmutable $period, array $minutes): ?array
    {
        if ($minutes === []) {
            return null;
        }

        $high = $minutes[0]->high->value;
        $low = $minutes[0]->low->value;
        $volume = Decimal::zero();

        foreach ($minutes as $minute) {
            $high = $minute->high->value->isGreaterThan($high) ? $minute->high->value : $high;
            $low = $minute->low->value->isLessThan($low) ? $minute->low->value : $low;
            $volume = $volume->plus($minute->volume);
        }

        return [
            'time' => $period->format('Y-m-d\TH:i:s\Z'), 'open' => (string) $minutes[0]->open, 'high' => (string) $high,
            'low' => (string) $low, 'close' => (string) $minutes[count($minutes) - 1]->close, 'volume' => (string) $volume,
        ];
    }
}
