<?php

declare(strict_types=1);

namespace App\Portfolio;

use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\PerpPosition;
use App\Portfolio\Data\Position;
use App\Portfolio\Data\RoundTrip;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
use App\Support\Perpetual;
use App\Support\Side;
use App\Support\Symbol;
use Carbon\CarbonImmutable;

/**
 * The account's book (docs/engine.md, section 6): balances per asset, positions per strategy and instrument, and
 * closed round trips. Every fill is booked once, in full or not at all. A balance never goes negative: the venue
 * would have refused the order, so a fill that would do it is a bug and is refused loudly.
 */
final class Ledger
{
    /** @var array<string, Decimal> by asset code */
    private array $balances = [];

    /** @var array<string, Decimal> remainders below the lot step, by asset code */
    private array $dust = [];

    /** @var array<string, Position> */
    private array $positions = [];

    /** @var list<RoundTrip> */
    private array $roundTrips = [];

    /** @var array<string, true> */
    private array $fillIds = [];

    private Decimal $tradedNotional;

    /** USDT in the USD-M wallet, outside the positions' isolated margins (D-43). */
    private Decimal $futuresWallet;

    /** @var array<string, PerpPosition> */
    private array $perpPositions = [];

    /** @var array<string, true> funding and liquidation ids already booked */
    private array $incomeIds = [];

    /** @param list<Money> $initialBalances spot balances */
    public function __construct(public readonly Asset $quote, array $initialBalances, ?Decimal $futuresWallet = null)
    {
        $this->tradedNotional = Decimal::zero();
        $this->futuresWallet = $futuresWallet ?? Decimal::zero();
        foreach ($initialBalances as $balance) {
            $this->balances[(string) $balance->asset] = $this->balance($balance->asset)->plus($balance->amount);
        }
    }

    public function apply(Fill $fill): void
    {
        if (isset($this->fillIds[$fill->id])) {
            throw new LedgerViolation("Fill {$fill->id} is already booked.");
        }

        if (! $fill->symbol->quote->equals($this->quote)) {
            throw new LedgerViolation("Fill {$fill->id} is in {$fill->symbol->quote}; this ledger books in {$this->quote}.");
        }

        if ($fill->symbol->isPerpetual()) {
            $this->applyPerpetual($fill);

            return;
        }

        $balances = $this->balances;
        $key = self::key($fill->strategy, $fill->symbol);
        $position = $this->positions[$key] ?? null;
        $base = (string) $fill->symbol->base;
        $quote = (string) $this->quote;
        $feeAsset = (string) $fill->fee->asset;
        $fee = $fill->fee->amount;
        $notional = $fill->notional();

        if ($fill->side === Side::Buy) {
            $balances[$base] = ($balances[$base] ?? Decimal::zero())->plus($fill->quantity);
            $balances[$quote] = ($balances[$quote] ?? Decimal::zero())->minus($notional);
            $position = ($position ?? Position::open($fill))->withBuy(
                $fill,
                $feeAsset === $base ? $fill->quantity->minus($fee) : $fill->quantity,
                $feeAsset === $quote ? $notional->plus($fee) : $notional,
            );
        } else {
            $out = $feeAsset === $base ? $fill->quantity->plus($fee) : $fill->quantity;

            if ($position === null || $out->isGreaterThan($position->quantity)) {
                throw new LedgerViolation("Fill {$fill->id} sells {$out} {$base} that strategy {$fill->strategy} does not hold.");
            }

            $balances[$base] = ($balances[$base] ?? Decimal::zero())->minus($fill->quantity);
            $balances[$quote] = ($balances[$quote] ?? Decimal::zero())->plus($notional);
            $position = $position->withSell($fill, $out, $feeAsset === $quote ? $notional->minus($fee) : $notional);
        }

        $balances[$feeAsset] = ($balances[$feeAsset] ?? Decimal::zero())->minus($fee);

        foreach ([$base, $quote, $feeAsset] as $asset) {
            if ($balances[$asset]->isNegative()) {
                throw new LedgerViolation("Fill {$fill->id} would leave {$balances[$asset]} {$asset}.");
            }
        }

        $this->balances = $balances;
        $this->fillIds[$fill->id] = true;
        $this->tradedNotional = $this->tradedNotional->plus($notional);
        $this->positions[$key] = $position;

        if ($position->quantity->isZero()) {
            $this->close($key, $fill->time);
        }
    }

    /**
     * Funding paid (negative) or received (positive) by a perpetual position, through the USD-M wallet (D-44); what
     * the wallet cannot pay is taken from the position's margin.
     */
    public function applyFunding(string $id, string $strategy, Symbol $symbol, Decimal $amount, CarbonImmutable $at): void
    {
        $key = self::key($strategy, $symbol);
        $position = $this->perpPositions[$key] ?? throw new LedgerViolation("Funding {$id}: strategy {$strategy} holds no {$symbol}.");
        $this->claimIncome($id);
        $wallet = $this->futuresWallet->plus($amount);
        // What the wallet cannot pay comes out of the position's margin, as on the venue.
        $fromMargin = $wallet->isNegative() ? $wallet : Decimal::zero();

        $this->futuresWallet = $wallet->isNegative() ? Decimal::zero() : $wallet;
        $this->perpPositions[$key] = $position->withFunding($amount, $fromMargin);
    }

    /** A liquidated position: closed at $price, its whole isolated margin lost (D-45). */
    public function liquidate(string $id, string $strategy, Symbol $symbol, Price $price, CarbonImmutable $at): void
    {
        $key = self::key($strategy, $symbol);
        $position = $this->perpPositions[$key] ?? throw new LedgerViolation("Liquidation {$id}: strategy {$strategy} holds no {$symbol}.");
        $this->roundTrips[] = $position->liquidated($price)->close($at);
        unset($this->perpPositions[$key]);
    }

    /**
     * Closes a position whose remainder is below the lot step; the remainder stays in the account as dust. The round
     * trip ends at the last sell, or at $at when nothing was sold.
     */
    public function writeOffDust(string $strategy, Symbol $symbol, CarbonImmutable $at): void
    {
        $key = self::key($strategy, $symbol);
        $position = $this->positions[$key] ?? throw new LedgerViolation("Strategy {$strategy} holds no {$symbol}.");
        $asset = (string) $symbol->base;
        $this->dust[$asset] = ($this->dust[$asset] ?? Decimal::zero())->plus($position->quantity);
        $this->close($key, $position->lastSellAt ?? $at);
    }

    /** Records the range of a closed minute on the open positions of that instrument, for their excursions. */
    public function observe(Symbol $symbol, Price $high, Price $low): void
    {
        foreach ($this->positions as $key => $position) {
            if ($position->symbol->equals($symbol)) {
                $this->positions[$key] = $position->withExtremes($high, $low);
            }
        }

        foreach ($this->perpPositions as $key => $position) {
            if ($position->symbol->equals($symbol)) {
                $this->perpPositions[$key] = $position->withExtremes($high, $low);
            }
        }
    }

    public function futuresWallet(): Decimal
    {
        return $this->futuresWallet;
    }

    public function perpPosition(string $strategy, Symbol $symbol): ?PerpPosition
    {
        return $this->perpPositions[self::key($strategy, $symbol)] ?? null;
    }

    /** @return list<PerpPosition> */
    public function perpPositions(): array
    {
        return array_values($this->perpPositions);
    }

    public function balance(Asset $asset): Decimal
    {
        return $this->balances[(string) $asset] ?? Decimal::zero();
    }

    /** @return array<string, Decimal> by asset code, in code order */
    public function balances(): array
    {
        $balances = $this->balances;
        ksort($balances, SORT_STRING);

        return $balances;
    }

    /** The notional of every fill booked, bought and sold, in quote. */
    public function tradedNotional(): Decimal
    {
        return $this->tradedNotional;
    }

    public function dust(Asset $asset): Decimal
    {
        return $this->dust[(string) $asset] ?? Decimal::zero();
    }

    public function position(string $strategy, Symbol $symbol): ?Position
    {
        return $this->positions[self::key($strategy, $symbol)] ?? null;
    }

    /** @return list<Position> */
    public function positions(): array
    {
        return array_values($this->positions);
    }

    /** @return list<RoundTrip> in closing order */
    public function roundTrips(): array
    {
        return $this->roundTrips;
    }

    /** Every balance valued in the quote asset at the marks; an asset without a mark is an error, never zero. */
    public function equity(Marks $marks): Decimal
    {
        $equity = Decimal::zero();

        foreach ($this->balances as $asset => $balance) {
            if ($asset === (string) $this->quote) {
                $equity = $equity->plus($balance);

                continue;
            }

            if ($balance->isZero()) {
                continue;
            }

            $price = $marks->priceOfCode((string) $asset) ?? throw new LedgerViolation("No price to value {$balance} {$asset}.");
            $equity = $equity->plus($balance->times($price->value));
        }

        $equity = $equity->plus($this->futuresWallet);

        foreach ($this->perpPositions as $position) {
            $mark = $marks->priceOfSymbol($position->symbol) ?? throw new LedgerViolation("No price to value the {$position->symbol} position.");
            $equity = $equity->plus($position->margin)->plus($position->unrealized($mark->value));
        }

        return $equity;
    }

    private function applyPerpetual(Fill $fill): void
    {
        if (isset($this->fillIds[$fill->id])) {
            throw new LedgerViolation("Fill {$fill->id} is already booked.");
        }

        if (! $fill->fee->asset->equals($this->quote)) {
            throw new LedgerViolation("Fill {$fill->id} charges a fee in {$fill->fee->asset}; USD-M fees are in {$this->quote}.");
        }

        $key = self::key($fill->strategy, $fill->symbol);
        $position = $this->perpPositions[$key] ?? null;
        $wallet = $this->futuresWallet->minus($fill->fee->amount);

        if ($position === null || $position->side === $fill->side) {
            $leverage = $position->leverage ?? $fill->leverage ?? throw new LedgerViolation("Fill {$fill->id} opens a position without a leverage.");
            $wallet = $wallet->minus(Perpetual::initialMargin($fill->quantity, $fill->price->value, $leverage));
            $position = $position === null ? PerpPosition::open($fill, $leverage) : $position->withIncrease($fill);
        } else {
            if ($fill->quantity->isGreaterThan($position->quantity)) {
                throw new LedgerViolation("Fill {$fill->id} reduces {$fill->quantity} of a {$position->quantity} {$fill->symbol} position.");
            }

            [$position, $realized, $released] = $position->withReduction($fill);
            $wallet = $wallet->plus($released)->plus($realized);
        }

        if ($wallet->isNegative()) {
            throw new LedgerViolation("Fill {$fill->id} would leave {$wallet} in the USD-M wallet.");
        }

        $this->futuresWallet = $wallet;
        $this->fillIds[$fill->id] = true;
        $this->tradedNotional = $this->tradedNotional->plus($fill->notional());

        if ($position->quantity->isZero()) {
            $this->roundTrips[] = $position->close($fill->time);
            unset($this->perpPositions[$key]);
        } else {
            $this->perpPositions[$key] = $position;
        }
    }

    private function claimIncome(string $id): void
    {
        if (isset($this->incomeIds[$id])) {
            throw new LedgerViolation("Income {$id} is already booked.");
        }

        $this->incomeIds[$id] = true;
    }

    private function close(string $key, CarbonImmutable $at): void
    {
        $this->roundTrips[] = $this->positions[$key]->close($at);
        unset($this->positions[$key]);
    }

    private static function key(string $strategy, Symbol $symbol): string
    {
        return "{$strategy}\0{$symbol}";
    }
}
