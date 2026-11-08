<?php

declare(strict_types=1);

use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\Document\StrategySpec;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\ReadBack;
use App\Support\Number\Decimal;
use Tests\Support\KnownInstruments;

mutates(ReadBack::class);

function readBackSpec(string $yaml): StrategySpec
{
    $result = DocumentChecker::check($yaml, new KnownInstruments(['BTC/USDT', 'ETH/USDT', 'BTC/USDT:USDT']), new PolicyLimits(Decimal::of('0.005'), Decimal::of('0.25'), 6));

    return $result->spec ?? throw new RuntimeException(implode("\n", array_map(fn (DslError $e): string => $e->getMessage(), $result->errors)));
}

function readBackDocument(string $body, string $market = 'spot', string $symbol = 'BTC/USDT', string $extra = ''): string
{
    return "strategy: test\ntitle: Test\nfamily: trend\nhypothesis: Test.\nfails_when: Never.\n{$extra}market: { exchange: binance, type: {$market}, instruments: [{$symbol}] }\ntimeframe: 15m\n{$body}";
}

it('reads the example of docs/strategy/configurator.md back as the paragraph of its section 4', function (): void {
    $doc = (string) file_get_contents(dirname(__DIR__, 4).'/docs/strategy/configurator.md');
    preg_match('/## 1\. A strategy document\s+```yaml\n(.*?)```/s', $doc, $yaml);
    preg_match('/## 4\. Read-back.*?\n((?:> [^\n]*\n)+)/s', $doc, $quote);
    $expected = preg_replace('/\s+/', ' ', trim(str_replace("\n> ", "\n", substr($quote[1] ?? '', 2))));

    expect(ReadBack::render(readBackSpec($yaml[1] ?? '')))->toBe($expected);
});

it('reads shorts, limit entries, lookbacks, logic and parameters as written in the version', function (): void {
    $spec = readBackSpec(readBackDocument(<<<'YAML'
        params:
          len: { default: 20, range: [10, 30], step: 5 }
        filters:
          - not in_position and (hour_utc >= 8 or weekday < 5)
        entries:
          short:
            when: close < lowest(low, $len)[1] and close[1] > open[2]
            order: limit(offset: 0.1%, expiry: 3 bars)
        exits:
          stop_loss: 1.5%
          time_stop: 8h
        sizing:
          risk_per_trade: 0.25%
        limits:
          cooldown_after_loss: 2d
        YAML, 'perp', 'BTC/USDT:USDT'));

    expect(ReadBack::render($spec, ['len' => '15']))->toBe(
        'On BTC/USDT:USDT perpetual, every time a 15-minute candle closes: if no position is open and (the UTC hour is at or above 8 or the weekday is below 5), '
        .'sell short when the close is below the lowest low of the last 15 candles as of the previous candle and the previous close is above the open 2 candles ago, '
        .'with a limit order 0.1 % above the close, cancelled after 3 candles. '
        .'Risk 0.25 % of equity per trade; the position size follows the account policy. Stop 1.5 % above the entry, on the exchange. '
        .'Leave after 8 hours. Wait 2 days after a loss. Parameters: len = 15.'
    );
});

it('reads benchmarks, stop levels, bands and the carry template', function (string $yaml, string $expected): void {
    expect(ReadBack::render(readBackSpec($yaml)))->toBe($expected);
})->with([
    'benchmark' => [readBackDocument("entries:\n  long:\n    when: true\nexits:\n  stop_loss: none\n", extra: "profile: benchmark\n"),
        'On BTC/USDT spot, every time a 15-minute candle closes: buy at market. Sized as a benchmark, for backtests only. No stop: a benchmark holds through every move.'],
    'level and bands' => [readBackDocument("entries:\n  long:\n    when: close crosses_above bb(20, 2).upper on 1h and macd().hist > 0\nexits:\n  stop_loss: level(lowest(low, 10))\n  trailing: atr_trail(14, 3)\n  when: rsi(7, src: hl2) > 70\nlimits:\n  max_trades_per_day: 1\n"),
        'On BTC/USDT spot, every time a 15-minute candle closes: buy at market when the close crosses above the 1-hour upper Bollinger band (20, 2) and the MACD histogram (12, 26, 9) is above 0. '
        .'Risk per trade and position size follow the account policy. Stop at the lowest low of the last 10 candles, on the exchange. Trail with an ATR trailing stop (14, 3). '
        .'Leave when RSI(7) of median price is above 70. At most 1 trade a day.'],
    'carry' => [<<<'YAML'
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
        YAML,
        'On BTC/USDT spot and BTC/USDT:USDT perpetual, every time a 1-hour candle closes: when the average funding rate of the last 9 periods is above 0.01 %, '
        .'buy spot and sell the perpetual short in equal size; close both when the average of the last 3 periods is below 0 %. '
        .'Perpetual leverage at most 2, with 50 % extra margin; at most 30 % of equity in the position.'],
]);

it('reads back every strategy of the catalog', function (string $file): void {
    $text = ReadBack::render(readBackSpec((string) file_get_contents($file)));

    expect($text)->toStartWith('On BTC/USDT:USDT perpetual, every time a 5-minute candle closes:')
        ->and($text)->toContain('on the exchange.')
        ->and($text)->not->toContain('$');
})->with(fn (): array => glob(dirname(__DIR__, 4).'/catalog/strategies/*.strategy.yaml') ?: []);
