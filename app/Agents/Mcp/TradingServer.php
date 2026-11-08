<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Agents\Mcp\Tools\CompileStrategy;
use App\Agents\Mcp\Tools\DslReference;
use App\Agents\Mcp\Tools\GetCandles;
use App\Agents\Mcp\Tools\GetFunding;
use App\Agents\Mcp\Tools\GetIndicators;
use App\Agents\Mcp\Tools\GetJob;
use App\Agents\Mcp\Tools\GetStrategy;
use App\Agents\Mcp\Tools\ListInstruments;
use App\Agents\Mcp\Tools\ListStrategies;
use App\Agents\Mcp\Tools\ListTrials;
use App\Agents\Mcp\Tools\RunBacktest;
use App\Agents\Mcp\Tools\RunGrid;
use App\Agents\Mcp\Tools\SaveStrategy;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

/**
 * Trading X for external agents (D-63): read the market through the DSL's own functions, write strategy documents
 * as draft versions, and run backtests, grids and walk-forwards in the background. It cannot trade, change a limit
 * or move a strategy's stage (D-7, D-16).
 */
#[Name('Trading X')]
#[Version('1.0.0')]
#[Instructions(<<<'TEXT'
Trading X research tools. Read candles and evaluate DSL expressions (ema(9), rsi(7), bb(20, 2).upper, close > vwap() ...)
on any timeframe with the same formulas backtests use. Write a strategy as a YAML document (call dsl_reference first),
check it with compile_strategy, save it with save_strategy (a draft version), then run_backtest or run_grid on that
version and poll get_job for the result. Judge a strategy by its out-of-sample walk-forward, its deflated Sharpe ratio
and its probability of overfitting, not by its best in-sample return. You cannot place orders, change limits or
promote a strategy: the operator decides that.
TEXT)]
final class TradingServer extends Server
{
    protected array $tools = [
        ListInstruments::class,
        GetCandles::class,
        GetIndicators::class,
        GetFunding::class,
        DslReference::class,
        CompileStrategy::class,
        SaveStrategy::class,
        ListStrategies::class,
        GetStrategy::class,
        RunBacktest::class,
        RunGrid::class,
        GetJob::class,
        ListTrials::class,
    ];
}
