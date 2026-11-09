<?php

declare(strict_types=1);

use App\Livewire\SettingsScreen;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Instruments\Universe;
use App\Market\Jobs\ImportMarketData;
use App\Market\Models\Instrument;
use App\Market\Models\MarketJob;
use App\Market\Models\UniverseEntry;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Support\ScalpingFixtures;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-03 12:00', 'UTC')));
    web()->actingAs(operator());
    // The migration seeds the universe; a test that truncates every table empties it, so seed it again here.
    app(Universe::class)->seedFromConfig();
});

it('starts from the universe of config/market.php, in the database', function (): void {
    $universe = app(Universe::class);

    expect(array_map(fn (Symbol $s): string => (string) $s, $universe->symbols(MarketType::Perp)))->toBe(['BTC/USDT:USDT', 'ETH/USDT:USDT', 'SOL/USDT:USDT', 'BNB/USDT:USDT', 'XRP/USDT:USDT'])
        ->and(UniverseEntry::query()->where('scalping', true)->orderBy('id')->pluck('symbol')->all())->toBe(['BTC/USDT:USDT', 'ETH/USDT:USDT']);
});

it('adds any stored instrument, refuses one Binance does not list, and removes one', function (): void {
    Instrument::register('binance', MarketType::Perp, Symbol::parse('DOGE/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));

    Livewire::test(SettingsScreen::class)
        ->set('universeSymbol', 'DOGE/USDT:USDT')->call('addInstrument')
        ->assertSee('DOGE/USDT:USDT joins the universe')
        ->set('universeSymbol', 'NOPE/USDT')->call('addInstrument')
        ->assertSee('NOPE/USDT is not a stored instrument; run market:instruments first.');

    expect(app(Universe::class)->symbols(MarketType::Perp))->toHaveCount(6);

    $doge = UniverseEntry::query()->where('symbol', 'DOGE/USDT:USDT')->sole();
    Livewire::test(SettingsScreen::class)->call('removeInstrument', $doge->id)->assertSee('DOGE/USDT:USDT leaves the universe');
    expect(app(Universe::class)->symbols(MarketType::Perp))->toHaveCount(5);
});

it('adds a delisted perpetual for its history only, never streamed (D-89)', function (): void {
    Instrument::register('binance', MarketType::Perp, Symbol::parse('SRM/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'), InstrumentStatus::Delisted);

    Livewire::test(SettingsScreen::class)
        ->set('universeSymbol', 'SRM/USDT:USDT')->call('addInstrument')
        ->assertSee('SRM/USDT:USDT joins the universe for its history only: it is delisted');

    expect(UniverseEntry::query()->where('symbol', 'SRM/USDT:USDT')->sole()->streamed)->toBeFalse()
        ->and(array_map(fn (Instrument $i): string => $i->symbol, app(Universe::class)->instruments(MarketType::Perp)))->not->toContain('SRM/USDT:USDT');
});

it('switches streaming, the scalping data and the large-trade notional, and says a restart is needed', function (): void {
    $sol = UniverseEntry::query()->where('symbol', 'SOL/USDT:USDT')->sole();

    Livewire::test(SettingsScreen::class)
        ->call('setUniverse', $sol->id, 'scalping', true)
        ->call('setUniverse', $sol->id, 'streamed', false)
        ->set("notional.{$sol->id}", '30000')->call('setNotional', $sol->id)
        ->assertSee('Restart market:stream perp to apply it.');

    $sol->refresh();
    expect($sol->scalping)->toBeTrue()->and($sol->streamed)->toBeFalse()->and((string) Decimal::of((string) $sol->large_notional))->toBe('30000');

    Livewire::test(SettingsScreen::class)->call('setUniverse', UniverseEntry::query()->where('symbol', 'BTC/USDT')->sole()->id, 'scalping', true)
        ->assertSee('The scalping data exists for perpetuals only.');
});

it('queues a history import with its size, and shows its progress', function (): void {
    Queue::fake();
    $btc = UniverseEntry::query()->where('symbol', 'BTC/USDT:USDT')->sole();

    Livewire::test(SettingsScreen::class)
        ->set('importFrom', '2026-10-01')->set('importScalping', true)
        ->call('import', entry: $btc->id)
        ->assertSee('Import of BTC/USDT:USDT from 2026-10-01 queued')
        ->assertSee('about 59 MB');

    Queue::assertPushedOn('market', ImportMarketData::class);
    expect(MarketJob::query()->sole()->status)->toBe('queued');
});

it('runs an import through market:backfill and keeps its output', function (): void {
    ScalpingFixtures::fake();
    config()->set('exchanges.binance.archive_cache', storage_path('framework/testing/archive-'.getmypid()));
    Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-01', 'UTC'));
    $job = MarketJob::query()->create(['symbol' => 'BTC/USDT:USDT', 'from' => '2026-11-01', 'candles' => false, 'scalping' => true, 'status' => 'queued', 'created_at' => '2026-11-03 12:00']);

    app()->call([new ImportMarketData($job->id), 'handle']);

    $job->refresh();
    expect($job->status)->toBe('done')
        ->and($job->output)->toContain('flow 2026-11-01')->and($job->output)->toContain('+22 new');
});
