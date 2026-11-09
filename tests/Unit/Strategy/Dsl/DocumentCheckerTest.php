<?php

declare(strict_types=1);

use App\Strategy\Dsl\Document\CheckResult;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\DslWriter;
use App\Support\Number\Decimal;
use App\Support\Side;
use App\Support\Timeframe;
use Tests\Support\KnownInstruments;

mutates(DocumentChecker::class);

const CONFIGURATOR_EXAMPLE = <<<'YAML'
    strategy: btc-trend-4h                # id: lowercase, digits, hyphens
    title: BTC trend follower on 4h
    family: trend                         # trend | mean_reversion | carry
    hypothesis: >
      Crypto trends persist after a moving-average cross on 4h when the daily trend agrees.
      Paid by late trend followers and by shorts forced to cover.
    fails_when: Choppy ranges with ADX under 20; expect many small losses there.

    market:
      exchange: binance
      type: spot                          # spot | perp
      instruments: [BTC/USDT]             # ccxt unified symbols; perps look like BTC/USDT:USDT
    timeframe: 4h                         # strategies are evaluated when a candle of this timeframe closes

    filters:                              # all must be true for a new entry
      - adx(14) > 20
      - natr(14) < 3                      # skip the most volatile days
      - volume > sma(20, src: volume)     # only on busier candles

    entries:
      long:
        when: ema(20) crosses_above ema(50) and close > ema(200) on 1d
        order: market                     # market | limit(offset: -0.1%) with expiry
      # short: only allowed when market.type is perp

    exits:
      stop_loss: 2 * atr(14)              # distance in price; also 1.5% or level(lowest(low, 10))
      take_profit: 3R                     # optional; R is the initial risk
      trailing: chandelier(22, 3)         # optional; only ever tightens
      time_stop: 60 bars                  # optional
      when: ema(20) crosses_below ema(50) # optional discretionary exit

    sizing:
      risk_per_trade: 0.5%                # of account equity, lost if the stop is hit
      max_position: 20%                   # of equity, cap on notional

    limits:                               # can only be tighter than docs/risk-policy.md
      max_open_positions: 1
      max_trades_per_day: 2
      cooldown_after_loss: 8h
    YAML;

function checkDocument(string $yaml, string $riskPerTrade = '0.005'): CheckResult
{
    return DocumentChecker::check(
        $yaml,
        new KnownInstruments(['BTC/USDT', 'ETH/USDT', 'BTC/USDT:USDT']),
        new PolicyLimits(Decimal::of($riskPerTrade), Decimal::of('0.25'), 6),
    );
}

/** @return list<string> */
function documentErrors(string $yaml, string $riskPerTrade = '0.005'): array
{
    return array_map(fn (DslError $error): string => $error->getMessage(), checkDocument($yaml, $riskPerTrade)->errors);
}

it('compiles the example of docs/strategy/configurator.md', function (): void {
    $result = checkDocument(CONFIGURATOR_EXAMPLE);
    $spec = $result->spec;

    expect($result->errors)->toBe([])
        ->and($spec?->id)->toBe('btc-trend-4h')
        ->and($spec?->family)->toBe('trend')
        ->and($spec?->hypothesis)->toStartWith('Crypto trends persist after a moving-average cross on 4h when the daily trend agrees. Paid by')
        ->and(array_map('strval', $spec->instruments ?? []))->toBe(['BTC/USDT'])
        ->and($spec?->timeframe)->toBe(Timeframe::FourHours)
        ->and(array_map(fn ($filter): string => $filter->text, $spec->filters ?? []))->toBe(['adx(14) > 20', 'natr(14) < 3', 'volume > sma(20, src: volume)'])
        ->and(array_keys($spec->entries ?? []))->toBe(['long'])
        ->and($spec?->entries['long']->side)->toBe(Side::Buy)
        ->and($spec?->entries['long']->order === null)->toBeTrue()
        ->and($spec?->exits->stopLoss?->text)->toBe('2 * atr(14)')
        ->and((string) $spec?->exits->takeProfit)->toBe('3')
        ->and($spec?->exits->trailing?->text)->toBe('chandelier(22, 3)')
        ->and($spec?->exits->timeStop?->text)->toBe('60 bars')
        ->and((string) $spec?->riskPerTrade)->toBe('0.005')
        ->and((string) $spec?->maxPosition)->toBe('0.2')
        ->and([$spec?->maxOpenPositions, $spec?->maxTradesPerDay, $spec?->cooldownSeconds])->toBe([1, 2, 28800])
        ->and($spec?->benchmark)->toBeFalse()
        ->and($result->warnings)->toBe([]);
});

it('gives the errors of docs/strategy/configurator.md, section 7, with their places', function (): void {
    $yaml = str_replace(
        ["  stop_loss: 2 * atr(14)              # distance in price; also 1.5% or level(lowest(low, 10))\n", '  - adx(14) > 20', '  risk_per_trade: 0.5%', '  # short: only allowed when market.type is perp'],
        ['', '  - close > 2%', '  risk_per_trade: 2%', "  short:\n    when: close < ema(200)"],
        CONFIGURATOR_EXAMPLE,
    );

    expect(documentErrors($yaml, '0.01'))->toBe([
        'line 16, column 5: cannot compare a price series with a percent (close > 2%). Did you mean close > close[1] * 1.02 ?',
        'line 24, column 3: entries.short needs market.type perp; BTC/USDT on spot cannot be shorted.',
        'line 27, column 1: stop_loss is missing. Every strategy needs a stop. Example: stop_loss: 2 * atr(14)',
        'line 34, column 19: risk_per_trade 2% is above the policy limit of 1%.',
    ]);
});

it('refuses documents that break the rules, saying where and why', function (string $from, string $to, string $error): void {
    $errors = documentErrors(str_replace($from, $to, CONFIGURATOR_EXAMPLE));

    expect($errors)->toContain($error);
})->with([
    'unknown key' => ['title: BTC', "colour: blue\ntitle: BTC", 'line 2, column 1: colour is not a key of a strategy document. Keys: strategy, title, family, hypothesis, fails_when, profile, template, params, market, timeframe, filters, entries, exits, sizing, limits.'],
    'bad id' => ['strategy: btc-trend-4h', 'strategy: BTC Trend', 'line 1, column 11: strategy must be lowercase letters, digits and hyphens, such as btc-trend-4h.'],
    'missing title' => ["title: BTC trend follower on 4h\n", '', 'line 1, column 1: title is missing.'],
    'unknown family' => ['family: trend', 'family: magic', 'line 3, column 9: family must be one of trend, mean_reversion, carry.'],
    'dropped family (D-64)' => ['family: trend', 'family: event', 'line 3, column 9: family must be one of trend, mean_reversion, carry.'],
    'agents section (D-64)' => ['cooldown_after_loss: 8h', "cooldown_after_loss: 8h\nagents:\n  max_brief_age: 8h", 'line 41, column 1: agents is not a key of a strategy document. Keys: strategy, title, family, hypothesis, fails_when, profile, template, params, market, timeframe, filters, entries, exits, sizing, limits.'],
    'unknown timeframe' => ['timeframe: 4h', 'timeframe: 3h', 'line 13, column 12: timeframe must be one of 1m, 5m, 15m, 1h, 4h, 1d, 1w.'],
    'unknown instrument' => ['instruments: [BTC/USDT]', 'instruments: [DOGE/USDT]', 'line 12, column 17: DOGE/USDT is not a tradable spot instrument on binance; run market:instruments if it is new.'],
    'perpetual on spot' => ['instruments: [BTC/USDT]', 'instruments: [BTC/USDT:USDT]', 'line 12, column 17: BTC/USDT:USDT is a perpetual; market.type is spot.'],
    'not a symbol' => ['instruments: [BTC/USDT]', 'instruments: [BTCUSDT]', 'line 12, column 17: BTCUSDT is not a symbol such as BTC/USDT or BTC/USDT:USDT.'],
    'exchange' => ['exchange: binance', 'exchange: kraken', 'line 10, column 13: exchange must be binance.'],
    'entry not a condition' => ['when: ema(20) crosses_above ema(50) and close > ema(200) on 1d', 'when: ema(20)', 'line 22, column 11: entries.long.when needs a condition, but ema(20) is a price series.'],
    'stop none without benchmark' => ['stop_loss: 2 * atr(14)', 'stop_loss: none', 'line 27, column 14: stop_loss: none is only for profile: benchmark (D-56).'],
    'stop is a condition' => ['stop_loss: 2 * atr(14)', 'stop_loss: close > open', 'line 27, column 14: stop_loss needs a price series or a percent or a stop level, but close > open is a condition.'],
    'take profit not R' => ['take_profit: 3R', 'take_profit: 3%', 'line 28, column 16: take_profit needs an R multiple, but 3% is a percent.'],
    'trailing not a trail' => ['trailing: chandelier(22, 3)', 'trailing: 2 * atr(14)', 'line 29, column 13: trailing needs a trailing stop, but 2 * atr(14) is a price series.'],
    'max position above policy' => ['max_position: 20%', 'max_position: 30%', 'line 35, column 17: max_position 30% is above the policy limit of 25%.'],
    'too many positions' => ['max_open_positions: 1', 'max_open_positions: 9', 'line 38, column 23: max_open_positions 9 is above the policy limit of 6.'],
    'not a whole count' => ['max_trades_per_day: 2', 'max_trades_per_day: 2.5', 'line 39, column 23: max_trades_per_day must be a whole number from 1.'],
    'cooldown in bars' => ['cooldown_after_loss: 8h', 'cooldown_after_loss: 3 bars', 'line 40, column 24: cooldown_after_loss must be a time, such as 8h, not candles.'],
    'syntax error inside' => ['adx(14) > 20', 'adx(14) >', 'line 16, column 14: expected a value after >, found the end of the expression.'],
    'filters not a list' => ["filters:                              # all must be true for a new entry\n  - adx(14) > 20\n  - natr(14) < 3                      # skip the most volatile days\n  - volume > sma(20, src: volume)     # only on busier candles\n", "filters: adx(14) > 20\n", 'line 15, column 10: filters must be a list, one condition per line.'],
    'order elsewhere' => ['order: market', 'order: 2 * atr(14)', 'line 23, column 12: entries.long.order needs an order type, but 2 * atr(14) is a price series.'],
    'no entries' => ["entries:\n  long:", "other_entries:\n  long:", 'line 20, column 1: other_entries is not a key of a strategy document. Keys: strategy, title, family, hypothesis, fails_when, profile, template, params, market, timeframe, filters, entries, exits, sizing, limits.'],
]);

it('declares parameters and checks their ranges', function (): void {
    $params = "params:\n  fast: { default: 20, range: [10, 30], step: 5 }\n  band: { default: 1.5% }\n";
    $ok = checkDocument(str_replace('entries:', $params.'entries:', str_replace('ema(20) crosses_above', 'ema($fast) crosses_above', CONFIGURATOR_EXAMPLE)));
    $bad = documentErrors(str_replace('entries:', "params:\n  fast: { default: 40, range: [10, 30], step: 5 }\n  slow: { default: 2.5, range: [1, 4], step: 0.5 }\nentries:", str_replace('ema(50)', 'ema($slow)', CONFIGURATOR_EXAMPLE)));

    expect($ok->errors)->toBe([])
        ->and(array_keys($ok->spec->params ?? []))->toBe(['fast', 'band'])
        ->and((string) $ok->spec?->params['fast']->default)->toBe('20')
        ->and($ok->spec?->params['fast']->whole)->toBeTrue()
        ->and((string) $ok->spec?->params['band']->default)->toBe('0.015')
        ->and($bad)->toContain('line 21, column 20: the default 40 of fast is outside its range [10, 30].')
        ->and(implode("\n", $bad))->toContain('n of ema must be a whole number of candles from 1, such as ema(20).');
});

it('compiles a benchmark document without a stop', function (): void {
    $result = checkDocument(<<<'YAML'
        strategy: buy-and-hold
        title: Buy and hold
        family: trend
        hypothesis: The comparison every strategy must beat.
        fails_when: Never; it is a benchmark.
        profile: benchmark
        market: { exchange: binance, type: spot, instruments: [BTC/USDT] }
        timeframe: 1m
        entries:
          long:
            when: true
        exits:
          stop_loss: none
        YAML);

    expect($result->errors)->toBe([])
        ->and($result->spec?->benchmark)->toBeTrue()
        ->and($result->spec?->exits->stopLoss)->toBeNull()
        ->and(DslWriter::write($result->spec->entries['long']->when->node ?? throw new LogicException))->toBe('true');
});

it('compiles the funding carry template of docs/strategy/configurator.md', function (): void {
    $result = checkDocument(<<<'YAML'
        strategy: btc-funding-carry
        title: BTC funding carry
        family: carry
        hypothesis: Leveraged longs pay positive funding; long spot plus short perpetual collects it.
        fails_when: Funding turns negative for days, or the exchange fails.
        template: funding_carry
        params:
          spot: BTC/USDT
          perp: BTC/USDT:USDT
          enter_when: funding_rate_avg(9) > 0.01%      # per 8h period, average of the last 3 days
          exit_when: funding_rate_avg(3) < 0%
          max_leverage_perp: 2
          margin_buffer: 50%                           # extra margin kept on the perp leg
        sizing:
          max_position: 30%
        YAML);
    $carry = $result->spec?->template;

    expect($result->errors)->toBe([])
        ->and($result->spec?->timeframe)->toBe(Timeframe::OneHour)
        ->and(array_map('strval', $result->spec->instruments ?? []))->toBe(['BTC/USDT', 'BTC/USDT:USDT'])
        ->and($carry?->parameters())->toBe([
            'entry' => '0.0001', 'entry_periods' => '9', 'exit' => '0', 'exit_periods' => '3',
            'margin_buffer' => '0.5', 'max_leverage' => '2', 'max_position' => '0.3', 'timeframe' => '1h',
        ]);
});

it('refuses a template it cannot read', function (string $from, string $to, string $error): void {
    $yaml = <<<'YAML'
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
        YAML;

    expect(documentErrors(str_replace($from, $to, $yaml)))->toContain($error);
})->with([
    'unknown template' => ['template: funding_carry', 'template: grid', 'line 6, column 11: template must be one of funding_carry.'],
    'other entry form' => ['enter_when: funding_rate_avg(9) > 0.01%', 'enter_when: funding_rate > 0.01%', 'line 10, column 15: funding_carry reads enter_when as funding_rate_avg(n) > x%.'],
    'perp not perpetual' => ['perp: BTC/USDT:USDT', 'perp: ETH/USDT', 'line 9, column 9: perp must be the perpetual of BTC/USDT, BTC/USDT:USDT.'],
    'missing parameter' => ["  margin_buffer: 50%\n", '', 'line 7, column 1: funding_carry needs margin_buffer.'],
]);
