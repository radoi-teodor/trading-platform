<?php

namespace App\Http\Controllers;

use App\Models\ClaudeAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class ClaudeStrategyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ClaudeAnalysis::where('user_id', $user->id)
            ->orderByDesc('date');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->input('to'));
        }

        if ($request->filled('outlook')) {
            $query->where('market_outlook', $request->input('outlook'));
        }

        $analyses = $query->paginate(15)->withQueryString();

        return view('strategies.index', compact('analyses'));
    }

    public function show($id)
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->with(['analysisTrades.executedOrder', 'analysisCloses', 'analysisAlerts.resultOrder'])
            ->findOrFail($id);

        return view('strategies.show', compact('analysis'));
    }

    public function today()
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->with(['analysisTrades.executedOrder', 'analysisCloses', 'analysisAlerts.resultOrder'])
            ->latest()
            ->first();

        return view('strategies.today', compact('analysis'));
    }

    public function trigger(Request $request)
    {
        $user = Auth::user();

        try {
            Redis::publish('trading:commands', json_encode([
                'type' => 'trigger_analysis',
                'user_id' => $user->id,
                'timestamp' => now()->toISOString(),
            ]));

            return back()->with('success', 'Analysis triggered. Results will appear shortly.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to trigger analysis: ' . $e->getMessage());
        }
    }
}
