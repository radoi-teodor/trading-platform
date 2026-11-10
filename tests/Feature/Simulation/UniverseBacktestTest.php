<?php

declare(strict_types=1);

use App\Simulation\Models\BacktestRun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/** @return array{int, string} the exit code and the output of a backtest of $document over the fixture's second day */
function universeBacktest(string $document = UNIVERSE_FIXTURE_DOCUMENT, string $from = '2026-10-02', string $to = '2026-10-03'): array
{
    $exit = Artisan::call('backtest:run', ['--file' => strategyFile($document), '--from' => $from, '--to' => $to]);

    return [$exit, Artisan::output()];
}

/**
 * @param  array<array-key, mixed>  $report
 * @return list<array<array-key, mixed>> the rows of $report under $key, those about $symbol when one is given
 */
function reportRows(array $report, string $key, ?string $symbol = null): array
{
    $rows = [];

    foreach (is_array($report[$key] ?? null) ? $report[$key] : [] as $row) {
        if (is_array($row) && ($symbol === null || ($row['symbol'] ?? null) === $symbol)) {
            $rows[] = $row;
        }
    }

    return $rows;
}

it('backtests a universe strategy over the members of its months, one that was later delisted included (D-92)', function (): void {
    universeFixture();

    [$exit, $output] = universeBacktest();
    $run = BacktestRun::query()->sole();
    $report = storedReport($run);
    $gone = reportRows($report, 'trades', 'FFF/USDT:USDT');
    $closes = array_filter(reportRows($report, 'events'), fn (array $event): bool => ($event['kind'] ?? null) === 'delisting_close');
    $late = array_filter($gone, fn (array $trade): bool => ($trade['closed_at'] ?? null) > '2026-10-02T12:00:00');

    expect($exit)->toBe(0)
        ->and($output)->toContain('on the top 6 by quote_volume_30d, 6 instruments')
        ->and(data_get($report, 'definition.symbols'))->toBe(UNIVERSE_SYMBOLS_FIXTURE)
        // The filters of the one known only from the archives are worked out from its candles, and the report says so.
        ->and(data_get($report, 'data.filters_assumed_before'))->toHaveKey('FFF/USDT:USDT')
        ->and(data_get($report, 'data.filters_assumed_before'))->not->toHaveKey('AAA/USDT:USDT')
        // A universe is compared with holding bitcoin.
        ->and($report)->toHaveKey('benchmarks.buy-and-hold.strategy')
        ->and($run->trades)->toBeGreaterThan(0)
        // It traded the one that was delisted while it was listed, and held nothing in it past its last hour.
        ->and($gone)->not->toBe([])
        ->and($late)->toBe([])
        ->and(reportRows($report, 'open_positions', 'FFF/USDT:USDT'))->toBe([])
        ->and($closes)->toHaveCount(1);
});

it('gives the same report for the same universe run (D-92)', function (): void {
    universeFixture();

    universeBacktest();
    universeBacktest();

    expect(BacktestRun::query()->pluck('report_hash')->unique())->toHaveCount(1);
});

it('runs over a member that did not trade for a while before the run, without the candles of the break (D-89)', function (): void {
    universeFixture();
    universeBacktest();
    $whole = data_get(storedReport(BacktestRun::query()->sole()), 'data.candles');
    BacktestRun::query()->delete();
    // CCC was delisted and listed again on the day before the run. The archive holds candles for two of the three
    // hours of the break; none of the three is a gap, and none of its candles reaches the run.
    $ccc = DB::table('instruments')->where('symbol', 'CCC/USDT:USDT')->value('id');
    DB::table('instrument_breaks')->insert(['instrument_id' => $ccc, 'stopped_at' => '2026-10-01 06:00:00+00', 'resumed_at' => '2026-10-01 09:00:00+00']);
    DB::table('candles')->where('instrument_id', $ccc)->whereBetween('open_time', ['2026-10-01 08:00:00+00', '2026-10-01 08:59:00+00'])->delete();

    [$exit, $output] = universeBacktest();

    expect($exit)->toBe(0, $output)
        ->and($whole)->toBeInt()
        ->and(data_get(storedReport(BacktestRun::query()->sole()), 'data.candles'))->toBe(is_int($whole) ? $whole - 180 : null);
});

it('refuses a universe run it cannot answer for', function (Closure $arrange, string $document, string $from, string $to, string $message): void {
    universeFixture();
    $arrange();

    [$exit, $output] = universeBacktest($document, $from, $to);

    expect($exit)->toBe(1)->and($output)->toContain($message)->and(BacktestRun::query()->count())->toBe(0);
})->with([
    'a universe that is not built' => [
        fn () => DB::table('universe_members')->delete(), UNIVERSE_FIXTURE_DOCUMENT, '2026-10-02', '2026-10-03',
        'The research universe is not built: run market:universe:build first.',
    ],
    'more members than the universe was built for' => [
        fn () => null, UNIVERSE_DOCUMENT, '2026-10-02', '2026-10-03',
        'xs-momentum-test trades the top 50, and the research universe is built for the top 6: build it again with --top=50.',
    ],
    'a month the universe was not built for' => [
        fn () => null, UNIVERSE_FIXTURE_DOCUMENT, '2026-10-20', '2026-11-02',
        'The research universe has no members for 2026-11: it is built from 2026-10 to 2026-10.',
    ],
    'a member with minutes missing' => [
        fn () => DB::table('candles')->where('instrument_id', DB::table('instruments')->where('symbol', 'CCC/USDT:USDT')->value('id'))
            ->whereBetween('open_time', ['2026-10-02 03:00:00+00', '2026-10-02 03:02:00+00'])->delete(),
        UNIVERSE_FIXTURE_DOCUMENT, '2026-10-02', '2026-10-03',
        'CCC/USDT:USDT: 3 minutes missing in 1 gaps, the first from 2026-10-02 03:00 to 2026-10-02 03:02',
    ],
    'a member that stopped trading for a while during the run' => [
        fn () => DB::table('instrument_breaks')->insert([
            'instrument_id' => DB::table('instruments')->where('symbol', 'CCC/USDT:USDT')->value('id'), 'stopped_at' => '2026-10-02 03:00:00+00', 'resumed_at' => '2026-10-02 05:00:00+00',
        ]),
        UNIVERSE_FIXTURE_DOCUMENT, '2026-10-02', '2026-10-03',
        'CCC/USDT:USDT: it did not trade from 2026-10-02 03:00 to 2026-10-02 05:00, and a run over a break in trading is not supported',
    ],
    'a member with funding missing' => [
        fn () => DB::table('funding_rates')->where('instrument_id', DB::table('instruments')->where('symbol', 'BBB/USDT:USDT')->value('id'))->delete(),
        UNIVERSE_FIXTURE_DOCUMENT, '2026-10-02', '2026-10-03',
        'BBB/USDT:USDT: no funding stored between',
    ],
]);
