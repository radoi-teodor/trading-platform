<?php

declare(strict_types=1);

use App\Execution\Accounts\OpenAccount;
use App\Execution\Data\CommandKind;
use App\Execution\Models\Account;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\Deployment;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\JournalEntry;
use App\Http\Controllers\ReauthController;
use App\Livewire\StrategiesScreen;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Notifications\SendTelegram;
use App\Simulation\Ladder\Ladder;
use App\Simulation\Models\StrategyGateReport;
use App\Simulation\StrategyLibrary;
use App\Strategy\Versions\StrategyStage;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/** A version with a passing gate report, deployed in paper since 2026-10-01, and a live account. */
function ladderSetup(): Deployment
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-01', 'UTC')));
    Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), CarbonImmutable::parse('2026-09-01', 'UTC'));
    $version = app(StrategyLibrary::class)->versions()->save(EMA_CROSS_DOCUMENT, 'operator')->version ?? throw new RuntimeException('No version.');
    StrategyGateReport::query()->create(['strategy_version_id' => $version->id, 'passed' => true, 'created_at' => '2026-09-30', 'report' => [
        'out_of_sample' => ['trades' => 100, 'expectancy' => '10', 'interval' => ['4', '16']],
        'monte_carlo' => ['results' => ['trades' => ['drawdown' => ['p95' => '0.05']]]],
    ]]);
    $paper = app(OpenAccount::class)->open('paper', 'paper', '10000', '0', ['BTC/USDT'], null);
    app(OpenAccount::class)->open('live', 'live', '0', '0', ['BTC/USDT'], null, confirmed: true);

    return Deployment::query()->create(['account_id' => $paper->id, 'strategy_version_id' => $version->id, 'parameters' => [], 'active' => true, 'reason' => 'test', 'created_at' => '2026-10-01']);
}

/** Trades of the deployment's strategy on its account, one a day from 2026-10-01, with these results. */
function ladderTrades(Deployment $deployment, string ...$nets): void
{
    foreach (array_values($nets) as $i => $net) {
        AccountTrade::query()->create([
            'account_id' => $deployment->account_id, 'seq' => $i, 'strategy' => 'abcd1234', 'label' => 'ema-cross-test@v1', 'symbol' => 'BTC/USDT', 'side' => 'buy',
            'opened_at' => CarbonImmutable::parse('2026-10-01 01:00', 'UTC')->addHours(20 * $i), 'closed_at' => CarbonImmutable::parse('2026-10-01 02:00', 'UTC')->addHours(20 * $i),
            'quantity' => '0.01', 'entry_price' => '80000', 'exit_price' => '80000', 'gross' => $net, 'fees' => '1.6', 'slippage' => '0', 'funding' => '0', 'net' => $net, 'r_multiple' => null, 'exit_reason' => 'signal',
        ]);
    }
}

function at(string $time): void
{
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse($time, 'UTC')));
}

it('offers live micro only after 30 days and 30 trades in paper, in band', function (): void {
    $deployment = ladderSetup();
    ladderTrades($deployment, ...array_merge(...array_fill(0, 10, ['20', '0'])));
    at('2026-10-20');

    expect(app(Ladder::class)->status($deployment)->missing)->toBe('19 of 30 days, 20 of 30 trades');
    expect(fn () => app(Ladder::class)->promote($deployment, 'ui'))->toThrow(InvalidArgumentException::class, 'Not yet: 19 of 30 days, 20 of 30 trades');
});

it('promotes a qualified paper deployment to live micro on the live account, effective 24 hours later', function (): void {
    $deployment = ladderSetup();
    ladderTrades($deployment, ...array_merge(...array_fill(0, 16, ['20', '0'])));
    at('2026-11-02 09:00');

    $status = app(Ladder::class)->status($deployment);
    expect($status->ready)->toBeTrue()->and($status->next)->toBe('live micro, a quarter of the size');

    [$micro] = app(Ladder::class)->promote($deployment, 'ui');

    expect($micro->account_id)->toBe(Account::query()->where('name', 'live')->sole()->id)
        ->and($micro->stage)->toBe('live_micro')
        ->and((string) $micro->size_multiplier)->toStartWith('0.25')
        ->and($micro->effective_at?->toIso8601ZuluString())->toBe('2026-11-03T09:00:00Z')
        ->and(Deployment::inForce($micro->account_id, CarbonImmutable::parse('2026-11-03 08:59', 'UTC'))->count())->toBe(0)
        ->and(Deployment::inForce($micro->account_id, CarbonImmutable::parse('2026-11-03 09:00', 'UTC'))->count())->toBe(1)
        ->and(StrategyStage::query()->orderByDesc('id')->value('stage'))->toBe('live_micro');
});

it('demotes a deployment out of band at once: ends it, pauses the strategy, journals and notifies', function (): void {
    Bus::fake([SendTelegram::class]);
    $deployment = ladderSetup();
    ladderTrades($deployment, '100', '-700');
    at('2026-10-05');

    $done = app(Ladder::class)->review();

    $deployment->refresh();
    expect($done)->toHaveCount(1)->and($done[0])->toContain('drawdown')
        ->and($deployment->active)->toBeFalse()
        ->and(StrategyStage::query()->orderByDesc('id')->value('stage'))->toBe('backtested')
        ->and(EngineCommandRow::query()->sole()->kind)->toBe(CommandKind::Pause)
        ->and(JournalEntry::query()->where('kind', 'notice')->sole()->payload['kind'] ?? null)->toBe('demotion');
    Bus::assertDispatched(SendTelegram::class, fn (SendTelegram $job): bool => str_contains($job->text, 'paper demotion'));
});

it('reviews every deployment once a day', function (): void {
    $deployment = ladderSetup();
    ladderTrades($deployment, '100', '-700');
    at('2026-10-05');

    expect(Artisan::call('strategy:review'))->toBe(0)
        ->and(Artisan::output())->toContain('ema-cross-test@v1 demoted to backtested on paper');

    $events = collect(app(Schedule::class)->events())->filter(fn (Event $e): bool => str_contains($e->command ?? '', 'strategy:review'));
    expect($events)->toHaveCount(1)->and($events->first()?->expression)->toBe('41 0 * * *');
});

it('promotes from the Strategies screen only after re-authentication', function (): void {
    $deployment = ladderSetup();
    ladderTrades($deployment, ...array_merge(...array_fill(0, 16, ['20', '0'])));
    at('2026-11-02 09:00');
    web()->actingAs(operator());

    Livewire::test(StrategiesScreen::class)->assertSee('live micro, a quarter of the size')->call('promoteDeployment', $deployment->id)->assertRedirect(route('reauth'));
    expect(Deployment::query()->count())->toBe(1);

    session()->put(ReauthController::SESSION_KEY, time());
    Livewire::test(StrategiesScreen::class)->call('promoteDeployment', $deployment->id)->assertSee('goes live micro from 2026-11-03 09:00 UTC');
    expect(Deployment::query()->where('stage', 'live_micro')->count())->toBe(1);
});
