<?php

declare(strict_types=1);

use App\Execution\Models\EngineCommandRow;
use App\Http\Controllers\ReauthController;
use App\Livewire\AccountScreen;
use App\Livewire\ChartScreen;
use App\Livewire\HaltButton;
use App\Livewire\StrategiesScreen;
use App\Market\CandleRepository;
use App\Market\Data\Candle;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\FormingCandles;
use App\Market\Jobs\ImportMarketData;
use App\Market\Models\Instrument;
use App\Market\Models\MarketJob;
use App\Models\User;
use App\Simulation\Models\SimulationJob;
use App\Strategy\Dsl\Functions\Library;
use App\Strategy\Versions\StrategyVersion;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Testing\PendingCommand;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Tests\Support\CandleFactory;

uses(RefreshDatabase::class);

// Accounts follow stored instruments only (D-75).
beforeEach(fn () => Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-11-01', 'UTC')));

it('sends a visitor to the login, and asks a TOTP code after the password', function (): void {
    operator();

    foreach (['/chart', '/strategies', '/account'] as $path) {
        web()->get($path)->assertRedirect('/login');
    }

    web()->get('/login')->assertOk()->assertSee('Log in');
    web()->post('/login', ['email' => 'op@example.test', 'password' => 'a long test password'])->assertRedirect('/two-factor-challenge');
    web()->post('/two-factor-challenge', ['code' => (new Google2FA)->getCurrentOtp(OPERATOR_SECRET)])->assertRedirect('/account');
    web()->get('/chart')->assertOk()->assertSee('Halt');
});

it('creates the one operator with a confirmed authenticator, and no second one', function (): void {
    app()->instance(TwoFactorAuthenticationProvider::class, new class implements TwoFactorAuthenticationProvider
    {
        public function generateSecretKey(): string
        {
            return OPERATOR_SECRET;
        }

        public function qrCodeUrl($companyName, $companyEmail, $secret): string
        {
            return 'otpauth://totp/test';
        }

        public function verify($secret, $code): bool
        {
            return $code === '123456';
        }
    });

    $command = web()->artisan('user:create', ['email' => 'op@example.test']);

    if (! $command instanceof PendingCommand) {
        throw new LogicException('Expected a pending command.');
    }

    $command->expectsQuestion('Password (hidden, at least 12 characters)', 'a long test password')
        ->expectsQuestion('The same password again', 'a long test password')
        ->expectsOutputToContain('Scan this code with your authenticator app')
        ->expectsOutputToContain('█')
        ->expectsOutputToContain('If the app cannot scan, type this key: '.OPERATOR_SECRET)
        ->doesntExpectOutputToContain('otpauth://')
        ->expectsQuestion('A code from the app', '123456')
        ->assertSuccessful()
        ->run();

    expect(User::query()->sole()->two_factor_confirmed_at)->not->toBeNull()
        ->and(Artisan::call('user:create', ['email' => 'other@example.test']))->toBe(1);
});

it('halts every account from the button on every screen, without re-authentication', function (): void {
    web()->actingAs(operator());
    Artisan::call('account:create', ['name' => 'paper']);
    Artisan::call('account:create', ['name' => 'testnet', '--mode' => 'testnet']);

    Livewire::test(HaltButton::class)->call('halt')->assertSee('Halt queued for paper, testnet.');

    expect(EngineCommandRow::query()->pluck('kind')->map->value->all())->toBe(['halt', 'halt']);
});

it('asks for the password and a fresh code before resuming or flattening, not before halting', function (): void {
    web()->actingAs(operator());
    Artisan::call('account:create', ['name' => 'paper']);

    Livewire::test(AccountScreen::class)->call('command', 'flatten')->assertRedirect(route('reauth'));
    Livewire::test(AccountScreen::class)->call('command', 'halt')->assertSee('Halt queued for paper');

    web()->post('/confirm', ['password' => 'a long test password', 'code' => '000000'])->assertSessionHasErrors('password');
    web()->post('/confirm', ['password' => 'a long test password', 'code' => (new Google2FA)->getCurrentOtp(OPERATOR_SECRET)])->assertRedirect();

    session()->put(ReauthController::SESSION_KEY, time());
    Livewire::test(AccountScreen::class)->call('command', 'flatten')->assertSee('Flatten queued for paper');

    expect(EngineCommandRow::query()->orderBy('id')->pluck('kind')->map->value->all())->toBe(['halt', 'flatten']);
});

it('draws candles and layers with their types, and says why an expression cannot be drawn', function (): void {
    web()->actingAs(operator());
    backtestFixture(days: 3);

    $component = Livewire::test(ChartScreen::class)->set('timeframe', '15m')->set('expression', 'rsi(14)')->call('add');
    $payload = $component->viewData('payload');

    expect($component->get('layers'))->toBe(['ema(9)', 'ema(21)', 'rsi(14)'])
        ->and(data_get($payload, 'types'))->toBe(['ema(9)' => 'a price series', 'ema(21)' => 'a price series', 'rsi(14)' => 'a value series'])
        ->and(data_get($payload, 'candles'))->toHaveCount(288)
        ->and(data_get($payload, 'profile'))->toHaveCount(24)
        ->and(ChartScreen::template(Library::get('bb')))->toBe('bb().upper');

    Livewire::test(ChartScreen::class)->set('layers', ['ema(9'])->assertSee('expected ) to close the call to ema');
});

it('offers every instrument to the chart as a list to search, and says when the one picked has no candles', function (): void {
    web()->actingAs(operator());
    backtestFixture(days: 2);
    Instrument::register('binance', MarketType::Perp, Symbol::parse('DOGE/USDT:USDT'), CarbonImmutable::parse('2026-10-01', 'UTC'));

    Livewire::test(ChartScreen::class)
        ->assertSeeHtml('list="chart-instruments"')
        ->assertSeeHtml('<option value="BTC/USDT"></option>')
        ->assertSeeHtml('<option value="DOGE/USDT:USDT"></option>')
        ->assertDontSeeHtml('wire:model.live="symbol"')
        ->set('symbol', 'DOGE/USDT:USDT')
        ->assertSee('DOGE/USDT:USDT has no stored candles.');
});

/** The spot fixture of two days, three closed minutes of 2026-10-03, and the minute 00:03 forming (D-87). */
function liveChartFixture(bool $forming = true): Instrument
{
    $btc = backtestFixture(days: 2);
    app(CandleRepository::class)->upsert($btc, CandleSource::Archive, CandleFactory::walk('2026-10-03 00:00:00', 3, seed: 5));

    if ($forming) {
        app(FormingCandles::class)->put($btc, Candle::fromStrings(CarbonImmutable::parse('2026-10-03 00:03:00', 'UTC'), Timeframe::OneMinute, '100', '999999', '1', '123.45', '2', '200', 3, '1'), app(Clock::class)->now()->subSecond());
    }

    return $btc;
}

/** The chart's live query (D-87) as the screen renders it for a timeframe. */
function chartLiveQuery(string $timeframe): string
{
    $live = data_get(Livewire::test(ChartScreen::class)->set('timeframe', $timeframe)->viewData('payload'), 'live');

    return http_build_query(is_array($live) ? $live : throw new LogicException('The payload has no live query.'));
}

it('moves the forming candle every second without recomputing the chart (D-87)', function (): void {
    web()->actingAs(operator());
    $btc = liveChartFixture();
    $minutes = app(CandleRepository::class)->range($btc, Timeframe::OneMinute, CarbonImmutable::parse('2026-10-03 00:00:00', 'UTC'), CarbonImmutable::parse('2026-10-03 00:02:00', 'UTC'));
    $volume = array_reduce($minutes, fn (Decimal $sum, Candle $c): Decimal => $sum->plus($c->volume), Decimal::of('2'));
    $query = chartLiveQuery('1h');

    $response = web()->getJson('/chart/live?'.$query)->assertOk();
    $barVolume = $response->json('bar.volume');

    expect($query)->toBe(http_build_query(['symbol' => 'BTC/USDT', 'timeframe' => '1h', 'through' => '2026-10-03T00:02:00+00:00']))
        ->and($response->json('closed'))->toBeFalse()
        ->and($response->json('bar.time'))->toBe('2026-10-03T00:00:00Z')
        ->and($response->json('bar.open'))->toBe((string) $minutes[0]->open)
        ->and($response->json('bar.high'))->toBe('999999')
        ->and($response->json('bar.close'))->toBe('123.45')
        ->and(is_string($barVolume) && Decimal::of($barVolume)->equals($volume))->toBeTrue()
        ->and($response->json('status'))->toStartWith('Live');
});

it('says when a candle of the chart timeframe has closed, and the chart then has it with its layers', function (): void {
    web()->actingAs(operator());
    $btc = liveChartFixture();
    $query = chartLiveQuery('1m');
    app(CandleRepository::class)->upsert($btc, CandleSource::Stream, CandleFactory::walk('2026-10-03 00:03:00', 1, seed: 6));

    web()->getJson('/chart/live?'.$query)->assertOk()->assertJson(['closed' => true]);
    $payload = Livewire::test(ChartScreen::class)->set('timeframe', '1m')->viewData('payload');
    $candles = data_get($payload, 'candles');

    expect(data_get($payload, 'candles.*.time'))->toContain('2026-10-03T00:03:00Z')
        ->and(data_get($payload, 'series.ema(9)'))->toHaveCount(is_array($candles) ? count($candles) : -1);
});

it('says the chart is not live when the stream does not write, and only to the operator', function (): void {
    liveChartFixture(forming: false);
    $query = ['symbol' => 'BTC/USDT', 'timeframe' => '1m', 'through' => '2026-10-03T00:02:00+00:00'];

    web()->getJson('/chart/live?'.http_build_query($query))->assertUnauthorized();
    web()->actingAs(operator());
    $response = web()->getJson('/chart/live?'.http_build_query($query))->assertOk();

    expect($response->json('bar'))->toBeNull()
        ->and($response->json('status'))->toContain('market:stream is not running');
});

/** ETH/USDT spot with stored minutes only from 2026-10-03 00:52, as when market:stream started without history. */
function partialHistory(): Instrument
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-03 01:30:00', 'UTC')));
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-10-03', 'UTC'));
    app(CandleRepository::class)->upsert($eth, CandleSource::Stream, CandleFactory::walk('2026-10-03 00:52:00', 30, seed: 3));

    return $eth;
}

it('explains missing history and offers to fill it, instead of failing (D-88)', function (): void {
    web()->actingAs(operator());
    partialHistory();
    Bus::fake();

    $component = Livewire::test(ChartScreen::class)->set('symbol', 'ETH/USDT')
        ->assertSee('ETH/USDT has stored minutes only from 2026-10-03 00:52')
        ->assertSee('Fill history from 2026-09-')
        ->call('fillHistory')
        ->assertSee('queued');
    $job = MarketJob::query()->sole();

    expect($job->symbol)->toBe('ETH/USDT')
        ->and($job->candles)->toBeTrue()
        ->and($job->scalping)->toBeFalse()
        ->and($job->from->format('Y-m'))->toBe('2026-09')
        ->and($component->get('error'))->toBe('');
    Bus::assertDispatched(ImportMarketData::class);
});

it('explains an empty window too: minutes stored only in the hour still forming', function (): void {
    web()->actingAs(operator());
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-03 01:30:00', 'UTC')));
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-10-03', 'UTC'));
    app(CandleRepository::class)->upsert($eth, CandleSource::Stream, CandleFactory::walk('2026-10-03 01:05:00', 20, seed: 3));

    Livewire::test(ChartScreen::class)->set('symbol', 'ETH/USDT')
        ->assertSee('ETH/USDT has no stored minutes from 2026-09-')
        ->assertSee('Fill history from 2026-09-');
});

it('does not draw a window whose last hours are missing, as while an import is still running', function (): void {
    web()->actingAs(operator());
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-03 03:30:00', 'UTC')));
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-10-03', 'UTC'));
    app(CandleRepository::class)->upsert($eth, CandleSource::Archive, CandleFactory::walk('2026-10-03 00:00:00', 60, seed: 3));
    app(CandleRepository::class)->upsert($eth, CandleSource::Stream, CandleFactory::walk('2026-10-03 03:05:00', 20, seed: 4));

    Livewire::test(ChartScreen::class)->set('symbol', 'ETH/USDT')
        ->assertSee('ETH/USDT misses the minutes from 2026-10-03 01:00 to 2026-10-03 02:59')
        ->assertSee('Fill history from 2026-10-03');
});

it('names a minute missing inside the window, and draws once the history is complete', function (): void {
    web()->actingAs(operator());
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-03 03:30:00', 'UTC')));
    $eth = Instrument::register('binance', MarketType::Spot, Symbol::parse('ETH/USDT'), CarbonImmutable::parse('2026-10-03', 'UTC'));
    $minutes = CandleFactory::walk('2026-10-03 00:00:00', 180, seed: 3);
    app(CandleRepository::class)->upsert($eth, CandleSource::Archive, array_values(array_filter($minutes, fn (Candle $c): bool => $c->openTime->format('H:i') !== '01:30')));

    Livewire::test(ChartScreen::class)->set('symbol', 'ETH/USDT')->assertSee('2026-10-03 01:30')->assertSee('Fill history from');

    app(CandleRepository::class)->upsert($eth, CandleSource::Rest, $minutes);
    $payload = Livewire::test(ChartScreen::class)->set('symbol', 'ETH/USDT')->assertDontSee('Fill history from')->viewData('payload');

    expect(data_get($payload, 'candles.*.time'))->toBe(['2026-10-03T00:00:00Z', '2026-10-03T01:00:00Z', '2026-10-03T02:00:00Z']);
});

it('compiles as the operator types, reads the strategy back, saves it and runs it in the background', function (): void {
    web()->actingAs(operator());
    backtestFixture(days: 3);

    Livewire::test(StrategiesScreen::class)
        ->set('document', str_replace('1.5 * atr(14)', 'close > 2%', EMA_CROSS_DOCUMENT))
        ->assertSee('cannot compare a price series with a percent')
        ->set('document', EMA_CROSS_DOCUMENT)
        ->assertSee('On BTC/USDT spot, every time a 5-minute candle closes')
        ->call('save')
        ->assertSee('Saved as ema-cross-test@v1.')
        ->set('from', '2026-10-02')->set('to', '2026-10-04')
        ->call('run', 'backtest')
        ->assertSee('queued');

    expect(StrategyVersion::query()->sole()->author)->toBe('operator')
        ->and(SimulationJob::query()->sole()->status)->toBe('done');
});
