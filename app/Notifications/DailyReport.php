<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Execution\Models\Account;
use App\Execution\Models\AccountEquity;
use App\Execution\Models\AccountSnapshot;
use App\Execution\Models\AccountTrade;
use App\Execution\Models\JournalEntry;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use Carbon\CarbonImmutable;

/**
 * The daily report of one account (D-77): equity and its change, the trades closed, halts and commands, breakers,
 * stale data and mismatches of the last 24 hours, from what its engine published (D-76) and journaled.
 */
final readonly class DailyReport
{
    /** Engine events that stop or limit trading, reported one by one with the operator's commands. */
    private const array HALTS = [
        'command', 'daily_loss_halt', 'weekly_loss_halt', 'drawdown_halt', 'drawdown_flatten', 'liquidation',
        'reconciliation_halt', 'replay_divergence', 'venue_error', 'clock_skew',
    ];

    public function __construct(private Clock $clock) {}

    public function forAccount(Account $account): string
    {
        $now = $this->clock->now();
        $since = $now->subDay();
        $lines = ["Trading X daily report: {$account->name} ({$account->mode->value}), {$now->format('Y-m-d H:i')} UTC"];
        $snapshot = AccountSnapshot::query()->find($account->id);

        if ($snapshot === null) {
            return $lines[0]."\nIts engine has not run yet.";
        }

        $lines[] = $this->equity($account, $snapshot, $since);
        $lines[] = $this->trades($account, $since, $now);
        [$halts, $breakers, $stale, $mismatches] = $this->journal($account, $since, $now);
        $lines[] = 'Halts and commands: '.($halts === [] ? 'none' : implode('; ', $halts));
        $lines[] = 'Breakers: '.($breakers === [] ? 'none' : implode(', ', array_map(fn (string $k, int $n): string => "{$k} {$n}", array_keys($breakers), $breakers)));
        $lines[] = "Stale data: {$stale}. Reconciliation mismatches: {$mismatches}.";
        $lines[] = "Last minute handled: {$snapshot->minute->format('Y-m-d H:i')} UTC";

        return implode("\n", $lines);
    }

    private function equity(Account $account, AccountSnapshot $snapshot, CarbonImmutable $since): string
    {
        $value = $snapshot->payload['equity'] ?? null;
        $now = Decimal::of(is_string($value) ? $value : '0');
        $before = AccountEquity::query()->where('account_id', $account->id)->where('at', '<=', $since)->orderByDesc('at')->first();
        $line = 'Equity '.self::fixed($now)." {$account->quote}";

        if ($before === null) {
            return "{$line}, 24 hours: no earlier point yet";
        }

        $then = Decimal::of($before->equity);
        $change = $now->minus($then);
        $percent = $then->isZero() ? Decimal::zero() : $change->times(Decimal::of(100))->dividedBy($then, 18);

        return "{$line}, 24 hours ".self::signed($change).' ('.self::signed($percent).' %)';
    }

    private function trades(Account $account, CarbonImmutable $since, CarbonImmutable $now): string
    {
        $trades = AccountTrade::query()->where('account_id', $account->id)->where('closed_at', '>', $since)->where('closed_at', '<=', $now)->get();

        if ($trades->isEmpty()) {
            return 'Trades closed: 0';
        }

        [$net, $fees, $funding] = [Decimal::zero(), Decimal::zero(), Decimal::zero()];
        $winning = 0;

        foreach ($trades as $trade) {
            $net = $net->plus(Decimal::of($trade->net));
            $fees = $fees->plus(Decimal::of($trade->fees));
            $funding = $funding->plus(Decimal::of($trade->funding));
            $winning += Decimal::of($trade->net)->isPositive() ? 1 : 0;
        }

        return "Trades closed: {$trades->count()}, {$winning} winning, net ".self::signed($net).', fees '.self::fixed($fees).', funding '.self::signed($funding);
    }

    /** @return array{list<string>, array<string, int>, int, int} */
    private function journal(Account $account, CarbonImmutable $since, CarbonImmutable $now): array
    {
        $halts = $breakers = [];
        $stale = $mismatches = 0;
        $entries = JournalEntry::query()->where('account_id', $account->id)->whereIn('kind', ['event', 'notice'])
            ->where('event_at', '>', $since)->where('event_at', '<=', $now)->orderBy('event_at')->orderBy('id')->get();

        foreach ($entries as $entry) {
            $kind = is_string($entry->payload['kind'] ?? null) ? $entry->payload['kind'] : '';
            $detail = is_string($entry->payload['detail'] ?? null) ? $entry->payload['detail'] : '';

            if (in_array($kind, self::HALTS, true)) {
                $halts[] = $entry->event_at->format('H:i').' '.str_replace('_', ' ', $kind).": {$detail}";
            } elseif ($kind === 'reconciliation_mismatch') {
                $mismatches++;
            } elseif ($kind === 'breaker_tripped' && str_starts_with($detail, 'stale_data')) {
                $stale++;
            } elseif ($kind === 'breaker_tripped') {
                // "volatility_shock BTC/USDT: ..." is counted by breaker and instrument.
                $key = strstr($detail, ':', true) ?: $detail;
                $breakers[$key] = ($breakers[$key] ?? 0) + 1;
            }
        }

        arsort($breakers);

        return [$halts, $breakers, $stale, $mismatches];
    }

    /** An amount with two decimals, as 1234.50. */
    private static function fixed(Decimal $value): string
    {
        $text = (string) $value->roundedTo(2);
        [$whole, $fraction] = array_pad(explode('.', $text, 2), 2, '');

        return $whole.'.'.str_pad($fraction, 2, '0');
    }

    private static function signed(Decimal $value, bool $plus = true): string
    {
        return ($plus && ! $value->isNegative() ? '+' : '').self::fixed($value);
    }
}
