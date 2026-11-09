<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Market\Models\Instrument;
use App\Simulation\Charts\ChartSeries;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Functions\FunctionSpec;
use App\Strategy\Dsl\Functions\Group;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Dsl\Functions\ParamKind;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * The chart (Phase 4, first screen): candles of a stored instrument with layers picked from the DSL's library, each
 * with its own parameters and as many as wanted, computed by the chart service with the backtest's formulas; price
 * layers over the candles, the others in panels below, conditions as markers, and the volume profile of the window.
 */
#[Layout('layouts.app')]
#[Title('Chart')]
final class ChartScreen extends Component
{
    public const int PROFILE_BINS = 24;

    public string $symbol = 'BTC/USDT';

    public string $timeframe = '1h';

    public int $candles = 300;

    /** @var list<string> */
    public array $layers = ['ema(9)', 'ema(21)'];

    public string $function = 'ema';

    public string $expression = '';

    public bool $table = false;

    public string $error = '';

    public function add(): void
    {
        $text = trim($this->expression) !== '' ? trim($this->expression) : self::template(Library::get($this->function));

        if ($text !== '' && ! in_array($text, $this->layers, true)) {
            $this->layers = [...$this->layers, $text];
        }

        $this->expression = '';
    }

    public function remove(int $index): void
    {
        $this->layers = array_values(array_filter($this->layers, fn (int $i): bool => $i !== $index, ARRAY_FILTER_USE_KEY));
    }

    /** "ema(20)", "bb(20, 2).upper": the function with its required arguments filled in. */
    public static function template(?FunctionSpec $spec): string
    {
        if ($spec === null) {
            return '';
        }

        if (! $spec->isCall) {
            return $spec->name;
        }

        $arguments = [];

        foreach ($spec->params as $param) {
            if ($param->default === null) {
                $arguments[] = match ($param->kind) {
                    ParamKind::Series => 'close',
                    default => '20',
                };
            }
        }

        $field = $spec->fields === [] ? '' : '.'.array_key_first($spec->fields);

        return "{$spec->name}(".implode(', ', $arguments).")$field";
    }

    public function render(ChartSeries $chart, ConnectionInterface $db): View
    {
        $payload = ['candles' => [], 'series' => [], 'types' => [], 'profile' => []];
        $this->error = '';

        try {
            $timeframe = Timeframe::tryFrom($this->timeframe) ?? throw new InvalidArgumentException('Pick a timeframe.');
            $symbol = Symbol::parse($this->symbol);
            $last = $db->table('candles')->join('instruments', 'instruments.id', '=', 'candles.instrument_id')
                ->where('instruments.symbol', (string) $symbol)->where('instruments.market', $symbol->isPerpetual() ? 'perp' : 'spot')
                ->where('candles.timeframe', '1m')->max('candles.open_time');
            $to = is_string($last) ? $timeframe->openTimeContaining(CarbonImmutable::parse($last, 'UTC')->addMinute()) : throw new InvalidArgumentException("{$symbol} has no stored candles.");
            $count = max(20, min(1000, $this->candles));
            $payload = $chart->compute($symbol, $timeframe, $to->subSeconds($count * $timeframe->seconds()), $to, $this->layers) + ['profile' => []];
            $payload['profile'] = self::profile($payload['candles']);
        } catch (DslError|InvalidArgumentException $failure) {
            $this->error = $failure->getMessage();
        }

        $functions = array_values(array_filter(Library::all(), fn (FunctionSpec $f): bool => $f->refused === null && in_array($f->group, [Group::Price, Group::Indicator, Group::Perpetual], true)));
        $this->dispatch('chart-data', payload: $payload);

        return view('livewire.chart-screen', [
            'payload' => $payload,
            'functions' => array_map(fn (FunctionSpec $f): string => $f->name, $functions),
            'instruments' => Instrument::query()->orderBy('market')->orderBy('symbol')->pluck('symbol')->unique()->values()->all(),
            'timeframes' => array_map(fn (Timeframe $t): string => $t->value, Timeframe::cases()),
        ]);
    }

    /**
     * The volume of the window in 24 price bins, each candle's volume spread over the bins its range covers (D-72).
     *
     * @param  list<array{time: string, open: string, high: string, low: string, close: string, volume: string}>  $candles
     * @return list<array{low: string, high: string, volume: string}>
     */
    private static function profile(array $candles): array
    {
        if ($candles === []) {
            return [];
        }

        $lo = Decimal::of($candles[0]['low']);
        $hi = Decimal::of($candles[0]['high']);

        foreach ($candles as $c) {
            $lo = Decimal::of($c['low'])->isLessThan($lo) ? Decimal::of($c['low']) : $lo;
            $hi = Decimal::of($c['high'])->isGreaterThan($hi) ? Decimal::of($c['high']) : $hi;
        }

        if (! $hi->isGreaterThan($lo)) {
            return [];
        }

        $width = $hi->minus($lo)->dividedBy(Decimal::of(self::PROFILE_BINS), 18);
        $bins = array_fill(0, self::PROFILE_BINS, Decimal::zero());
        $bin = fn (string $price): int => min(self::PROFILE_BINS - 1, (int) (string) Decimal::of($price)->minus($lo)->dividedBy($width, 18)->truncatedTo(0));

        foreach ($candles as $c) {
            [$first, $last] = [$bin($c['low']), $bin($c['high'])];
            $part = Decimal::of($c['volume'])->dividedBy(Decimal::of($last - $first + 1), 18);

            for ($j = $first; $j <= $last; $j++) {
                $bins[$j] = $bins[$j]->plus($part);
            }
        }

        return array_map(fn (int $j, Decimal $volume): array => [
            'low' => (string) $lo->plus($width->times(Decimal::of($j))), 'high' => (string) $lo->plus($width->times(Decimal::of($j + 1))), 'volume' => (string) $volume->roundedTo(8),
        ], array_keys($bins), $bins);
    }
}
