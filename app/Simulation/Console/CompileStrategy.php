<?php

declare(strict_types=1);

namespace App\Simulation\Console;

use App\Market\Instruments\StoredInstruments;
use App\Risk\RiskPolicy;
use App\Strategy\Data\UniverseSchedule;
use App\Strategy\Dsl\Compiler\CompiledStrategy;
use App\Strategy\Dsl\Document\CheckResult;
use App\Strategy\Dsl\Document\DocumentChecker;
use App\Strategy\Dsl\Document\PolicyLimits;
use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Position;
use App\Strategy\Dsl\ReadBack;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;

/** Checks a strategy document and says what it compiled to, or every error with its place (docs/strategy/configurator.md). */
final class CompileStrategy extends Command
{
    protected $signature = 'strategy:compile {file : A strategy document, such as catalog/strategies/ema-cross-scalp.strategy.yaml}';

    protected $description = 'Check a strategy document against the DSL and the account policy';

    public function handle(Repository $config): int
    {
        $file = $this->argument('file');
        $file = is_string($file) ? $file : '';
        $limits = $config->get('risk.account');
        $result = self::compile($file, RiskPolicy::account(is_array($limits) ? $limits : throw new InvalidArgumentException('config risk.account must be an array.')));

        foreach ($result->warnings as $warning) {
            $this->warn("warning: {$warning}");
        }

        if ($result->spec === null) {
            foreach ($result->errors as $error) {
                $this->error("{$file}:{$error->position->line}:{$error->position->column}: {$error->problem}");
            }

            return self::FAILURE;
        }

        $spec = $result->spec;
        $strategy = CompiledStrategy::fromSpec($spec, [], 1, UniverseSchedule::none());
        $traded = $spec->universe !== null ? "universe {$spec->universe->text()}" : implode(', ', array_map('strval', $spec->instruments));
        $parameters = array_map(fn (string $name, string $value): string => "{$name} = {$value}", array_keys($strategy->identity()->parameters), $strategy->identity()->parameters);

        $this->info("{$spec->id} compiles: {$spec->title}");
        $this->line("{$traded} on {$spec->timeframe->value}".($spec->benchmark ? ', benchmark profile' : ''));
        $this->line("Warmup: {$strategy->warmupBars()} candles of the longest timeframe it reads.");
        $this->line('Parameters: '.($parameters === [] ? 'none' : implode(', ', $parameters)));
        $this->newLine();
        $this->line(wordwrap(ReadBack::render($spec), 110));

        return self::SUCCESS;
    }

    /** Reads and checks a document against the stored instruments and the account policy's limits. */
    public static function compile(string $file, RiskPolicy $policy): CheckResult
    {
        $yaml = is_file($file) ? file_get_contents($file) : false;

        if ($yaml === false) {
            return new CheckResult(null, [new DslError(new Position(1, 1), "cannot read {$file}.")], []);
        }

        return DocumentChecker::check($yaml, new StoredInstruments, new PolicyLimits($policy->riskPerTrade, $policy->notionalPerInstrument, $policy->maxOpenPositions));
    }
}
