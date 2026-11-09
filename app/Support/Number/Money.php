<?php

declare(strict_types=1);

namespace App\Support\Number;

use App\Support\Asset;
use Stringable;

/** An amount of one asset. May be negative (a loss, a fee owed). Never combined with another asset. */
final readonly class Money implements Stringable
{
    private function __construct(public Decimal $amount, public Asset $asset) {}

    public static function of(string|int|Decimal $amount, Asset $asset): self
    {
        return new self($amount instanceof Decimal ? $amount : Decimal::of($amount), $asset);
    }

    public function plus(self $other): self
    {
        $this->assertSameAsset($other);

        return new self($this->amount->plus($other->amount), $this->asset);
    }

    public function minus(self $other): self
    {
        $this->assertSameAsset($other);

        return new self($this->amount->minus($other->amount), $this->asset);
    }

    public function negate(): self
    {
        return new self($this->amount->negate(), $this->asset);
    }

    public function __toString(): string
    {
        return "{$this->amount} {$this->asset}";
    }

    private function assertSameAsset(self $other): void
    {
        if (! $this->asset->equals($other->asset)) {
            throw new AssetMismatch("Cannot combine {$this->asset} with {$other->asset}.");
        }
    }
}
