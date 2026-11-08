<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Execution\Data\FeeSchedule;
use App\Execution\Venues\CostModel;
use App\Market\Archive\ArchivePeriod;
use App\Market\Archive\BinanceArchive;
use App\Market\Data\Candle;
use App\Market\Data\InstrumentRules;
use App\Market\Data\MarketType;
use App\Market\Data\RulesHistory;
use App\Risk\RiskPolicy;
use App\Simulation\Backtest;
use App\Simulation\Data\Participant;
use App\Simulation\Data\RunDefinition;
use App\Simulation\Report\Report;
use App\Simulation\Report\ReportBuilder;
use App\Strategy\Contracts\Strategy;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * The committed golden dataset: Binance's daily 1-minute archives of BTCUSDT and ETHUSDT spot for 2026-10-01 to
 * 2026-10-10, read through the real archive parser with their checksums (tests/Fixtures/binance/golden).
 */
final class GoldenData
{
    public const string FIRST_DAY = '2026-10-01';

    public const int DAYS = 10;

    /** @var array<string, list<Candle>> */
    private static array $candles = [];

    /** @return list<Candle> */
    public static function candles(string $venueSymbol): array
    {
        if (isset(self::$candles[$venueSymbol])) {
            return self::$candles[$venueSymbol];
        }

        $directory = dirname(__DIR__).'/Fixtures/binance/golden';
        Http::fake(['data.binance.vision/*' => function (Request $request) use ($directory) {
            $file = $directory.'/'.basename(parse_url($request->url(), PHP_URL_PATH) ?: '');

            if (! is_file($file)) {
                throw new RuntimeException("No golden fixture for {$request->url()}.");
            }

            return Http::response((string) file_get_contents($file));
        }]);
        $cache = sys_get_temp_dir().'/trading-golden-'.getmypid();
        $archive = new BinanceArchive(app(Factory::class), 'https://data.binance.vision', $cache, 15);
        $candles = [];

        for ($day = 0; $day < self::DAYS; $day++) {
            $date = CarbonImmutable::parse(self::FIRST_DAY, 'UTC')->addDays($day);
            array_push($candles, ...$archive->klines(MarketType::Spot, $venueSymbol, ArchivePeriod::daily($date)));
        }

        return self::$candles[$venueSymbol] = $candles;
    }

    /**
     * The real filters of 2026-11-08, the oldest stored: older candles run under them, and the report says so.
     *
     * @return array<string, RulesHistory>
     */
    public static function rules(): array
    {
        $rules = fn (string $symbol, string $step): RulesHistory => new RulesHistory(Symbol::parse($symbol), [new InstrumentRules(
            CarbonImmutable::parse('2026-11-08 15:00:00', 'UTC'), Decimal::of('0.01'), Decimal::of($step), Decimal::of($step), Decimal::of('9000'), Decimal::of('5'),
        )]);

        return ['BTC/USDT' => $rules('BTC/USDT', '0.00001'), 'ETH/USDT' => $rules('ETH/USDT', '0.0001')];
    }

    /** @param list<Participant> $benchmarks */
    public static function run(Strategy $strategy, RiskPolicy $policy, string $from, string $to = '2026-10-11', array $benchmarks = []): Report
    {
        $symbols = array_map(fn ($subscription): Symbol => $subscription->symbol, $strategy->subscriptions());
        $start = CarbonImmutable::parse($from, 'UTC');
        $seconds = 60;

        foreach ($strategy->subscriptions() as $subscription) {
            $seconds = max($seconds, $subscription->timeframe->seconds());
        }

        $warmupFrom = $start->subSeconds($seconds * $strategy->warmupBars())->startOfDay();
        $costs = config()->array('simulation.cost_model');
        $definition = new RunDefinition(
            $strategy->identity(), MarketType::Spot, $symbols, $start, CarbonImmutable::parse($to, 'UTC'), $warmupFrom,
            Money::of('10000', Asset::of('USDT')),
            new FeeSchedule(Decimal::of('0.001'), Decimal::of('0.001')),
            new CostModel(1, Decimal::of(self::text($costs, 'half_spread')), Decimal::of(self::text($costs, 'impact')), Decimal::of(self::text($costs, 'limit_volume_share'))),
            $policy,
            array_map(fn (Participant $p): string => $p->label, $benchmarks),
        );
        $feed = new InMemoryCandleFeed([
            'BTC/USDT' => self::candles('BTCUSDT'),
            'ETH/USDT' => self::candles('ETHUSDT'),
        ]);
        $result = (new Backtest($feed))->run($definition, new Participant('strategy', $strategy, $policy, $warmupFrom), self::rules(), $benchmarks);

        return ReportBuilder::build($definition, $result, self::rules());
    }

    /** @param array<array-key, mixed> $section */
    private static function text(array $section, string $key): string
    {
        $value = $section[$key] ?? null;

        return is_string($value) ? $value : throw new RuntimeException("Missing {$key}.");
    }
}
