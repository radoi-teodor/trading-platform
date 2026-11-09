<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Simulation\Models\SimulationJob;
use App\Simulation\Models\StrategyGateReport;
use App\Simulation\SimulationQueue;
use App\Simulation\StrategyLibrary;
use App\Strategy\Dsl\DslError;
use App\Strategy\Versions\StrategyVersion;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Strategies (Phase 4): the DSL editor, which compiles as the operator types and shows every error with its place
 * and the read-back; saving as a version (D-61); backtests, grids and gate reports in the background (D-62); and
 * promotion from Draft to Backtested on a passing report, never further (D-16).
 */
#[Layout('layouts.app')]
#[Title('Strategies')]
final class StrategiesScreen extends Component
{
    public string $document = '';

    public ?string $selected = null;

    public string $from = '';

    public string $to = '';

    public string $message = '';

    public function open(string $label, StrategyLibrary $library): void
    {
        $version = $library->versions()->find($label);

        if ($version !== null) {
            $this->selected = $label;
            $this->document = $version->document;
            $this->dispatch('document-loaded');
        }
    }

    public function save(StrategyLibrary $library): void
    {
        $saved = $library->versions()->save($this->document, 'operator');
        $this->message = $saved->version === null
            ? 'The document does not compile; nothing was saved.'
            : ($saved->created ? "Saved as {$saved->version->label()}." : "Unchanged: it is {$saved->version->label()}.");
        $this->selected = $saved->version?->label() ?? $this->selected;
    }

    public function run(string $kind, SimulationQueue $queue): void
    {
        try {
            $arguments = match ($kind) {
                'gates' => ['strategy' => $this->selected ?? ''] + ($this->to === '' ? [] : ['to' => $this->to]),
                default => ['strategy' => $this->selected ?? '', 'from' => $this->from, 'to' => $this->to],
            };
            $job = $queue->submit($kind, $arguments, 'operator');
            $this->message = ucfirst($kind)." {$job->id} queued.";
        } catch (InvalidArgumentException $failure) {
            $this->message = $failure->getMessage();
        }
    }

    public function promote(Kernel $console): void
    {
        $console->call('strategy:promote', ['strategy' => $this->selected ?? '']);
        $this->message = trim($console->output());
    }

    public function render(StrategyLibrary $library): View
    {
        $check = trim($this->document) === '' ? null : $library->check($this->document);
        $version = $this->selected === null ? null : $this->find($library, $this->selected);

        return view('livewire.strategies-screen', [
            'problems' => $check === null ? [] : array_map(fn (DslError $e): array => ['line' => $e->position->line, 'column' => $e->position->column, 'problem' => $e->problem], $check->errors),
            'warnings' => $check->warnings ?? [],
            'compiled' => $check === null ? null : $library->describe($check),
            'versions' => $library->versions()->all()->reverse()->values()->map(fn (StrategyVersion $v): array => [
                'label' => $v->label(), 'stage' => $v->stage(), 'author' => $v->author, 'created' => $v->created_at->format('Y-m-d H:i'),
            ])->all(),
            'jobs' => SimulationJob::query()->orderByDesc('id')->limit(10)->get(),
            'report' => $version === null ? null : StrategyGateReport::query()->where('strategy_version_id', $version->id)->orderByDesc('id')->first(),
            'stage' => $version?->stage(),
        ]);
    }

    private function find(StrategyLibrary $library, string $label): ?StrategyVersion
    {
        try {
            return $library->versions()->find($label);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
