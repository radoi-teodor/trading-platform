<?php

declare(strict_types=1);

use App\Market\Archive\ArchiveCatalog;
use App\Market\Data\InstrumentStatus;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function fakeArchiveListing(): void
{
    Http::fake(function (Request $request) {
        if (str_contains($request->url(), 'fapi/v1/exchangeInfo')) {
            return Http::response(binanceFixture('perp-exchange-info.hand-written.json'));
        }

        return Http::response(binanceFixture(str_contains($request->url(), 'marker=') ? 'archive-listing-um-klines-2.xml' : 'archive-listing-um-klines-1.xml'));
    });
}

it('lists every USD-M perpetual that ever had an archive, page by page (D-89)', function (): void {
    fakeArchiveListing();

    $symbols = app(ArchiveCatalog::class)->perpetuals();

    // Quarterly contracts (BTCUSDT_250328) are not perpetuals; USDC and BUSD pairs are kept, the universe rule
    // decides about them.
    expect($symbols)->toBe(['1000BONKUSDC', '1000SHIBUSDT', 'BTCUSDT', 'ETHBUSD', 'ETHUSDT', 'SRMUSDT']);
    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'marker=data%2Ffutures%2Fum%2Fmonthly%2Fklines%2FBTCUSDT_250328%2F'));
});

it('registers the perpetuals the exchange no longer lists as delisted, history only', function (): void {
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-11-09 12:00:00', 'UTC')));
    fakeArchiveListing();

    expect(Artisan::call('market:instruments', ['market' => 'perp', '--history' => true]))->toBe(0)
        ->and(Artisan::output())->toContain('4 perpetuals known only from the archives, registered as delisted');

    $srm = Instrument::query()->where('market', MarketType::Perp)->where('venue_symbol', 'SRMUSDT')->sole();

    expect($srm->symbol)->toBe('SRM/USDT:USDT')
        ->and($srm->status)->toBe(InstrumentStatus::Delisted)
        ->and(Instrument::query()->where('venue_symbol', 'BTCUSDT')->sole()->status)->toBe(InstrumentStatus::Trading)
        ->and(Instrument::query()->where('venue_symbol', '1000SHIBUSDT')->sole()->symbol)->toBe((string) Symbol::parse('1000SHIB/USDT:USDT'))
        ->and(Instrument::query()->where('venue_symbol', 'ETHBUSD')->sole()->symbol)->toBe('ETH/BUSD:BUSD');
});
