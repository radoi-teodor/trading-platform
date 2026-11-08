<?php

declare(strict_types=1);

namespace App\Portfolio;

use App\Portfolio\Data\Fill;
use App\Portfolio\Data\Marks;
use App\Portfolio\Data\Position;
use App\Portfolio\Data\RoundTrip;
use App\Support\Asset;
use App\Support\Number\Decimal;
use App\Support\Number\Money;
use App\Support\Number\Price;
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

    /** @param list<Money> $initialBalances */
    public function __construct(public readonly Asset $quote, array $initialBalances)
    {
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
        $this->positions[$key] = $position;

        if ($position->quantity->isZero()) {
            $this->close($key, $fill->time);
        }
    }

    /** Closes a position whose remainder is below the lot step; the remainder stays in the account as dust. */
    public function writeOffDust(string $strategy, Symbol $symbol, CarbonImmutable $at): void
    {
        $key = self::key($strategy, $symbol);
        $position = $this->positions[$key] ?? throw new LedgerViolation("Strategy {$strategy} holds no {$symbol}.");
        $asset = (string) $symbol->base;
        $this->dust[$asset] = ($this->dust[$asset] ?? Decimal::zero())->plus($position->quantity);
        $this->close($key, $at);
    }

    /** Records the range of a closed minute on the open positions of that instrument, for their excursions. */
    public function observe(Symbol $symbol, Price $high, Price $low): void
    {
        foreach ($this->positions as $key => $position) {
            if ($position->symbol->equals($symbol)) {
                $this->positions[$key] = $position->withExtremes($high, $low);
            }
        }
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

            $price = $marks->priceOf(Asset::of($asset)) ?? throw new LedgerViolation("No price to value {$balance} {$asset}.");
            $equity = $equity->plus($balance->times($price->value));
        }

        return $equity;
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
