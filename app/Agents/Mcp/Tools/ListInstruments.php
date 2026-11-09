<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Market\Models\Instrument;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list_instruments')]
#[Description('Instruments with stored 1-minute candles: market, symbol, status, and the first and last minute stored.')]
#[IsReadOnly]
final class ListInstruments extends TradingTool
{
    public function handle(Request $request, ConnectionInterface $db): Response
    {
        $coverage = $db->table('candles')->where('timeframe', '1m')->groupBy('instrument_id')
            ->selectRaw('instrument_id, min(open_time) as first, max(open_time) as last')->get()->keyBy('instrument_id');
        $rows = [];

        foreach (Instrument::query()->whereIn('id', $coverage->keys())->orderBy('market')->orderBy('symbol')->get() as $instrument) {
            $range = $coverage[$instrument->id] ?? null;
            $rows[] = [
                'market' => $instrument->market->value,
                'symbol' => $instrument->symbol,
                'status' => $instrument->status->value,
                'first_minute' => self::minute(is_object($range) ? ($range->first ?? null) : null),
                'last_minute' => self::minute(is_object($range) ? ($range->last ?? null) : null),
            ];
        }

        return $this->json(['instruments' => $rows]);
    }

    private static function minute(mixed $value): ?string
    {
        return is_string($value) ? CarbonImmutable::parse($value, 'UTC')->utc()->toIso8601ZuluString() : null;
    }
}
