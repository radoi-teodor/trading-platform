<?php

declare(strict_types=1);

namespace App\Risk;

use App\Risk\Data\RiskProfile;
use App\Support\Number\Decimal;
use InvalidArgumentException;

/**
 * The limits of docs/risk-policy.md, section 1, as fractions of equity. An account policy above a ceiling is refused
 * here, whatever the configuration says (D-19). The benchmark profile is for backtests only (D-40).
 */
final readonly class RiskPolicy
{
    /** @var array<string, string> the ceilings of docs/risk-policy.md, section 1 */
    public const array CEILINGS = [
        'risk_per_trade' => '0.01',
        'total_open_risk' => '0.05',
        'notional_per_instrument' => '0.40',
        'daily_loss' => '0.03',
        'weekly_loss' => '0.07',
        'drawdown_halt' => '0.15',
        'drawdown_flatten' => '0.20',
    ];

    public const int MAX_OPEN_POSITIONS_CEILING = 10;

    private function __construct(
        public RiskProfile $profile,
        public Decimal $riskPerTrade,
        public Decimal $totalOpenRisk,
        public Decimal $notionalPerInstrument,
        public int $maxOpenPositions,
        public Decimal $dailyLoss,
        public Decimal $weeklyLoss,
        public Decimal $drawdownHalt,
        public Decimal $drawdownFlatten,
        public Decimal $cashBuffer,
    ) {}

    /** @param array<mixed> $limits the `account` section of config/risk.php */
    public static function account(array $limits): self
    {
        $fraction = function (string $key) use ($limits): Decimal {
            $value = $limits[$key] ?? null;

            if (! is_string($value)) {
                throw new InvalidArgumentException("Risk limit '{$key}' must be a decimal string.");
            }

            $limit = Decimal::of($value);
            $ceiling = self::CEILINGS[$key] ?? null;

            if ($limit->isNegative() || ($ceiling !== null && ($limit->isZero() || $limit->isGreaterThan(Decimal::of($ceiling))))) {
                throw new InvalidArgumentException("Risk limit '{$key}' is {$value}; it must be above 0 and at most {$ceiling}.");
            }

            return $limit;
        };
        $positions = $limits['max_open_positions'] ?? null;

        if (! is_int($positions) || $positions < 1 || $positions > self::MAX_OPEN_POSITIONS_CEILING) {
            throw new InvalidArgumentException('Risk limit max_open_positions must be between 1 and '.self::MAX_OPEN_POSITIONS_CEILING.'.');
        }

        return new self(
            RiskProfile::Account,
            $fraction('risk_per_trade'),
            $fraction('total_open_risk'),
            $fraction('notional_per_instrument'),
            $positions,
            $fraction('daily_loss'),
            $fraction('weekly_loss'),
            $fraction('drawdown_halt'),
            $fraction('drawdown_flatten'),
            $fraction('cash_buffer'),
        );
    }

    /** @return array<string, string|int> the profile and its limits, for a run definition */
    public function toArray(): array
    {
        if ($this->profile === RiskProfile::Benchmark) {
            return ['profile' => $this->profile->value];
        }

        return [
            'profile' => $this->profile->value,
            'risk_per_trade' => (string) $this->riskPerTrade,
            'total_open_risk' => (string) $this->totalOpenRisk,
            'notional_per_instrument' => (string) $this->notionalPerInstrument,
            'max_open_positions' => $this->maxOpenPositions,
            'daily_loss' => (string) $this->dailyLoss,
            'weekly_loss' => (string) $this->weeklyLoss,
            'drawdown_halt' => (string) $this->drawdownHalt,
            'drawdown_flatten' => (string) $this->drawdownFlatten,
            'cash_buffer' => (string) $this->cashBuffer,
        ];
    }

    public static function benchmark(): self
    {
        $one = Decimal::of(1);
        $zero = Decimal::zero();

        return new self(RiskProfile::Benchmark, $one, $one, $one, 1, $one, $one, $one, $one, $zero);
    }
}
