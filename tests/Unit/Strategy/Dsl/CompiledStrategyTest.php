<?php

declare(strict_types=1);

use App\Market\Data\Candle;
use App\Strategy\Baselines\DonchianBreakout;
use App\Strategy\Baselines\MovingAverageTrend;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\Intent;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Compiler\Compiler;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\DslError;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Tests\Support\CandleFactory;
use Tests\Support\KnownInstruments;

mutates(CompiledStrategy::class, Compiler::class);

/** @param array<string, string> $overrides */
function compileDocument(string $yaml, array $overrides = []): Strategy
{
    $result = DocumentChecker::check($yaml, new KnownInstruments(['BTC/USDT', 'BTC/USDT:USDT', 'ETH/USDT']), new PolicyLimits(Decimal::of('0.005'), Decimal::of('0.25'), 6));

    if ($result->spec === null) {
        throw new RuntimeException(implode("\n", array_map(fn (DslError $e): string => $e->getMessage(), $result->errors)));
    }

    return CompiledStrategy::fromSpec($result->spec, $overrides);
}

function dslDocument(string $body, string $symbol = 'BTC/USDT', string $timeframe = '1h', string $extra = ''): string
{
    $type = str_contains($symbol, ':') ? 'perp' : 'spot';

    return "strategy: test\ntitle: Test\nfamily: trend\nhypothesis: Test.\nfails_when: Never.\n{$extra}market: { exchange: binance, type: {$type}, instruments: [{$symbol}] }\ntimeframe: {$timeframe}\n{$body}";
}

/**
 * Candles of $timeframe built from 1-minute walk candles, as the aggregator closes them.
 *
 * @return list<Candle>
 */
function aggregatedBars(int $minutes, Timeframe $timeframe, int $seed): array
{
    $bars = [];
    $group = [];

    foreach (CandleFactory::walk('2026-10-01 00:00:00', $minutes, seed: $seed) as $minute) {
        $group[] = $minute;

        if (count($group) * 60 === $timeframe->seconds()) {
            $bars[] = Candle::fromStrings(
                $group[0]->openTime, $timeframe, (string) $group[0]->open,
                (string) max(array_map(fn (Candle $c): string => (string) $c->high, $group)),
                (string) min(array_map(fn (Candle $c): string => (string) $c->low, $group)),
                (string) end($group)->close, '1', '1', 1, '0',
            );
            $group = [];
        }
    }

    return $bars;
}

/**
 * Plays bars through a strategy, holding a position from each entry until a close (the venue fills at the close),
 * and lists what it decided on each bar.
 *
 * @param  list<Candle>  $bars
 * @return list<string>
 */
function playBars(Strategy $strategy, array $bars, Symbol $symbol): array
{
    $position = null;
    $lines = [];

    foreach ($bars as $bar) {
        $intents = $strategy->onCandle(new StrategyContext($bar->endsAt(), $symbol, $bar, $position));
        $lines[] = implode(',', array_map(fn (Intent $intent): string => match (true) {
            $intent instanceof OpenLong => "long {$intent->stop} {$intent->target}",
            $intent instanceof OpenShort => "short {$intent->stop} {$intent->target}",
            $intent instanceof Close => 'close',
            $intent instanceof MoveStop => "move {$intent->stop}",
            default => 'other',
        }, $intents));

        foreach ($intents as $intent) {
            if ($intent instanceof OpenLong || $intent instanceof OpenShort) {
                $position = new PositionView(Decimal::of(1), $bar->close->value, $intent->stop, $bar->endsAt(), $intent instanceof OpenLong ? Side::Buy : Side::Sell);
            } elseif ($intent instanceof Close) {
                $position = null;
            }
        }
    }

    return $lines;
}

it('decides like the coded Donchian breakout, long and short, on every bar', function (string $side, string $symbol, string $entry, string $exit): void {
    $bars = aggregatedBars(60 * 400, Timeframe::OneHour, 11);
    $coded = DonchianBreakout::build([...DonchianBreakout::defaults(), 'timeframe' => '1h', 'side' => $side], [Symbol::parse($symbol)]);
    $dsl = compileDocument(dslDocument(<<<YAML
        entries:
          {$side}:
            when: {$entry}
        exits:
          stop_loss: 2 * atr(14)
          take_profit: 3R
          when: {$exit}
        YAML, $symbol));
    $decisions = playBars($dsl, $bars, Symbol::parse($symbol));

    expect($decisions)->toBe(playBars($coded, $bars, Symbol::parse($symbol)))
        ->and(count(array_filter($decisions, fn (string $d): bool => str_starts_with($d, $side))))->toBeGreaterThan(2)
        ->and($dsl->warmupBars())->toBe($coded->warmupBars());
})->with([
    'long' => ['long', 'BTC/USDT', 'close > highest(high, 20)[1]', 'close < lowest(low, 10)[1]'],
    'short' => ['short', 'BTC/USDT:USDT', 'close < lowest(low, 20)[1]', 'close > highest(high, 10)[1]'],
]);

it('decides like the coded trend rule as a benchmark document', function (): void {
    $bars = aggregatedBars(60 * 300, Timeframe::OneHour, 5);
    $coded = MovingAverageTrend::build([...MovingAverageTrend::defaults(), 'fast' => '12', 'slow' => '48', 'timeframe' => '1h'], [Symbol::parse('BTC/USDT')]);
    $dsl = compileDocument(dslDocument(<<<'YAML'
        params:
          fast: { default: 50, range: [5, 100], step: 1 }
          slow: { default: 200, range: [20, 400], step: 1 }
        entries:
          long:
            when: sma($fast) > sma($slow)
        exits:
          stop_loss: none
          when: sma($fast) <= sma($slow)
        YAML, extra: "profile: benchmark\n"), ['fast' => '12', 'slow' => '48']);
    $decisions = playBars($dsl, $bars, Symbol::parse('BTC/USDT'));

    expect($decisions)->toBe(playBars($coded, $bars, Symbol::parse('BTC/USDT')))
        ->and(array_filter($decisions))->not->toBe([])
        ->and($dsl->warmupBars())->toBe(48)
        ->and($dsl->identity()->parameters)->toBe(['fast' => '12', 'slow' => '48']);
});

it('reads a higher timeframe only from its last closed candle', function (): void {
    $dsl = compileDocument(dslDocument(<<<'YAML'
        entries:
          long:
            when: close > highest(high, 3)[1] on 1h
        exits:
          stop_loss: 1%
        YAML, timeframe: '15m'));
    $symbol = Symbol::parse('BTC/USDT');
    $hours = aggregatedBars(60 * 8, Timeframe::OneHour, 3);
    $quarters = aggregatedBars(60 * 8, Timeframe::FifteenMinutes, 3);
    $decided = [];
    $levels = [];
    $highs = [];

    // The engine calls the hourly subscription first when both close together (D-53).
    foreach ($quarters as $quarter) {
        foreach ($hours as $hour) {
            if ($hour->endsAt()->equalTo($quarter->endsAt())) {
                $dsl->onCandle(new StrategyContext($hour->endsAt(), $symbol, $hour, null));
                $highs[] = $hour->high->value;
            }
        }

        $level = null;

        foreach (count($highs) >= 4 ? array_slice($highs, -4, 3) : [] as $high) {
            $level = $level === null || $high->isGreaterThan($level) ? $high : $level;
        }

        $levels[] = $level;
        $decided[] = $dsl->onCandle(new StrategyContext($quarter->endsAt(), $symbol, $quarter, null)) !== [];
    }

    $expected = array_map(fn (?Decimal $level, Candle $quarter): bool => $level !== null && $quarter->close->value->isGreaterThan($level), $levels, $quarters);

    expect($decided)->toBe($expected)
        ->and(array_map(fn ($s): string => $s->timeframe->value, $dsl->subscriptions()))->toBe(['1h', '15m'])
        // three hourly candles back plus the one before: 4 hours
        ->and($dsl->warmupBars())->toBe(4);
});

it('trails the stop only towards the price, leaves on the time stop, and fixes R from the close', function (): void {
    $dsl = compileDocument(dslDocument(<<<'YAML'
        entries:
          long:
            when: close > open
        exits:
          stop_loss: 2%
          take_profit: 2R
          trailing: atr_trail(2, 1)
          time_stop: 3 bars
        YAML, timeframe: '1h'));
    $symbol = Symbol::parse('BTC/USDT');
    $bar = fn (int $hour, string $open, string $high, string $low, string $close): Candle => Candle::fromStrings(
        CarbonImmutable::parse('2026-10-01', 'UTC')->addHours($hour), Timeframe::OneHour, $open, $high, $low, $close, '1', '1', 1, '0',
    );
    $entry = $dsl->onCandle(new StrategyContext($bar(0, '100', '101', '99', '100')->endsAt(), $symbol, $bar(0, '100', '101', '99', '100'), null));
    $entry = $dsl->onCandle(new StrategyContext($bar(1, '100', '101', '99', '100')->endsAt(), $symbol, $bar(1, '100', '101', '99', '100'), null))
        ?: $dsl->onCandle(new StrategyContext($bar(2, '100', '111', '99', '110')->endsAt(), $symbol, $bar(2, '100', '111', '99', '110'), null));
    $open = $entry[0] ?? null;
    $held = new PositionView(Decimal::of(1), Decimal::of('110'), $open instanceof OpenLong ? $open->stop : null, CarbonImmutable::parse('2026-10-01 03:00', 'UTC'));
    // ATR(2): true ranges 2, 2, 12, then 5; Wilder (2 + 12) / 2 = 7, then (7 + 5) / 2 = 6: trail at 115 - 6 = 109
    $move = $dsl->onCandle(new StrategyContext($bar(3, '110', '115', '110', '115')->endsAt(), $symbol, $bar(3, '110', '115', '110', '115'), $held));
    $loosen = $dsl->onCandle(new StrategyContext($bar(4, '112', '112', '90', '95')->endsAt(), $symbol, $bar(4, '112', '112', '90', '95'), $held));
    $timed = $dsl->onCandle(new StrategyContext($bar(5, '95', '96', '94', '95')->endsAt(), $symbol, $bar(5, '95', '96', '94', '95'), $held));

    expect($open)->toBeInstanceOf(OpenLong::class)
        ->and((string) ($open instanceof OpenLong ? $open->stop : ''))->toBe('107.8')
        ->and((string) ($open instanceof OpenLong ? $open->target : ''))->toBe('114.4')
        ->and($move[0] ?? null)->toBeInstanceOf(MoveStop::class)
        ->and((string) (($move[0] ?? null) instanceof MoveStop ? $move[0]->stop : ''))->toBe('109')
        ->and($loosen)->toBe([])
        ->and($timed[0] ?? null)->toBeInstanceOf(Close::class);
});

it('builds the funding carry template under the document\'s identity', function (): void {
    $carry = compileDocument(<<<'YAML'
        strategy: btc-funding-carry
        title: BTC funding carry
        family: carry
        hypothesis: Carry.
        fails_when: Negative funding.
        template: funding_carry
        params:
          spot: BTC/USDT
          perp: BTC/USDT:USDT
          enter_when: funding_rate_avg(9) > 0.01%
          exit_when: funding_rate_avg(3) < 0%
          max_leverage_perp: 2
          margin_buffer: 50%
        sizing:
          max_position: 30%
        YAML);

    expect($carry->identity()->name)->toBe('btc-funding-carry')
        ->and(array_map(fn ($s): string => (string) $s->symbol, $carry->subscriptions()))->toBe(['BTC/USDT', 'BTC/USDT:USDT'])
        ->and($carry->warmupBars())->toBe(73);
});

it('refuses parameter values a document does not allow', function (string $name, string $value, string $message): void {
    expect(fn () => compileDocument(dslDocument(<<<'YAML'
        params:
          fast: { default: 9 }
          band: { default: 1% }
        entries:
          long:
            when: ema($fast) > close * (1 + $band)
        exits:
          stop_loss: 1%
        YAML), [$name => $value]))->toThrow(InvalidArgumentException::class, $message);
})->with([
    'unknown' => ['slow', '21', 'test has no parameter slow. Parameters: fast, band.'],
    'percent for a number' => ['fast', '9%', "fast takes a number, got '9%'."],
    'number for a percent' => ['band', '2', "band takes a percent, such as 1.5%, got '2'."],
    'period not whole' => ['fast', '9.5', "fast is a period: a whole number from 1, got '9.5'."],
]);

it('asks for a limit entry and carries the document\'s sizing and limits to the risk engine (D-54, D-55)', function (): void {
    $dsl = compileDocument(dslDocument(<<<'YAML'
        params:
          offset: { default: -0.1% }
        entries:
          long:
            when: close > open
            order: limit(offset: $offset, expiry: 3 bars)
        exits:
          stop_loss: 1%
        sizing:
          risk_per_trade: 0.25%
          max_position: 10%
        limits:
          max_open_positions: 1
          max_trades_per_day: 4
          cooldown_after_loss: 30m
        YAML, timeframe: '5m'));
    $bar = Candle::fromStrings(CarbonImmutable::parse('2026-10-01', 'UTC'), Timeframe::FiveMinutes, '100', '101', '99', '101', '1', '1', 1, '0');
    $intent = $dsl->onCandle(new StrategyContext($bar->endsAt(), Symbol::parse('BTC/USDT'), $bar, null))[0] ?? null;
    $limits = $dsl instanceof LimitedStrategy ? $dsl->limits() : null;

    expect($intent)->toBeInstanceOf(OpenLong::class)
        ->and($intent instanceof OpenLong ? (string) $intent->order?->offset : '')->toBe('-0.001')
        ->and($intent instanceof OpenLong ? $intent->order?->expirySeconds : 0)->toBe(900)
        ->and((string) $limits?->riskPerTrade)->toBe('0.0025')
        ->and((string) $limits?->maxPosition)->toBe('0.1')
        ->and([$limits?->maxOpenPositions, $limits?->maxTradesPerDay, $limits?->cooldownSeconds])->toBe([1, 4, 1800]);
});
