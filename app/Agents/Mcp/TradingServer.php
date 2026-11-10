<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Agents\Mcp\Tools\CompileStrategy;
use App\Agents\Mcp\Tools\DslReference;
use App\Agents\Mcp\Tools\GetCandles;
use App\Agents\Mcp\Tools\GetDepth;
use App\Agents\Mcp\Tools\GetFunding;
use App\Agents\Mcp\Tools\GetIndicators;
use App\Agents\Mcp\Tools\GetJob;
use App\Agents\Mcp\Tools\GetLiquidations;
use App\Agents\Mcp\Tools\GetListingStats;
use App\Agents\Mcp\Tools\GetStrategy;
use App\Agents\Mcp\Tools\ListInstruments;
use App\Agents\Mcp\Tools\ListStrategies;
use App\Agents\Mcp\Tools\ListTrials;
use App\Agents\Mcp\Tools\ListUniverse;
use App\Agents\Mcp\Tools\RunBacktest;
use App\Agents\Mcp\Tools\RunGrid;
use App\Agents\Mcp\Tools\SaveStrategy;
use App\Agents\Mcp\Tools\ScreenMarket;
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
and its probability of overfitting, not by its best in-sample return.

To look across markets: list_universe gives the USDT perpetuals of a month as they were ranked then, delisted ones
included. screen_market ranks them by return, volume, funding, open interest change or volatility over the days
before a date; it reads nothing later, so a past date gives what was known then. get_listing_stats says what newly
listed perpetuals did in their first days. get_depth and get_liquidations read one instrument's order book depth
and liquidations; liquidations are a lower bound and exist only from the day they were first collected.

A strategy over many instruments declares universe: instead of market: and ranks its members with rank,
percentile, top and bottom (dsl_reference, section 1). run_backtest and run_grid run it over the members of each
month, up to 40,000 instrument-days (instruments times days) a run. An account cannot run a universe strategy yet.

What is not stored is refused or left out, never filled in, and the refusal names what is missing.
You cannot place orders, change limits or promote a strategy, and you cannot import data or build the universe:
the operator decides and does that.
TEXT)]
final class TradingServer extends Server
{
    /** Every tool in the first page of tools/list: an agent that does not ask for the next page still sees them all. */
    public int $defaultPaginationLength = 50;

    protected array $tools = [
        ListInstruments::class,
        GetCandles::class,
        GetIndicators::class,
        GetFunding::class,
        ListUniverse::class,
        GetDepth::class,
        GetLiquidations::class,
        ScreenMarket::class,
        GetListingStats::class,
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
