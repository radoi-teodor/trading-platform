<?php

declare(strict_types=1);

namespace App\Strategy\Baselines;

use App\Strategy\Contracts\BuildableStrategy;
use App\Strategy\Contracts\Strategy;
use App\Strategy\Data\Close;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Strategy\Data\StrategyContext;
use App\Strategy\Data\StrategyIdentity;
use App\Strategy\Data\Subscription;
use App\Strategy\Indicators\Atr;
use App\Strategy\Indicators\Donchian;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Side;
use App\Support\Symbol;
use App\Support\Timeframe;

/**
 * Buys a close above the highest high of the previous n candles, with a stop a multiple of the ATR below and a
 * target a multiple of that risk above; leaves on a close below the lowest low of the previous m candles. With
 * side=short, on a perpetual, the mirror image (D-50); a target that would not be above zero is left out. The
 * baseline that uses the account's risk policy: sized by its stop, protected on the venue.
 */
final class DonchianBreakout implements BuildableStrategy
{
    private readonly Donchian $entryChannel;

    private readonly Donchian $exitChannel;

    private readonly Atr $atr;

    private readonly Decimal $stopAtr;

    private readonly Decimal $targetR;

    private readonly Side $side;

    /** @param array<string, string> $parameters */
    private function __construct(private readonly array $parameters, private readonly Symbol $symbol, private readonly Timeframe $timeframe)
    {
        $this->entryChannel = new Donchian(Parameters::count($parameters, 'entry'));
        $this->exitChannel = new Donchian(Parameters::count($parameters, 'exit'));
        $this->atr = new Atr(Parameters::count($parameters, 'atr'));
        $this->stopAtr = Parameters::decimal($parameters, 'stop_atr');
        $this->targetR = Parameters::decimal($parameters, 'target_r');
        $this->side = Parameters::side($parameters, 'side', $symbol);
    }

    public static function defaults(): array
    {
        return ['entry' => '20', 'exit' => '10', 'atr' => '14', 'side' => 'long', 'stop_atr' => '2', 'target_r' => '3', 'timeframe' => '4h'];
    }

    public static function build(array $parameters, array $symbols): Strategy
    {
        return new self($parameters, $symbols[0], Parameters::timeframe($parameters, 'timeframe'));
    }

    public function identity(): StrategyIdentity
    {
        return new StrategyIdentity('donchian-breakout', 1, $this->parameters);
    }

    public function subscriptions(): array
    {
        return [new Subscription($this->symbol, $this->timeframe)];
    }

    public function warmupBars(): int
    {
        return max($this->entryChannel->period, $this->exitChannel->period, $this->atr->period) + 1;
    }

    public function onCandle(StrategyContext $context): array
    {
        $candle = $context->candle;
        $close = $candle->close->value;
        $short = $this->side === Side::Sell;
        // The channels of the candles before this one.
        $entry = $short ? $this->entryChannel->lower() : $this->entryChannel->upper();
        $exit = $short ? $this->exitChannel->upper() : $this->exitChannel->lower();
        $atr = $this->atr->push($candle);
        $this->entryChannel->push($candle);
        $this->exitChannel->push($candle);

        if ($short) {
            return $this->short($context, $close, $entry, $exit, $atr);
        }

        if ($context->position !== null) {
            return $exit !== null && $close->isLessThan($exit) ? [new Close($this->symbol, 'close below the exit channel')] : [];
        }

        if ($entry === null || $atr === null || ! $close->isGreaterThan($entry)) {
            return [];
        }

        $stop = $close->minus($this->stopAtr->times($atr));

        if (! $stop->isPositive()) {
            return [];
        }

        $target = $close->plus($this->targetR->times($close->minus($stop)));

        return [new OpenLong($this->symbol, Price::of($stop), Price::of($target), 'close above the entry channel')];
    }

    /** @return list<Close|OpenShort> */
    private function short(StrategyContext $context, Decimal $close, ?Decimal $entry, ?Decimal $exit, ?Decimal $atr): array
    {
        if ($context->position !== null) {
            return $exit !== null && $close->isGreaterThan($exit) ? [new Close($this->symbol, 'close above the exit channel')] : [];
        }

        if ($entry === null || $atr === null || ! $close->isLessThan($entry)) {
            return [];
        }

        $stop = $close->plus($this->stopAtr->times($atr));
        $target = $close->minus($this->targetR->times($stop->minus($close)));

        return [new OpenShort($this->symbol, Price::of($stop), $target->isPositive() ? Price::of($target) : null, 'close below the entry channel')];
    }
}
