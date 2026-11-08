<?php

declare(strict_types=1);

namespace App\Risk;

use App\Market\Data\InstrumentRules;
use App\Risk\Data\AccountView;
use App\Risk\Data\RejectionReason;
use App\Risk\Data\RiskProfile;
use App\Risk\Data\Verdict;
use App\Strategy\Data\MoveStop;
use App\Strategy\Data\OpenLong;
use App\Support\Number\Decimal;
use App\Support\Number\Price;
use Carbon\CarbonImmutable;

/**
 * Approves, resizes or rejects intents against the policy, in the order of docs/engine.md, section 5. Sizes never
 * round up: every rounding and every cap makes the position smaller.
 */
final readonly class RiskEngine
{
    private const int SCALE = 18;

    public function __construct(private RiskPolicy $policy, private RiskState $state) {}

    public function assessEntry(OpenLong $intent, AccountView $account, InstrumentRules $rules, Price $price, CarbonImmutable $time): Verdict
    {
        if ($this->state->entryBlock($time) !== null) {
            return Verdict::rejected(RejectionReason::Halted);
        }

        if ($account->holdsInstrument) {
            return Verdict::rejected(RejectionReason::PositionOpen);
        }

        if ($account->openPositions >= $this->policy->maxOpenPositions) {
            return Verdict::rejected(RejectionReason::MaxPositions);
        }

        if ($intent->target !== null && ! $intent->target->value->isGreaterThan($price->value)) {
            return Verdict::rejected(RejectionReason::InvalidTarget);
        }

        if ($this->policy->profile === RiskProfile::Benchmark) {
            return $this->benchmarkEntry($account, $rules);
        }

        if ($intent->stop === null) {
            return Verdict::rejected(RejectionReason::NoStop);
        }

        $riskPerUnit = $price->value->minus($intent->stop->value);

        if (! $riskPerUnit->isPositive()) {
            return Verdict::rejected(RejectionReason::InvalidStop);
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

        $affordable = $account->freeQuote->dividedBy($price->value->times(Decimal::of(1)->plus($this->policy->cashBuffer)), self::SCALE);

        if ($quantity->isGreaterThan($affordable)) {
            $quantity = $affordable;
            $adjustments[] = 'cash';
        }

        if ($rules->maxQty !== null && $quantity->isGreaterThan($rules->maxQty)) {
            $quantity = $rules->maxQty;
            $adjustments[] = 'exchange_maximum';
        }

        $quantity = $quantity->roundDownToMultipleOf($rules->stepSize);

        if ($quantity->isLessThan($rules->minQty) || ! $quantity->isPositive() || $this->belowMinimumNotional($quantity->times($price->value), $rules)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quantity($quantity, $quantity->times($riskPerUnit), $adjustments);
    }

    /** A stop may move towards the price, never away from it, and never to or beyond the price. */
    public function assessStopMove(MoveStop $intent, ?Price $currentStop, Price $price): Verdict
    {
        if (! $intent->stop->value->isLessThan($price->value)) {
            return Verdict::rejected(RejectionReason::InvalidStop);
        }

        if ($currentStop !== null && $intent->stop->value->isLessThan($currentStop->value)) {
            return Verdict::rejected(RejectionReason::StopWidening);
        }

        return Verdict::approved();
    }

    private function benchmarkEntry(AccountView $account, InstrumentRules $rules): Verdict
    {
        if ($this->belowMinimumNotional($account->freeQuote, $rules)) {
            return Verdict::rejected(RejectionReason::BelowMinimum);
        }

        return Verdict::quoteAmount($account->freeQuote);
    }

    private function belowMinimumNotional(Decimal $notional, InstrumentRules $rules): bool
    {
        return $rules->minNotional !== null && $notional->isLessThan($rules->minNotional);
    }
}
