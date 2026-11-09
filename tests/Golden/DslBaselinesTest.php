<?php

declare(strict_types=1);

use App\Risk\RiskPolicy;
use App\Simulation\Data\Participant;
use App\Simulation\Report\CanonicalJson;
use App\Simulation\Report\Report;
use App\Strategy\Baselines\BuyAndHold;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\FundingCarry;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\DslError;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Tests\Support\GoldenData;
use Tests\Support\KnownInstruments;

/**
 * Phase 3's criterion: every coded baseline, written in the DSL (catalog/baselines), gives the same report on the
 * golden data as the code, apart from the strategy's identity (name, version, parameters and the key derived from
 * them), which appears in the definition and in event details.
 *
 * @param  array<string, string>  $replace  text changes to the document, such as its timeframe for the golden window
 * @param  array<string, string>  $overrides  parameter values
 */
function baselineDocument(string $name, array $replace = [], array $overrides = []): Strategy
{
    $yaml = str_replace(array_keys($replace), array_values($replace), (string) file_get_contents(dirname(__DIR__, 2)."/catalog/baselines/{$name}.strategy.yaml"));
    $policy = RiskPolicy::account(config()->array('risk.account'));
    $result = DocumentChecker::check($yaml, new KnownInstruments(['BTC/USDT', 'ETH/USDT', 'BTC/USDT:USDT']), new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions));

    return CompiledStrategy::fromSpec($result->spec ?? throw new RuntimeException(implode("\n", array_map(fn (DslError $e): string => $e->getMessage(), $result->errors))), $overrides);
}

/** @return array<array-key, mixed> the report without the strategy's identity: definition.strategy and its key in event details */
function withoutIdentity(Report $report, Strategy $strategy): array
{
    $content = $report->content;
    $definition = is_array($content['definition'] ?? null) ? $content['definition'] : [];
    unset($definition['strategy']);
    $content['definition'] = $definition;
    $decoded = json_decode(str_replace($strategy->identity()->key(), 'KEY', CanonicalJson::encode($content)), true, flags: JSON_THROW_ON_ERROR);

    return is_array($decoded) ? $decoded : [];
}

/** @return array<string, array{Closure(): Strategy, Closure(): Strategy, Closure(): RiskPolicy, string, Closure(): list<Participant>}> */
function baselinePairs(): array
{
    $btc = [Symbol::parse('BTC/USDT')];
    $eth = [Symbol::parse('ETH/USDT')];
    $perpetual = [Symbol::parse('BTC/USDT:USDT')];
    $account = fn (): RiskPolicy => RiskPolicy::account(config()->array('risk.account'));
    $benchmark = fn (): RiskPolicy => RiskPolicy::benchmark();
    $none = fn (): array => [];

    return [
        'buy and hold' => [fn (): Strategy => BuyAndHold::build([], $btc), fn (): Strategy => baselineDocument('buy-and-hold'), $benchmark, '2026-10-02', $none],
        'trend 12/48 hourly' => [
            fn (): Strategy => MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h'], $btc),
            fn (): Strategy => baselineDocument('ma-trend', ['timeframe: 1d' => 'timeframe: 1h'], ['fast' => '12', 'slow' => '48']),
            $benchmark, '2026-10-03', $none,
        ],
        'trend short on the perpetual' => [
            fn (): Strategy => MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h', 'side' => 'short'], $perpetual),
            fn (): Strategy => baselineDocument('ma-trend-short', ['timeframe: 1d' => 'timeframe: 1h'], ['fast' => '12', 'slow' => '48']),
            $benchmark, '2026-10-03', $none,
        ],
        'donchian on ETH' => [
            fn (): Strategy => DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h'], $eth),
            fn (): Strategy => baselineDocument('donchian-breakout', ['timeframe: 4h' => 'timeframe: 1h', '[BTC/USDT]' => '[ETH/USDT]']),
            $account, '2026-10-02',
            fn (): array => [new Participant('buy-and-hold', BuyAndHold::build([], $eth), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-10-02', 'UTC'))],
        ],
        'donchian short on the perpetual' => [
            fn (): Strategy => DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h', 'side' => 'short'], $perpetual),
            fn (): Strategy => baselineDocument('donchian-breakout-short', ['timeframe: 4h' => 'timeframe: 1h']),
            $account, '2026-10-02',
            fn (): array => [new Participant(
                'buy-and-hold', BuyAndHold::build([], $btc), RiskPolicy::benchmark(), CarbonImmutable::parse('2026-10-02', 'UTC'),
                Money::of('10000', Asset::of('USDT')), Decimal::zero(),
            )],
        ],
        'funding carry' => [
            fn (): Strategy => FundingCarry::build([...FundingCarry::defaults(), 'entry' => '0.00003'], $perpetual),
            fn (): Strategy => baselineDocument('funding-carry', ['> 0.01%' => '> 0.003%']),
            $account, '2026-10-04', $none,
        ],
    ];
}

/** @return list<Participant> */
function participantsOf(Closure $make): array
{
    $made = $make();

    return is_array($made) ? array_values(array_filter($made, fn (mixed $p): bool => $p instanceof Participant)) : [];
}

it('gives the coded baseline\'s report when written in the DSL', function (Closure $coded, Closure $dsl, Closure $policy, string $from, Closure $benchmarks): void {
    $codedStrategy = $coded();
    $dslStrategy = $dsl();
    $rules = $policy();

    if (! $codedStrategy instanceof Strategy || ! $dslStrategy instanceof Strategy || ! $rules instanceof RiskPolicy) {
        throw new LogicException('A baseline pair builds two strategies and a policy.');
    }

    $codedReport = GoldenData::run($codedStrategy, $rules, $from, benchmarks: participantsOf($benchmarks));
    $dslReport = GoldenData::run($dslStrategy, $rules, $from, benchmarks: participantsOf($benchmarks));
    $held = fn (Report $report): int => (is_array($report->content['trades'] ?? null) ? count($report->content['trades']) : 0)
        + (is_array($report->content['open_positions'] ?? null) ? count($report->content['open_positions']) : 0);

    expect(withoutIdentity($dslReport, $dslStrategy))->toBe(withoutIdentity($codedReport, $codedStrategy))
        ->and($held($codedReport))->toBeGreaterThan(0);
})->with(baselinePairs());
