<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\AccountRuntime;
use App\Execution\Contracts\Notifier;
use App\Execution\Contracts\Venue;
use App\Execution\Data\CommandKind;
use App\Execution\Data\EngineMode;
use App\Execution\Data\EngineRecord;
use App\Execution\Journal\ReplayDivergence;
use App\Execution\Models\Account;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\EngineCommandRow;
use App\Execution\Models\ExchangeKey;
use App\Execution\Venues\BinanceVenue;
use App\Market\Binance\BinanceApiError;
use App\Market\Binance\BinanceRequestFailed;
use App\Market\Binance\SignedBinanceClient;
use App\Market\Binance\Sleeper;
use App\Market\Contracts\CandleFeed;
use App\Market\Data\MarketType;
use App\Support\Asset;
use App\Support\Clock\Clock;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\Factory as HttpFactory;
use InvalidArgumentException;

/**
 * Runs one paper or testnet account (docs/engine.md, section 9). A testnet account starts from the exchange as it is, halts
 * on a clock more than a second off Binance's or a bad key, and stops on a venue error for its supervisor to restart.
 * A paper account replays every minute it already handled, with each command
 * at its event, and stops on the first record that differs from the journal (D-68); then it handles each new minute
 * once every followed instrument has it (D-67), applying the commands queued since, and publishes the account.
 */
final class RunEngine extends Command
{
    /** Minutes of silence after which the feed is stale: two of the 1-minute timeframe the engine reads. */
    private const int STALE_MINUTES = 2;

    /** docs/risk-policy.md, section 3: clock skew halts the account above 1 second. */
    private const int MAX_SKEW_MS = 1000;

    protected $signature = 'engine:run
        {account : The account name}
        {--once : Handle the minutes already stored, then stop}
        {--until= : Stop after the minute closing at this time, UTC}
        {--poll=2 : Seconds between looks at the database}';

    protected $description = 'Run an account\'s engine on the live feed: paper now, testnet with its venue, live in phase 6';

    public function handle(Repository $config, CandleFeed $feed, Clock $clock, Sleeper $sleeper, Notifier $notifier, HttpFactory $http): int
    {
        $name = (string) $this->argument('account');
        $account = Account::query()->where('name', $name)->first();

        if ($account === null) {
            $this->error("No account {$name}; create one with account:create.");

            return self::FAILURE;
        }

        if ($account->mode === EngineMode::Live) {
            $this->error("{$account->name} is live: live trading needs phase 6 and the operator's explicit approval (D-16).");

            return self::FAILURE;
        }

        $client = null;
        $exchange = null;

        if ($account->mode === EngineMode::Testnet) {
            $keys = ExchangeKey::query()->where('account_id', $account->id)->first();

            if ($keys === null) {
                $this->error("{$account->name} has no key: store one with php artisan account:keys {$account->name}.");

                return self::FAILURE;
            }

            $url = fn (string $key): string => is_string($value = $config->get("exchanges.binance.testnet.{$key}")) ? $value : throw new InvalidArgumentException("exchanges.binance.testnet.{$key} must be a URL.");
            $client = new SignedBinanceClient($http, $clock, $url('spot'), $url('perp'), $keys->api_key, $keys->api_secret);
            $exchange = function (array $symbols) use ($client, $account, $clock): Venue {
                $venue = new BinanceVenue($client, array_values(array_filter($symbols, fn (mixed $s): bool => $s instanceof Symbol)), Asset::of($account->quote), $clock->now());

                if ($venue->openOrders() !== [] || $venue->positions() !== []) {
                    throw new InvalidArgumentException("{$account->name} has open orders or positions on testnet: close them there, then start again. A testnet engine starts from a clean account.");
                }

                return $venue;
            };
        }

        try {
            $runtime = new AccountRuntime($account, $this->section($config, 'risk.account'), $this->section($config, 'simulation'), $clock, fn (EngineRecord $r) => $notifier->engineEvent($account->name, $r), $exchange);
        } catch (InvalidArgumentException|BinanceRequestFailed $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $until = is_string($this->option('until')) ? CarbonImmutable::parse($this->option('until'), 'UTC') : null;
        $last = $account->mode === EngineMode::Paper ? AccountSnapshot::query()->find($account->id)?->minute : null;
        $cursor = $runtime->feedFrom;
        $stale = false;

        try {
            if ($last !== null) {
                $this->line("Replaying {$account->name} from {$cursor->toIso8601ZuluString()} to {$last->toIso8601ZuluString()}...");
                $cursor = $this->handleMinutes($runtime, $feed, $cursor, $last, $account, replay: true);

                if ($runtime->log->replaying() || ! $cursor->equalTo($last)) {
                    throw new ReplayDivergence('The replay ended before the journal did: a minute the engine handled is missing from the candles now.');
                }

                $this->info('The replay matches the journal.');
                // What the replay rebuilt is published at once: the snapshot, and trades and equity lost from the tables (D-76).
                $runtime->publish($cursor);
            }

            while (true) {
                $now = $clock->now();

                if ($client !== null) {
                    $skew = abs($client->serverTime(MarketType::Spot)->getTimestampMs() - $now->getTimestampMs());

                    if ($skew > self::MAX_SKEW_MS) {
                        $this->halt($account, $clock);
                        $runtime->log->notice(new EngineRecord($now, 'clock_skew', "the local clock is {$skew} ms away from Binance's; the account is halted"));
                        $this->error('The clock is too far from Binance\'s: the account is halted.');

                        return self::FAILURE;
                    }
                }

                $to = $now->startOfMinute();
                $to = $until !== null && $until < $to ? $until : $to;
                $cursor = $this->handleMinutes($runtime, $feed, $cursor, $to, $account, replay: false);

                if ($cursor->lessThan($to)) {
                    $silent = intdiv($now->getTimestamp() - $cursor->getTimestamp(), 60) > self::STALE_MINUTES;

                    if ($silent !== $stale) {
                        $stale = $silent;
                        $runtime->log->notice(new EngineRecord($now, $stale ? 'breaker_tripped' : 'breaker_cleared', 'stale_data: the next minute closes at '.$cursor->addMinute()->toIso8601ZuluString()));
                    }
                } elseif ($stale) {
                    $stale = false;
                    $runtime->log->notice(new EngineRecord($now, 'breaker_cleared', 'stale_data: the feed caught up'));
                }

                if ($this->option('once') === true || ($until !== null && ! $cursor->lessThan($until))) {
                    break;
                }

                $sleeper->sleep(max(1, (int) (is_string($this->option('poll')) ? $this->option('poll') : '2')));
            }
        } catch (BinanceRequestFailed $failure) {
            // A testnet engine stops on a venue error; the supervisor restarts it from the exchange's state.
            if ($failure instanceof BinanceApiError && $failure->isAuthentication()) {
                $this->halt($account, $clock);
            }

            $runtime->log->notice(new EngineRecord($clock->now(), 'venue_error', $failure->getMessage()));
            $this->error($failure->getMessage());

            return self::FAILURE;
        } catch (ReplayDivergence $divergence) {
            $runtime->log->notice(new EngineRecord($clock->now(), 'replay_divergence', $divergence->getMessage()));
            $this->error($divergence->getMessage().' The account stays stopped until the operator looks (D-68).');

            return self::FAILURE;
        }

        $this->info("{$account->name}: handled to {$cursor->toIso8601ZuluString()}.");

        return self::SUCCESS;
    }

    /** Handles minutes closing after $cursor and at most at $to, in order, stopping at the first that is not complete. */
    private function handleMinutes(AccountRuntime $runtime, CandleFeed $feed, CarbonImmutable $cursor, CarbonImmutable $to, Account $account, bool $replay): CarbonImmutable
    {
        if (! $cursor->lessThan($to)) {
            return $cursor;
        }

        foreach ($feed->minutes($runtime->symbols, $cursor, $to) as $minute) {
            if (! $minute->closedAt->equalTo($cursor->addMinute()) || count($minute->candles) < count($runtime->symbols)) {
                if ($replay) {
                    throw new ReplayDivergence("The minute closing at {$cursor->addMinute()->toIso8601ZuluString()} was handled before and is incomplete now.");
                }

                break;
            }

            $rows = $replay
                ? EngineCommandRow::query()->where('account_id', $account->id)->where('applied_event_at', $minute->closedAt)->orderBy('id')->get()
                : EngineCommandRow::query()->where('account_id', $account->id)->whereNull('applied_at')->orderBy('id')->get();
            $runtime->step($minute, array_values($rows->map(fn (EngineCommandRow $row) => $row->command())->all()));

            if (! $replay) {
                foreach ($rows as $row) {
                    $row->update(['applied_at' => $minute->closedAt, 'applied_event_at' => $minute->closedAt]);
                }

                $runtime->publish($minute->closedAt);
            }

            $cursor = $minute->closedAt;
        }

        return $cursor;
    }

    private function halt(Account $account, Clock $clock): void
    {
        EngineCommandRow::query()->create(['account_id' => $account->id, 'kind' => CommandKind::Halt, 'strategy' => null, 'issued_by' => 'engine', 'created_at' => $clock->now()]);
    }

    /** @return array<array-key, mixed> */
    private function section(Repository $config, string $key): array
    {
        $section = $config->get($key);

        return is_array($section) ? $section : throw new InvalidArgumentException("Configuration {$key} must be an array.");
    }
}
