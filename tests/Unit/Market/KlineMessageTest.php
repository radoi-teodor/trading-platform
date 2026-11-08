<?php

declare(strict_types=1);

use App\Market\Stream\KlineMessage;

mutates(KlineMessage::class);

/** @return list<string> */
function streamLines(): array
{
    $lines = file(__DIR__.'/../../Fixtures/binance/spot-stream-kline-btcusdt.jsonl', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    return $lines === false ? [] : $lines;
}

it('reads a closed candle from a combined stream message', function (): void {
    $message = KlineMessage::parse(streamLines()[1]);

    expect($message)->not->toBeNull()
        ->and($message?->venueSymbol)->toBe('BTCUSDT')
        ->and($message?->closed)->toBeTrue()
        ->and($message?->candle->openTime->format('Y-m-d H:i'))->toBe('2026-11-08 15:03')
        ->and((string) $message?->candle->close)->toBe('76030')
        ->and((string) $message?->candle->volume)->toBe('2.26208')
        ->and($message?->candle->tradeCount)->toBe(1675);
});

it('marks an update of a still-open candle as not closed', function (): void {
    expect(KlineMessage::parse(streamLines()[0])?->closed)->toBeFalse();
});

it('ignores messages that are not klines', function (): void {
    expect(KlineMessage::parse('{"result":null,"id":1}'))->toBeNull()
        ->and(KlineMessage::parse('{"stream":"btcusdt@trade","data":{"e":"trade"}}'))->toBeNull();
});

it('fails loudly on a kline it cannot read', function (string $payload): void {
    KlineMessage::parse($payload);
})->with([
    'not json' => ['not json'],
    'kline without fields' => ['{"stream":"btcusdt@kline_1m","data":{"e":"kline","s":"BTCUSDT","k":{}}}'],
])->throws(UnexpectedValueException::class);
