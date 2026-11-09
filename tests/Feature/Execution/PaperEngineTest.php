<?php

declare(strict_types=1);

use App\Execution\Models\Account;
use App\Execution\Models\AccountEquity;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\Deployment;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\JournalEntry;
use App\Notifications\SendTelegram;
use App\Simulation\StrategyLibrary;
use App\Support\Clock\Clock;
use App\Support\Clock\SimClock;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/** A paper account from 2026-10-02 with the test strategy deployed, the clock after the stored candles. */
function paperAccount(): Account
{
    backtestFixture(days: 3);
    app()->instance(Clock::class, new SimClock(CarbonImmutable::parse('2026-10-05 00:00', 'UTC')));
    $version = app(StrategyLibrary::class)->versions()->save(EMA_CROSS_DOCUMENT, 'operator')->version ?? throw new RuntimeException('No version.');
    Artisan::call('account:create', ['name' => 'paper', '--start' => '2026-10-02 00:00']);
    $account = Account::query()->sole();
    Deployment::query()->create(['account_id' => $account->id, 'strategy_version_id' => $version->id, 'parameters' => [], 'active' => true, 'reason' => 'test', 'created_at' => '2026-10-02']);

    return $account;
}

/**
 * @param  array<string, string|bool>  $options
 * @return array{int, string}
 */
function runPaper(array $options = []): array
{
    $exit = Artisan::call('engine:run', ['account' => 'paper', '--once' => true, ...$options]);

    return [$exit, Artisan::output()];
}

it('handles the stored minutes, publishes the account and journals its decisions', function (): void {
    $account = paperAccount();
    [$exit, $output] = runPaper(['--until' => '2026-10-02 12:00']);
    $snapshot = AccountSnapshot::query()->sole();

    expect($exit)->toBe(0)
        ->and($output)->toContain('paper: handled to 2026-10-02T12:00:00Z')
        ->and($snapshot->minute->toIso8601ZuluString())->toBe('2026-10-02T12:00:00Z')
        ->and($snapshot->payload['mode'] ?? null)->toBe('paper')
        ->and(JournalEntry::query()->where('account_id', $account->id)->where('kind', 'decision')->count())->toBeGreaterThan(0);
});

it('refuses to run an account a universe strategy is deployed on: they run in simulation only so far (D-92)', function (): void {
    $account = paperAccount();
    $version = app(StrategyLibrary::class)->versions()->save(UNIVERSE_FIXTURE_DOCUMENT, 'operator')->version ?? throw new RuntimeException('No version.');
    Deployment::query()->create(['account_id' => $account->id, 'strategy_version_id' => $version->id, 'parameters' => [], 'active' => true, 'reason' => 'test', 'created_at' => '2026-10-02']);

    [$exit, $output] = runPaper(['--until' => '2026-10-02 12:00']);

    expect($exit)->toBe(1)
        ->and($output)->toContain('xs-fixture@v1 trades a universe: an account cannot run one yet; it runs in backtests, grids and gates (D-92).')
        ->and(AccountSnapshot::query()->count())->toBe(0)
        ->and(JournalEntry::query()->where('account_id', $account->id)->count())->toBe(0);
});

it('applies a command at the next minute, and replays it there after a restart (D-68)', function (): void {
    $account = paperAccount();
    Bus::fake([SendTelegram::class]);
    runPaper(['--until' => '2026-10-02 12:00']);

    expect(Artisan::call('engine:halt', ['account' => 'paper']))->toBe(0);

    runPaper(['--until' => '2026-10-02 18:00']);
    $command = EngineCommandRow::query()->sole();
    $journal = JournalEntry::query()->orderBy('id')->get()->map(fn (JournalEntry $e): array => $e->payload)->all();

    expect($command->applied_event_at?->toIso8601ZuluString())->toBe('2026-10-02T12:01:00Z')
        ->and(collect($journal)->firstWhere('kind', 'command'))->toBe(['time' => '2026-10-02T12:01:00Z', 'kind' => 'command', 'detail' => 'halt by cli'])
        ->and(AccountSnapshot::query()->sole()->payload['halt'] ?? null)->toBe('operator');
    Bus::assertDispatched(SendTelegram::class, fn (SendTelegram $job): bool => str_contains($job->text, 'paper command, 2026-10-02 12:01 UTC: halt by cli'));

    [$exit, $output] = runPaper(['--until' => '2026-10-02 18:00']);

    expect($exit)->toBe(0)
        ->and($output)->toContain('The replay matches the journal.')
        ->and(JournalEntry::query()->orderBy('id')->get()->map(fn (JournalEntry $e): array => $e->payload)->all())->toBe($journal);
});

it('stops and says so when the replay differs from the journal', function (): void {
    $account = paperAccount();
    runPaper(['--until' => '2026-10-02 12:00']);
    $first = JournalEntry::query()->where('kind', 'decision')->orderBy('id')->firstOrFail();
    $first->update(['payload' => [...$first->payload, 'close' => '1']]);

    [$exit, $output] = runPaper(['--until' => '2026-10-02 12:00']);

    expect($exit)->toBe(1)
        ->and($output)->toContain('Replay diverged at journal record')
        ->and(JournalEntry::query()->where('kind', 'notice')->sole()->payload['kind'])->toBe('replay_divergence');
});

it('waits at a missing minute instead of skipping it, and journals the stale feed', function (): void {
    paperAccount();
    DB::table('candles')->where('open_time', '2026-10-02 13:00:00+00')->delete();

    [$exit, $output] = runPaper(['--until' => '2026-10-02 18:00']);

    expect($exit)->toBe(0)
        ->and($output)->toContain('handled to 2026-10-02T13:00:00Z')
        ->and(JournalEntry::query()->where('kind', 'notice')->sole()->payload['detail'])->toBe('stale_data: the next minute closes at 2026-10-02T13:01:00Z');
});

it('refuses a live account the operator has not approved, and accounts it does not know', function (): void {
    paperAccount();
    Account::query()->sole()->update(['mode' => 'live']);

    expect(Artisan::call('engine:run', ['account' => 'paper', '--once' => true]))->toBe(1)
        ->and(Artisan::output())->toContain('paper is not approved for live trading')
        ->and(Artisan::call('engine:run', ['account' => 'other', '--once' => true]))->toBe(1)
        ->and(Artisan::call('account:create', ['name' => 'real', '--mode' => 'live']))->toBe(1);
});

it('publishes its closed trades and hourly equity, and a restart writes the same rows again (D-76)', function (): void {
    // The fixture's candles end at 2026-10-04 00:00: one point an hour from the account's start to there.
    $account = paperAccount();
    runPaper(['--until' => '2026-10-04 23:00']);

    $trades = AccountTrade::query()->where('account_id', $account->id)->orderBy('seq')->get()->map(fn (AccountTrade $t): array => [...$t->only(['seq', 'strategy', 'label', 'symbol', 'side', 'net', 'fees']), 'closed_at' => $t->closed_at->toIso8601ZuluString()])->all();
    $equity = AccountEquity::query()->where('account_id', $account->id)->orderBy('at')->get()->map(fn (AccountEquity $e): array => [$e->at->toIso8601ZuluString(), $e->equity])->all();

    expect($trades)->not->toBe([])
        ->and($trades[0]['label'])->toBe('ema-cross-test@v1')
        ->and($equity)->toHaveCount(49)
        ->and($equity[0][0])->toBe('2026-10-02T00:00:00Z')
        ->and($equity[48][0])->toBe('2026-10-04T00:00:00Z')
        ->and($equity[48][1])->toBe(AccountSnapshot::query()->sole()->payload['equity'] ?? null);

    // Ending the rows and running again: the replay rebuilds them, identical.
    AccountTrade::query()->delete();
    AccountEquity::query()->delete();
    runPaper(['--until' => '2026-10-04 23:00']);
    runPaper(['--until' => '2026-10-04 23:00']);

    expect(AccountTrade::query()->orderBy('seq')->get()->map(fn (AccountTrade $t): array => [...$t->only(['seq', 'strategy', 'label', 'symbol', 'side', 'net', 'fees']), 'closed_at' => $t->closed_at->toIso8601ZuluString()])->all())->toBe($trades)
        ->and(AccountEquity::query()->orderBy('at')->get()->map(fn (AccountEquity $e): array => [$e->at->toIso8601ZuluString(), $e->equity])->all())->toBe($equity);
});
