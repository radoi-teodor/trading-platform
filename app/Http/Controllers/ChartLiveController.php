<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Web\Chart\LiveBar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * The live chart's second-by-second read (D-87), apart from Livewire: a poll inside the component travelled in the
 * same request as the operator's clicks and swallowed their re-render. It reads the database only.
 */
final class ChartLiveController
{
    public function __invoke(Request $request, LiveBar $live): JsonResponse
    {
        $request->validate([
            'symbol' => ['required', 'string', 'max:64'],
            'timeframe' => ['required', 'string', 'max:8'],
            'through' => ['required', 'date'],
        ]);

        try {
            return response()->json($live->read($request->string('symbol')->toString(), $request->string('timeframe')->toString(), $request->string('through')->toString()));
        } catch (InvalidArgumentException $invalid) {
            return response()->json(['message' => $invalid->getMessage()], 422);
        }
    }
}
