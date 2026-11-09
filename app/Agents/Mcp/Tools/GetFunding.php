<?php

declare(strict_types=1);

namespace App\Agents\Mcp\Tools;

use App\Agents\Mcp\TradingTool;
use App\Market\Data\MarketType;
use App\Market\FundingRateRepository;
use App\Market\Models\Instrument;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Throwable;

#[Name('get_funding')]
#[Description('Funding rates of a perpetual between two days, as fractions per funding interval (0.0001 is 0.01 %).')]
#[IsReadOnly]
final class GetFunding extends TradingTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'symbol' => $schema->string()->description('A perpetual, such as BTC/USDT:USDT')->required(),
            'from' => $schema->string()->description('First day, YYYY-MM-DD')->required(),
            'to' => $schema->string()->description('Last day, YYYY-MM-DD; at most 92 days after from')->required(),
        ];
    }

    public function handle(Request $request, FundingRateRepository $funding): Response
    {
        try {
            $from = CarbonImmutable::parse($request->string('from')->value(), 'UTC')->startOfDay();
            $to = CarbonImmutable::parse($request->string('to')->value(), 'UTC')->endOfDay();
        } catch (Throwable) {
            return $this->refuse('from and to are days, YYYY-MM-DD.');
        }

        if ($from->diffInDays($to) > 92) {
            return $this->refuse('Ask for at most 92 days at a time.');
        }

        $instrument = Instrument::query()->where('market', MarketType::Perp)->where('symbol', $request->string('symbol')->value())->first();

        if ($instrument === null) {
            return $this->refuse($request->string('symbol')->value().' is not a stored perpetual.');
        }

        return $this->json(['funding' => array_map(fn ($rate): array => [
            'time' => $rate->fundingTime->format('Y-m-d\TH:i:s\Z'), 'rate' => (string) $rate->rate, 'interval_hours' => $rate->intervalHours,
        ], $funding->range($instrument, $from, $to))]);
    }
}
