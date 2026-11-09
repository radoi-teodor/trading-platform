<?php

declare(strict_types=1);

use App\Execution\Deployments\SizedStrategy;
use App\Strategy\Contracts\LimitedStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\StrategyLimits;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use Tests\Support\EnterOnceStrategy;
use Tests\Support\Policies;

function enterOnce(): Strategy
{
    return EnterOnceStrategy::build(EnterOnceStrategy::defaults(), [Symbol::parse('BTC/USDT')]);
}

it('scales risk per trade by the deployment\'s multiplier and caps the notional on live micro (D-83)', function (): void {
    $policy = Policies::account();
    $sized = new SizedStrategy(enterOnce(), Decimal::of('0.25'), Decimal::of('0.05'), $policy);

    expect($sized)->toBeInstanceOf(LimitedStrategy::class)
        ->and((string) $sized->limits()->riskPerTrade)->toBe((string) $policy->riskPerTrade->times(Decimal::of('0.25')))
        ->and((string) $sized->limits()->maxPosition)->toBe('0.05')
        ->and($sized->identity()->key())->toBe(enterOnce()->identity()->key());
});

it('scales a strategy\'s own tighter limit, never loosens one', function (): void {
    $policy = Policies::account();
    $inner = new class implements LimitedStrategy
    {
        private Strategy $inner;

        public function __construct()
        {
            $this->inner = enterOnce();
        }

        public function identity(): StrategyIdentity
        {
            return $this->inner->identity();
        }

        public function subscriptions(): array
        {
            return $this->inner->subscriptions();
        }

        public function warmupBars(): int
        {
            return $this->inner->warmupBars();
        }

        public function onCandle(StrategyContext $context): array
        {
            return $this->inner->onCandle($context);
        }

        public function limits(): StrategyLimits
        {
            return new StrategyLimits(Decimal::of('0.002'), Decimal::of('0.03'), 2);
        }
    };
    $sized = new SizedStrategy($inner, Decimal::of('0.5'), null, $policy);

    expect((string) $sized->limits()->riskPerTrade)->toBe('0.001')
        ->and((string) $sized->limits()->maxPosition)->toBe('0.03')
        ->and($sized->limits()->maxOpenPositions)->toBe(2);
});
