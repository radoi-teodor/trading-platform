<?php

declare(strict_types=1);

use App\Market\Depth\DepthUpdate;
use App\Market\Depth\OrderBook;
use App\Market\Depth\OrderBookOutOfSync;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * @param  list<array{string, string}>  $levels
 * @return list<array{Decimal, Decimal}>
 */
function levels(array $levels): array
{
    return array_map(fn (array $l): array => [Decimal::of($l[0]), Decimal::of($l[1])], $levels);
}

/**
 * @param  list<array{string, string}>  $bids
 * @param  list<array{string, string}>  $asks
 */
function update(int $first, int $last, int $previous, array $bids, array $asks): DepthUpdate
{
    return new DepthUpdate('BTCUSDT', CarbonImmutable::parse('2026-11-09 10:00:00', 'UTC'), $first, $last, $previous, levels($bids), levels($asks));
}

it('starts from a snapshot and applies the updates that follow it, removing a level at zero (D-91)', function (): void {
    $book = OrderBook::fromSnapshot(100, levels([['99', '2'], ['98.5', '1']]), levels([['101', '3'], ['104', '10']]));

    // Older than the snapshot: dropped. The first kept one straddles it; the next follows on.
    $book->apply(update(90, 99, 89, [['97', '5']], []));
    $book->apply(update(99, 105, 98, [['98.5', '0'], ['99.5', '4']], []));
    $book->apply(update(106, 110, 105, [], [['101', '1']]));

    // Mid price 100.25 (99.5 and 101): 1 % reaches 99.2475 below and 101.2525 above.
    $depth = $book->depth(CarbonImmutable::parse('2026-11-09 10:00', 'UTC')) ?? throw new LogicException('The book has no depth.');

    expect((string) $depth->bid(1))->toBe('398')        // 99.5 x 4
        ->and((string) $depth->bid(2))->toBe('596')     // and 99 x 2
        ->and((string) $depth->ask(1))->toBe('101')     // 101 x 1
        ->and((string) $depth->ask(5))->toBe('1141')    // and 104 x 10
        ->and($book->lastUpdate())->toBe(110);
});

it('says it is out of sync when an update does not follow the previous one', function (): void {
    $book = OrderBook::fromSnapshot(100, levels([['99', '2']]), levels([['101', '3']]));
    $book->apply(update(99, 105, 98, [], []));

    $book->apply(update(107, 110, 106, [], []));
})->throws(OrderBookOutOfSync::class, 'BTCUSDT: update 107 to 110 follows 106, not 105');

it('says it is out of sync when the snapshot is older than the first update', function (): void {
    $book = OrderBook::fromSnapshot(100, levels([['99', '2']]), levels([['101', '3']]));

    $book->apply(update(120, 125, 119, [], []));
})->throws(OrderBookOutOfSync::class, 'snapshot 100 is older than the first update, 120');
