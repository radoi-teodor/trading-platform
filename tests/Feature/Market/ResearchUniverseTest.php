<?php

declare(strict_types=1);

use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Instruments\ResearchUniverse;
use App\Market\Models\Instrument;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/** A perpetual with a daily quote volume over [from, to), and its lifetime. */
function rankedPerp(string $base, string $volume, string $from, string $to, ?string $listed = null, ?string $delisted = null, ?string $type = 'COIN'): Instrument
{
    $instrument = Instrument::register('binance', MarketType::Perp, Symbol::parse("{$base}/USDT:USDT"), CarbonImmutable::parse('2026-11-01', 'UTC'), $delisted === null ? InstrumentStatus::Trading : InstrumentStatus::Delisted);
    $instrument->update(['underlying_type' => $type, 'listed_at' => $listed === null ? null : CarbonImmutable::parse($listed, 'UTC'), 'delisted_at' => $delisted === null ? null : CarbonImmutable::parse($delisted, 'UTC')]);

    for ($day = CarbonImmutable::parse($from, 'UTC'); $day->lessThan(CarbonImmutable::parse($to, 'UTC')); $day = $day->addDay()) {
        DB::table('daily_stats')->insert([
            'instrument_id' => $instrument->id, 'day' => $day->format('Y-m-d'), 'open' => '1', 'high' => '1', 'low' => '1', 'close' => '1',
            'volume' => $volume, 'quote_volume' => $volume, 'trades' => 1,
        ]);
    }

    return $instrument;
}

it('ranks the top N by 30-day quote volume each month, point in time (D-89)', function (): void {
    rankedPerp('BIG', '900', '2025-01-01', '2025-04-01', listed: '2024-06-01');
    rankedPerp('MID', '500', '2025-01-01', '2025-04-01', listed: '2024-06-01');
    // Biggest in January and February, delisted on 2025-03-10: a member of February and of March (it still traded
    // when March began), never after.
    rankedPerp('GONE', '2000', '2025-01-01', '2025-03-10', listed: '2024-06-01', delisted: '2025-03-10');
    // Listed on 2025-02-15: not 30 days old on March 1, so not in March; in April.
    rankedPerp('NEW', '5000', '2025-02-15', '2025-04-01', listed: '2025-02-15');
    // A stock contract is never in the research universe.
    rankedPerp('TSLA', '9000', '2025-01-01', '2025-04-01', listed: '2024-06-01', type: 'EQUITY');

    expect(Artisan::call('market:universe:build', ['--top' => 2, '--from' => '2025-02', '--to' => '2025-04']))->toBe(0);

    $members = app(ResearchUniverse::class);

    expect($members->symbolsAt(CarbonImmutable::parse('2025-02-14 10:00', 'UTC')))->toBe(['GONE/USDT:USDT', 'BIG/USDT:USDT'])
        ->and($members->symbolsAt(CarbonImmutable::parse('2025-03-20', 'UTC')))->toBe(['GONE/USDT:USDT', 'BIG/USDT:USDT'])
        ->and($members->symbolsAt(CarbonImmutable::parse('2025-04-01', 'UTC')))->toBe(['NEW/USDT:USDT', 'BIG/USDT:USDT'])
        ->and($members->symbolsAt(CarbonImmutable::parse('2025-05-01', 'UTC')))->toBe([])
        ->and($members->everyMember())->toBe(['BIG/USDT:USDT', 'GONE/USDT:USDT', 'NEW/USDT:USDT'])
        ->and(Artisan::output())->toContain('3 months, 3 instruments');

    // What to import: each instrument over the months it was a member, from a month before for the warmup, and no
    // further, so a coin that left the top is not downloaded to today.
    expect(array_map(fn (array $s): string => "{$s[0]} {$s[1]->format('Y-m-d')} {$s[2]->format('Y-m-d')}", $members->spans()))->toBe([
        'BIG/USDT:USDT 2025-01-01 2025-04-30',
        'GONE/USDT:USDT 2025-01-01 2025-03-31',
        'NEW/USDT:USDT 2025-03-01 2025-04-30',
    ]);
});
