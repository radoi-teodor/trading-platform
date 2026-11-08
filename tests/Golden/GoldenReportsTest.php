<?php

declare(strict_types=1);

use App\Risk\RiskPolicy;
use App\Simulation\Data\Participant;
use App\Simulation\Metrics\PerformanceMetrics;
use App\Simulation\Metrics\TradeStatistics;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Report\Report;
use App\Simulation\Report\ReportBuilder;
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\FundingCarry;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\GoldenData;

/*
 * Golden tests (docs/conventions.md, section 6): fixed data, strategy version and seed give a report whose hash is
 * committed. A changed hash must be explained in the commit message. To accept a change on purpose, run
 * `UPDATE_GOLDEN=1 vendor/bin/pest tests/Golden`, review the diff of tests/Golden/reports and update the hashes here.
 */

// The golden reports pin every field of a report, so mutation testing may use them for the classes that build one.
mutates(ReportBuilder::class, PerformanceMetrics::class, TradeStatistics::class);

const GOLDEN_HASHES = [
    'buy-and-hold-btc' => '8aad85b739a1896ed0cf32756be6e56c8275be1c1c2526c1c19a316fbc2891b0',
    'ma-trend-btc-1h' => '672640af16f7b6bf6c92c4bcdb03899a1da9d701d3bf5aa5d650ed5df0d835d3',
    'donchian-eth-1h' => 'f9bc685879731c16b824ddc59dc94f044748700d1acd091cbda823d7a5fea9e1',
    'funding-carry-btc-1h' => '2345e99333bba095e4ee599721d7640b9d1c184e6813e9d87ee7f72137328136',
    'ma-trend-short-btc-1h' => '17cb552426f7c5e344ca778fb2f067946b3e89b920773839505f8eca0a5ff3e9',
    'donchian-short-btc-1h' => 'ef940bcc809bb120b3e9213a33f07f2f605846640b6dafea5ad33340c60ffb13',
];

/** @return array<string, Closure(): Report> */
function goldenRuns(): array
{
    $btc = [Symbol::parse('BTC/USDT')];
    $eth = [Symbol::parse('ETH/USDT')];
    $perpetual = [Symbol::parse('BTC/USDT:USDT')];
    $account = fn (): RiskPolicy => RiskPolicy::account(config()->array('risk.account'));

    return [
        'buy-and-hold-btc' => fn (): Report => GoldenData::run(BuyAndHold::build([], $btc), RiskPolicy::benchmark(), '2026-10-02'),
        'ma-trend-btc-1h' => fn (): Report => GoldenData::run(
            MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h'], $btc), RiskPolicy::benchmark(), '2026-10-03',
        ),
        'donchian-eth-1h' => fn (): Report => GoldenData::run(
            DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h'], $eth),
            RiskPolicy::account(config()->array('risk.account')),
            '2026-10-02',
            benchmarks: [new Participant('buy-and-hold', BuyAndHold::build([], $eth), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-10-02', 'UTC'))],
        ),
        'funding-carry-btc-1h' => fn (): Report => GoldenData::run(
            FundingCarry::build([...FundingCarry::defaults(), 'entry' => '0.00003'], $perpetual), $account(), '2026-10-04',
        ),
        'ma-trend-short-btc-1h' => fn (): Report => GoldenData::run(
            MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h', 'side' => 'short'], $perpetual),
            RiskPolicy::benchmark(),
            '2026-10-03',
        ),
        // A perpetual strategy's benchmark holds spot of the same base, with the whole equity in spot (D-49).
        'donchian-short-btc-1h' => fn (): Report => GoldenData::run(
            DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h', 'side' => 'short'], $perpetual),
            $account(),
            '2026-10-02',
            benchmarks: [new Participant(
                'buy-and-hold', BuyAndHold::build([], $btc), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-10-02', 'UTC'),
                Money::of('10000', Asset::of('USDT')), Decimal::zero(),
            )],
        ),
    ];
}

it('reproduces the golden report', function (string $name): void {
    $report = goldenRuns()[$name]();
    $file = __DIR__."/reports/{$name}.json";

    if (getenv('UPDATE_GOLDEN') === '1') {
        file_put_contents($file, CanonicalJson::pretty($report->content));
        fwrite(STDERR, "{$name}: {$report->hash()}\n");
    }

    expect(CanonicalJson::pretty($report->content))->toBe((string) file_get_contents($file))
        ->and($report->hash())->toBe(GOLDEN_HASHES[$name], "The golden report {$name} changed; see the header of this file.");
})->with(array_keys(GOLDEN_HASHES));

it('gives the same report twice in one process', function (): void {
    expect(goldenRuns()['donchian-eth-1h']()->hash())->toBe(goldenRuns()['donchian-eth-1h']()->hash());
});
