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
use App\Strategy\Baselines\MovingAverageTrend;
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
    'ma-trend-btc-1h' => '4e44533c9829ab538f4f5d4a11b3d93baea62ef2bdd292e5e1133c2bdce005d4',
    'donchian-eth-1h' => '08ce374cab3d139da8fed8bec4560f8b49879f275885d377d1100cb6d0283776',
];

/** @return array<string, Closure(): Report> */
function goldenRuns(): array
{
    $btc = [Symbol::parse('BTC/USDT')];
    $eth = [Symbol::parse('ETH/USDT')];

    return [
        'buy-and-hold-btc' => fn (): Report => GoldenData::run(BuyAndHold::build([], $btc), RiskPolicy::benchmark(), '2026-10-02'),
        'ma-trend-btc-1h' => fn (): Report => GoldenData::run(
            MovingAverageTrend::build(['fast' => '12', 'slow' => '48', 'timeframe' => '1h'], $btc), RiskPolicy::benchmark(), '2026-10-03',
        ),
        'donchian-eth-1h' => fn (): Report => GoldenData::run(
            DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h'], $eth),
            RiskPolicy::account(config()->array('risk.account')),
            '2026-10-02',
            benchmarks: [new Participant('buy-and-hold', BuyAndHold::build([], $eth), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-10-02', 'UTC'))],
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
