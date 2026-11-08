<?php

declare(strict_types=1);

use App\Risk\Data\RiskProfile;
use App\Risk\RiskPolicy;
use App\Support\Number\Decimal;

/**
 * The limits of docs/risk-policy.md, section 1, read from the document itself: label => [default, ceiling], as
 * fractions. A change on one side without the other fails here (D-19: no code change raises a limit unnoticed).
 *
 * @return array<string, array{string, string}>
 */
function documentedLimits(): array
{
    $document = (string) file_get_contents(base_path('docs/risk-policy.md'));
    $section = explode('## 2.', explode('## 1. Account limits', $document)[1])[0];
    preg_match_all('/^\| ([^|]+?) \| ([\d.]+)(?: %)?[^|]*\| ([\d.]+)(?: %)? \|/m', $section, $rows, PREG_SET_ORDER);
    $limits = [];

    foreach ($rows as [, $label, $default, $ceiling]) {
        $limits[$label] = [$default, $ceiling];
    }

    return $limits;
}

const DOCUMENTED_KEYS = [
    'Risk per trade (loss if the stop is hit)' => 'risk_per_trade',
    'Total open risk (sum over open positions)' => 'total_open_risk',
    'Notional per instrument' => 'notional_per_instrument',
    'Open positions' => 'max_open_positions',
    'Daily loss (from equity at 00:00 UTC, realized plus unrealized)' => 'daily_loss',
    'Weekly loss (from Monday 00:00 UTC)' => 'weekly_loss',
    'Drawdown from equity peak' => 'drawdown_halt',
    'Hard drawdown from equity peak' => 'drawdown_flatten',
];

it('configures exactly the defaults of the risk policy document, below its ceilings', function (): void {
    $documented = documentedLimits();
    $config = config()->array('risk.account');

    foreach (DOCUMENTED_KEYS as $label => $key) {
        expect($documented)->toHaveKey($label);
        [$default, $ceiling] = $documented[$label];

        if ($key === 'max_open_positions') {
            expect($config[$key])->toBe((int) $default)
                ->and(RiskPolicy::MAX_OPEN_POSITIONS_CEILING)->toBe((int) $ceiling);

            continue;
        }

        $asFraction = fn (string $percent): string => (string) Decimal::of($percent)->times(Decimal::of('0.01'));
        $configured = $config[$key] ?? null;
        expect($configured)->toBeString();
        expect((string) Decimal::of(is_string($configured) ? $configured : ''))->toBe($asFraction($default), "{$key} default")
            ->and((string) Decimal::of(RiskPolicy::CEILINGS[$key]))->toBe($asFraction($ceiling), "{$key} ceiling");
    }
});

it('builds the account policy from the configuration', function (): void {
    $policy = RiskPolicy::account(config()->array('risk.account'));

    expect($policy->profile)->toBe(RiskProfile::Account)
        ->and((string) $policy->riskPerTrade)->toBe('0.005')
        ->and((string) $policy->cashBuffer)->toBe('0.01');
});
