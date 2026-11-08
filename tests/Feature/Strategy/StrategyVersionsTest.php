<?php

declare(strict_types=1);

use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Versions\StrategyRepository;
use App\Strategy\Versions\StrategyVersion;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\Support\KnownInstruments;

uses(RefreshDatabase::class);

mutates(StrategyRepository::class);

const VERSIONED_DOCUMENT = <<<'YAML'
    strategy: ema-versioned
    title: EMA cross
    family: trend
    hypothesis: Momentum.
    fails_when: Chop.
    params:
      fast: { default: 9, range: [5, 13], step: 2 }
    market: { exchange: binance, type: spot, instruments: [BTC/USDT] }
    timeframe: 5m
    entries:
      long:
        when: ema($fast) crosses_above ema(21)
    exits:
      stop_loss: 1.5 * atr(14)
    YAML;

function versions(): StrategyRepository
{
    return new StrategyRepository(new SimClock(CarbonImmutable::parse('2026-11-08 12:00', 'UTC')), new KnownInstruments(['BTC/USDT']), new PolicyLimits(Decimal::of('0.005'), Decimal::of('0.25'), 6));
}

it('saves a document that compiles as version 1, a Draft, by its author', function (): void {
    $saved = versions()->save(VERSIONED_DOCUMENT, 'agent:desk');
    $version = $saved->version;

    expect($saved->created)->toBeTrue()
        ->and($version?->strategy)->toBe('ema-versioned')
        ->and($version?->version)->toBe(1)
        ->and($version?->label())->toBe('ema-versioned@v1')
        ->and($version?->author)->toBe('agent:desk')
        ->and($version?->sha256)->toBe(hash('sha256', VERSIONED_DOCUMENT."\n"))
        ->and($version?->parent_id)->toBeNull()
        ->and($version?->stage())->toBe('draft')
        ->and($version?->created_at->toDateTimeString())->toBe('2026-11-08 12:00:00');
});

it('keeps versions immutable: the same document again is the same version, a changed one the next', function (): void {
    $first = versions()->save(VERSIONED_DOCUMENT, 'operator')->version;
    $again = versions()->save(VERSIONED_DOCUMENT."\n\n", 'operator');
    $changed = versions()->save(str_replace('ema(21)', 'ema(26)', VERSIONED_DOCUMENT), 'agent:desk');

    expect($again->created)->toBeFalse()
        ->and($again->version?->id)->toBe($first?->id)
        ->and($changed->created)->toBeTrue()
        ->and($changed->version?->version)->toBe(2)
        ->and($changed->version?->parent_id)->toBe($first?->id)
        ->and(StrategyVersion::query()->count())->toBe(2)
        ->and(versions()->find('ema-versioned@v1')?->document)->toBe(VERSIONED_DOCUMENT."\n")
        ->and(versions()->latest('ema-versioned')?->version)->toBe(2);
});

it('refuses to save a document that does not compile, and says why', function (): void {
    $saved = versions()->save(str_replace('stop_loss: 1.5 * atr(14)', 'stop_loss: close > 2%', VERSIONED_DOCUMENT), 'operator');

    expect($saved->version)->toBeNull()
        ->and($saved->check->errors[0]->getMessage())->toStartWith('line 14, column 14: cannot compare a price series with a percent')
        ->and(StrategyVersion::query()->count())->toBe(0);
});

it('refuses version names it cannot read', function (string $name): void {
    versions()->find($name);
})->with(['ema-versioned', 'ema-versioned@1', 'Ema@v1', 'ema@v0'])->throws(InvalidArgumentException::class);

it('imports the catalog and exports every version back, identical', function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-08 12:00', 'UTC')));
    Instrument::register('binance', MarketType::Perp, Symbol::parse('BTC/USDT:USDT'), CarbonImmutable::parse('2026-11-08', 'UTC'));
    $directory = storage_path('framework/testing/catalog-'.getmypid());
    @mkdir($directory, 0o755, true);
    array_map('unlink', glob("{$directory}/*") ?: []);

    expect(Artisan::call('strategy:import', ['path' => base_path('catalog/strategies')]))->toBe(0)
        ->and(Artisan::output())->toContain('ema-cross-scalp-long@v1 saved');
    $count = StrategyVersion::query()->count();

    expect(Artisan::call('strategy:export', ['path' => $directory]))->toBe(0);
    foreach (StrategyVersion::query()->get() as $version) {
        expect(file_get_contents("{$directory}/{$version->strategy}.v{$version->version}.strategy.yaml"))->toBe($version->document);
    }

    expect(Artisan::call('strategy:import', ['path' => base_path('catalog/strategies')]))->toBe(0)
        ->and(Artisan::output())->toContain('ema-cross-scalp-long@v1 unchanged')
        ->and(StrategyVersion::query()->count())->toBe($count)
        ->and($count)->toBe(count(glob(base_path('catalog/strategies/*.strategy.yaml')) ?: []));
});
