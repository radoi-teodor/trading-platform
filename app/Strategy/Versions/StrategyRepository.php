<?php

declare(strict_types=1);

namespace App\Strategy\Versions;

use App\Market\Contracts\InstrumentDirectory;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Support\Clock\Clock;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * Saves and finds strategy versions (D-61). Only a document that compiles is saved; a document identical to the
 * latest version of its strategy (line endings and trailing blank lines aside) is that version; any other becomes the
 * next version, a Draft. Versions are never changed afterwards.
 */
final readonly class StrategyRepository
{
    private const string LABEL = '/^([a-z0-9]+(?:-[a-z0-9]+)*)@v([1-9]\d{0,5})$/';

    public function __construct(private Clock $clock, private InstrumentDirectory $instruments, private PolicyLimits $limits) {}

    public function save(string $document, string $author): SaveResult
    {
        $text = rtrim(str_replace("\r\n", "\n", $document))."\n";
        $check = DocumentChecker::check($text, $this->instruments, $this->limits);

        if ($check->spec === null) {
            return new SaveResult(null, false, $check);
        }

        $latest = $this->latest($check->spec->id);
        $sha256 = hash('sha256', $text);

        if ($latest !== null && $latest->sha256 === $sha256) {
            return new SaveResult($latest, false, $check);
        }

        $now = $this->clock->now();
        $version = StrategyVersion::query()->create([
            'strategy' => $check->spec->id,
            'version' => ($latest === null ? 0 : $latest->version) + 1,
            'document' => $text,
            'sha256' => $sha256,
            'author' => $author,
            'parent_id' => $latest?->id,
            'created_at' => $now,
        ]);
        StrategyStage::query()->create(['strategy_version_id' => $version->id, 'stage' => 'draft', 'reason' => "saved by {$author}", 'created_at' => $now]);

        return new SaveResult($version, true, $check);
    }

    /** The version named <id>@v<n>, or null when there is none. */
    public function find(string $label): ?StrategyVersion
    {
        if (preg_match(self::LABEL, $label, $match) !== 1) {
            throw new InvalidArgumentException("{$label} is not a version name; write it as <strategy>@v<n>, such as ema-cross-scalp-long@v1.");
        }

        return StrategyVersion::query()->where('strategy', $match[1])->where('version', (int) $match[2])->first();
    }

    public function latest(string $strategy): ?StrategyVersion
    {
        return StrategyVersion::query()->where('strategy', $strategy)->orderByDesc('version')->first();
    }

    /** @return Collection<int, StrategyVersion> every version, by strategy then version */
    public function all(): Collection
    {
        return StrategyVersion::query()->orderBy('strategy')->orderBy('version')->get();
    }
}
