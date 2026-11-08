<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Symbol;
use App\Support\Timeframe;

/** Enters once with a stop a fraction under the close, and leaves after a number of bars. For tests only. */
final class EnterOnceStrategy implements BuildableStrategy
{
    private int $held = 0;

    /** @param array<string, string> $parameters */
    private function __construct(private readonly array $parameters, private readonly Symbol $symbol) {}

    public static function defaults(): array
    {
        return ['bars' => '30', 'stop' => '0.05', 'warmup' => '5'];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
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

            $stop = $context->candle->close->value->times(Decimal::of(1)->minus(Decimal::of($this->parameters['stop'])));

            return [new OpenLong($this->symbol, Price::of($stop), null, 'enter once')];
        }

        return ++$this->held >= (int) $this->parameters['bars'] ? [new Close($this->symbol, 'held long enough')] : [];
    }
}
