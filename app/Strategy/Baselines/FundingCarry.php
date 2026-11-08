<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Market\Data\FundingRate;
use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\PositionView;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Support\Number\Decimal;
use App\Support\Symbol;
use App\Support\Timeframe;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * The configurator's funding carry template (docs/strategy/configurator.md, D-50): long spot and short perpetual of the
 * same base, opened when the average of the last n funding rates is above the entry threshold and closed when the
 * average of the last m is below the exit one. A pair without a stop; the hedge is its protection (D-48). When one leg
 * is gone, the other is closed.
 *
 * It follows the spot instrument first and the perpetual second, so that in one event the perpetual's call sees both
 * legs, and the pair is priced at the perpetual's close. An hour whose spot candle it has not seen is not decided on.
 */
final class FundingCarry implements BuildableStrategy
{
    /** Binance's usual funding interval, used only to size the warmup. */
    private const int FUNDING_HOURS = 8;

    /** @var list<FundingRate> funding rates seen, oldest first, one per funding time */
    private array $rates = [];

    private ?CarbonImmutable $spotSeenAt = null;

    private bool $spotHeld = false;

    private readonly Decimal $entry;

    private readonly Decimal $exit;

    private readonly int $entryPeriods;

    private readonly int $exitPeriods;

    /** @param array<string, string> $parameters */
    private function __construct(
        private readonly array $parameters,
        private readonly Symbol $spot,
        private readonly Symbol $perpetual,
        private readonly Timeframe $timeframe,
    ) {
        $this->entry = Parameters::rate($parameters, 'entry');
        $this->exit = Parameters::rate($parameters, 'exit');
        $this->entryPeriods = Parameters::count($parameters, 'entry_periods');
        $this->exitPeriods = Parameters::count($parameters, 'exit_periods');
        Parameters::count($parameters, 'max_leverage');
        Parameters::decimal($parameters, 'margin_buffer');
        Parameters::decimal($parameters, 'max_position');

        if ($this->exit->isGreaterThan($this->entry)) {
            throw new InvalidArgumentException("The exit threshold must not be above the entry one, got {$this->exit} and {$this->entry}.");
        }
    }

    public static function defaults(): array
    {
        return [
            'entry' => '0.0001', 'entry_periods' => '9', 'exit' => '0', 'exit_periods' => '3',
            'margin_buffer' => '0.5', 'max_leverage' => '2', 'max_position' => '0.30', 'timeframe' => '1h',
        ];
    }

    /** The pair is built from the first symbol's base and quote, whichever market it names. */
    public static function build(array $parameters, array $symbols): Strategy
    {
        $base = $symbols[0]->base;
        $quote = $symbols[0]->quote;

        return new self(
            $parameters,
            Symbol::parse("{$base}/{$quote}"),
            Symbol::parse("{$base}/{$quote}:{$quote}"),
            Parameters::timeframe($parameters, 'timeframe'),
        );
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('funding-carry', 1, $this->parameters);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->spot, $this->timeframe), new Subscription($this->perpetual, $this->timeframe)];
    }

    /** Enough candles to have seen the entry's funding periods, plus one. */
    public function warmupBars(): int
    {
        return intdiv($this->entryPeriods * self::FUNDING_HOURS * 3600 + $this->timeframe->seconds() - 1, $this->timeframe->seconds()) + 1;
    }

    public function onCandle(StrategyContext $context): array
    {
        if (! $context->symbol->isPerpetual()) {
            $this->spotSeenAt = $context->time;
            $this->spotHeld = $context->position !== null;

            return [];
        }

        $this->remember($context->funding);

        if ($this->spotSeenAt === null || ! $this->spotSeenAt->equalTo($context->time)) {
            return [];
        }

        return $this->decide($context->position);
    }

    /** @return list<Close|OpenCarry> */
    private function decide(?PositionView $hedge): array
    {
        if ($hedge !== null && $this->spotHeld) {
            $average = $this->average($this->exitPeriods);

            return $average !== null && $average->isLessThan($this->exit)
                ? [new Close($this->spot, 'funding average below the exit'), new Close($this->perpetual, 'funding average below the exit')]
                : [];
        }

        if ($hedge !== null) {
            return [new Close($this->perpetual, 'the spot leg is gone')];
        }

        if ($this->spotHeld) {
            return [new Close($this->spot, 'the hedge is gone')];
        }

        $average = $this->average($this->entryPeriods);

        if ($average === null || ! $average->isGreaterThan($this->entry)) {
            return [];
        }

        return [new OpenCarry(
            $this->spot,
            $this->perpetual,
            Decimal::of($this->parameters['max_position']),
            (int) $this->parameters['max_leverage'],
            Decimal::of($this->parameters['margin_buffer']),
            'funding average above the entry',
        )];
    }

    private function remember(?FundingRate $rate): void
    {
        $last = $this->rates === [] ? null : $this->rates[count($this->rates) - 1];

        if ($rate !== null && ($last === null || $rate->fundingTime > $last->fundingTime)) {
            $this->rates[] = $rate;
            $this->rates = array_slice($this->rates, -max($this->entryPeriods, $this->exitPeriods));
        }
    }

    /** The mean of the last $periods funding rates, or null before that many were seen. */
    private function average(int $periods): ?Decimal
    {
        if (count($this->rates) < $periods) {
            return null;
        }

        $sum = Decimal::zero();

        foreach (array_slice($this->rates, -$periods) as $rate) {
            $sum = $sum->plus($rate->rate);
        }

        return $sum->dividedBy(Decimal::of($periods), 18);
    }
}
