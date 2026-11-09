<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use App\Support\Timeframe;
use InvalidArgumentException;

/** Enters once, long or short, with a stop a fraction beyond the close, and leaves after a number of bars. For tests only. */
final class EnterOnceStrategy implements BuildableStrategy
{
    private int $held = 0;

    /** @param array<string, string> $parameters */
    private function __construct(private readonly array $parameters, private readonly Symbol $symbol) {}

    public static function defaults(): array
    {
        return ['bars' => '30', 'side' => 'long', 'stop' => '0.05', 'warmup' => '5'];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
        if ($parameters['side'] === 'short' && ! $symbols[0]->isPerpetual()) {
            throw new InvalidArgumentException("A short needs a perpetual, not {$symbols[0]}.");
        }

        return new self($parameters, $symbols[0]);
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('enter-once', 1, $this->parameters);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->symbol, Timeframe::OneMinute)];
    }

    public function warmupBars(): int
    {
        return (int) $this->parameters['warmup'];
    }

    public function onCandle(StrategyContext $context): array
    {
        if ($context->position === null) {
            // Enters until a position exists (intents during warmup are not acted on), never after one was held.
            if ($this->held > 0) {
                return [];
            }

            $distance = Decimal::of($this->parameters['stop']);

            if ($this->parameters['side'] === 'short') {
                return [new OpenShort($this->symbol, Price::of($context->candle->close->value->times(Decimal::of(1)->plus($distance))), null, 'enter once')];
            }

            return [new OpenLong($this->symbol, Price::of($context->candle->close->value->times(Decimal::of(1)->minus($distance))), null, 'enter once')];
        }

        return ++$this->held >= (int) $this->parameters['bars'] ? [new Close($this->symbol, 'held long enough')] : [];
    }
}
