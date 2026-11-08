<?php

declare(strict_types=1);

namespace App\Risk;

use App\Market\Data\InstrumentRules;
use App\Risk\Data\AccountView;
use App\Risk\Data\RejectionReason;
use App\Risk\Data\RiskProfile;
use App\Risk\Data\Verdict;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenCarry;
use App\Strategy\Data\OpenLong;
use App\Strategy\Data\OpenShort;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use App\Support\Perpetual;
use App\Support\Side;
use Carbon\CarbonImmutable;

/**
 * Approves, resizes or rejects intents against the policy, in the order of docs/engine.md, sections 5 and 8. Sizes
 * never round up: every rounding and every cap makes the position smaller.
 */
final readonly class RiskEngine
{
    private const int SCALE = 18;

    /** The benchmark profile keeps this share of a USD-M wallet free for slippage and fees (D-50). */
    private const string BENCHMARK_MARGIN_SHARE = '0.99';

    public function __construct(
        private RiskPolicy $policy,
        private RiskState $state,
        private ?Decimal $maintenanceRate = null,
    ) {}

    public function assessEntry(OpenLong|OpenShort $intent, AccountView $account, InstrumentRules $rules, Price $price, CarbonImmutable $time): Verdict
    {
        $side = $intent instanceof OpenLong ? Side::Buy : Side::Sell;
        $perpetual = $intent->symbol->isPerpetual();

        if ($this->state->entryBlock($time) !== null) {
            return Verdict::rejected(RejectionReason::Halted);
        }

        if ($side === Side::Sell && ! $perpetual) {
            return Verdict::rejected(RejectionReason::SpotShort);
        }

        if ($account->holdsInstrument) {
            return Verdict::rejected(RejectionReason::PositionOpen);
        }

        if ($account->openPositions >= $this->policy->maxOpenPositions) {
            return Verdict::rejected(RejectionReason::MaxPositions);
        }

        if ($intent->target !== null && ! self::beyond($side, $intent->target->value, $price->value)) {
            return Verdict::rejected(RejectionReason::InvalidTarget);
        }

        if ($this->policy->profile === RiskProfile::Benchmark) {
            return $perpetual ? $this->benchmarkPerpetual($account, $rules, $price) : $this->benchmarkEntry($account, $rules);
        }

        if ($intent->stop === null) {
            return Verdict::rejected(RejectionReason::NoStop);
        }

        $riskPerUnit = $price->value->minus($intent->stop->value)->abs();

        if (! self::beyond($side, $price->value, $intent->stop->value)) {
            return Verdict::rejected(RejectionReason::InvalidStop);
        }

        $leverage = $perpetual ? $this->policy->perpetualLeverage : null;

        if ($leverage !== null && $this->liquidationTooClose($side, $price->value, $leverage, $riskPerUnit)) {
            return Verdict::rejected(RejectionReason::LiquidationTooClose);
        }

        $adjustments = [];
        $quantity = $account->equity->times($this->policy->riskPerTrade)->dividedBy($riskPerUnit, self::SCALE);
        $maximum = $account->equity->times($this->policy->notionalPerInstrument)->dividedBy($price->value, self::SCALE);

        if ($quantity->isGreaterThan($maximum)) {
            $quantity = $maximum;
            $adjustments[] = 'notional_cap';
        }

        if ($account->openRisk->plus($quantity->times($riskPerUnit))->isGreaterThan($account->equity->times($this->policy->totalOpenRisk))) {
            return Verdict::rejected(RejectionReason::OpenRisk);
        }

        $exposure = $this->exposure($account, $side, $quantity->times($price->value), Decimal::zero());

        if ($exposure !== null) {
            return Verdict::rejected($exposure);
        }

        // Spot pays the notional from free quote; a perpetual its margin from the USD-M wallet.
        $perUnitCash = $leverage === null ? $price->value : $price->value->dividedBy(Decimal::of($leverage), self::SCALE);
        $cash = $leverage === null ? $account->freeQuote : ($account->freeMargin ?? Decimal::zero());
        $affordable = $cash->dividedBy($perUnitCash->times(Decimal::of(1)->plus($this->policy->cashBuffer)), self::SCALE);

        if ($quantity->isGreaterThan($affordable)) {
            $quantity = $affordable;
            $adjustments[] = 'cash';
        }

        if ($rules->maxQty !== null && $quantity->isGreaterThan($rules->maxQty)) {
            $quantity = $rules->maxQty;
            $adjustments[] = 'exchange_maximum';
        }

        $quantity = $quantity->roundDownToMultipleOf($rules->stepSize);

        if ($this->belowMinimum($quantity, $price->value, $rules)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quantity($quantity, $quantity->times($riskPerUnit), $adjustments, $leverage);
    }

    /** A hedged pair (D-48): one quantity for both legs, no stop, the perpetual leg at a leverage that keeps the buffer. */
    public function assessCarry(
        OpenCarry $intent,
        AccountView $account,
        InstrumentRules $spotRules,
        InstrumentRules $perpetualRules,
        Price $spotPrice,
        Price $perpetualPrice,
        CarbonImmutable $time,
    ): Verdict {
        if ($this->state->entryBlock($time) !== null) {
            return Verdict::rejected(RejectionReason::Halted);
        }

        if ($account->holdsInstrument) {
            return Verdict::rejected(RejectionReason::PositionOpen);
        }

        if ($account->openPositions + 2 > $this->policy->maxOpenPositions) {
            return Verdict::rejected(RejectionReason::MaxPositions);
        }

        $buffer = $intent->marginBuffer->isGreaterThan($this->policy->carryExtraMargin) ? $intent->marginBuffer : $this->policy->carryExtraMargin;
        $leverage = max(1, (int) (string) Decimal::of(min($intent->maxLeverage, $this->policy->perpetualLeverage))
            ->dividedBy(Decimal::of(1)->plus($buffer), 0));
        $share = $intent->maxPosition->isLessThan($this->policy->notionalPerInstrument) ? $intent->maxPosition : $this->policy->notionalPerInstrument;
        $quantity = $account->equity->times($share)->dividedBy($spotPrice->value, self::SCALE);
        $adjustments = [];
        $withBuffer = Decimal::of(1)->plus($this->policy->cashBuffer);
        $affordable = [
            $account->freeQuote->dividedBy($spotPrice->value->times($withBuffer), self::SCALE),
            ($account->freeMargin ?? Decimal::zero())->dividedBy($perpetualPrice->value->dividedBy(Decimal::of($leverage), self::SCALE)->times($withBuffer), self::SCALE),
        ];

        foreach ($affordable as $limit) {
            if ($quantity->isGreaterThan($limit)) {
                $quantity = $limit;
                $adjustments = ['cash'];
            }
        }

        $quantity = $quantity->roundDownToMultipleOf($perpetualRules->stepSize)->roundDownToMultipleOf($spotRules->stepSize);
        $exposure = $this->exposure($account, Side::Buy, $quantity->times($spotPrice->value), $quantity->times($perpetualPrice->value));

        if ($exposure !== null) {
            return Verdict::rejected($exposure);
        }

        if ($this->belowMinimum($quantity, $spotPrice->value, $spotRules) || $this->belowMinimum($quantity, $perpetualPrice->value, $perpetualRules)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quantity($quantity, null, $adjustments, $leverage);
    }

    /** A stop may move towards the price, never away from it, and never to or beyond the price. */
    public function assessStopMove(MoveStop $intent, ?Price $currentStop, Price $price, Side $side = Side::Buy): Verdict
    {
        if (! self::beyond($side, $price->value, $intent->stop->value)) {
            return Verdict::rejected(RejectionReason::InvalidStop);
        }

        if ($currentStop !== null && self::beyond($side, $currentStop->value, $intent->stop->value)) {
            return Verdict::rejected(RejectionReason::StopWidening);
        }

        return Verdict::approved();
    }

    /** Whether $a is strictly on the profit side of $b for $side: above it for a long, below it for a short. */
    private static function beyond(Side $side, Decimal $a, Decimal $b): bool
    {
        return $side === Side::Buy ? $a->isGreaterThan($b) : $a->isLessThan($b);
    }

    private function liquidationTooClose(Side $side, Decimal $price, int $leverage, Decimal $riskPerUnit): bool
    {
        $one = Decimal::of(1);
        $liquidation = Perpetual::liquidationPrice($side, $one, $price, $price->dividedBy(Decimal::of($leverage), self::SCALE), $this->maintenanceRate ?? Decimal::of('0.01'));

        return $price->minus($liquidation)->abs()->isLessThan($riskPerUnit->times(Decimal::of(3)));
    }

    /** Gross exposure counts every leg; net exposure only rejects what moves it further from zero beyond the limit. */
    private function exposure(AccountView $account, Side $side, Decimal $notional, Decimal $hedge): ?RejectionReason
    {
        $gross = ($account->grossExposure ?? Decimal::zero())->plus($notional)->plus($hedge);

        if ($gross->isGreaterThan($account->equity->times($this->policy->grossExposure))) {
            return RejectionReason::GrossExposure;
        }

        $net = $account->netExposure ?? Decimal::zero();
        $after = $net->plus($side === Side::Buy ? $notional : $notional->negate())->minus($hedge);

        return $after->abs()->isGreaterThan($account->equity->times($this->policy->netExposure)) && $after->abs()->isGreaterThan($net->abs())
            ? RejectionReason::NetExposure
            : null;
    }

    private function benchmarkEntry(AccountView $account, InstrumentRules $rules): Verdict
    {
        if ($rules->minNotional !== null && $account->freeQuote->isLessThan($rules->minNotional)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quoteAmount($account->freeQuote);
    }

    private function benchmarkPerpetual(AccountView $account, InstrumentRules $rules, Price $price): Verdict
    {
        $quantity = ($account->freeMargin ?? Decimal::zero())->times(Decimal::of(self::BENCHMARK_MARGIN_SHARE))
            ->dividedBy($price->value, self::SCALE)->roundDownToMultipleOf($rules->stepSize);

        if ($this->belowMinimum($quantity, $price->value, $rules)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quantity($quantity, null, [], 1);
    }

    private function belowMinimum(Decimal $quantity, Decimal $price, InstrumentRules $rules): bool
    {
        return $quantity->isLessThan($rules->minQty) || ! $quantity->isPositive()
            || ($rules->minNotional !== null && $quantity->times($price)->isLessThan($rules->minNotional));
    }
}
