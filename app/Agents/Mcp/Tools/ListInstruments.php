<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\BoundedRead;
use App\Agents\Mcp\TradingTool;
use App\Market\CandleRepository;
use App\Market\Models\Instrument;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list_instruments')]
#[Description('Instruments with stored 1-minute candles: market, symbol, status, and the first and last minute stored. list_universe says which perpetuals a universe strategy trades in a month.')]
#[IsReadOnly]
final class ListInstruments extends TradingTool
{
    public function handle(Request $request, CandleRepository $candles, BoundedRead $read): Response
    {
        return $this->bounded($read, function () use ($candles): Response {
            $stored = $candles->coverage();
            $rows = [];

            foreach (Instrument::query()->whereIn('id', array_keys($stored))->orderBy('market')->orderBy('symbol')->get() as $instrument) {
                $rows[] = [
                    'market' => $instrument->market,
                    'symbol' => $instrument->symbol,
                    'status' => $instrument->status,
                    'first_minute' => $stored[$instrument->id][0],
                    'last_minute' => $stored[$instrument->id][1],
                ];
            }

            return $this->json(['instruments' => $rows]);
        });
    }
}
