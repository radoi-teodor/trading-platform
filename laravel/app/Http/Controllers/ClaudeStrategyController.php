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
            ->latest('created_at')
            ->first();

        return view('strategies.today', compact('analysis'));
    }

    public function trigger(Request $request)
    {
        $user = Auth::user();

        // Create a pending analysis record immediately so the user sees it
        $analysis = ClaudeAnalysis::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'market_outlook' => 'neutral',
            'confidence' => 0,
            'reasoning' => '',
            'strategy_json' => '{}',
            'status' => 'pending',
            'trades_planned' => 0,
            'prompt_sent' => 'Pending — analysis triggered manually.',
            'raw_response' => '',
            'created_at' => now(),
        ]);

        // Notify Python agents via Redis
        try {
            Redis::publish('trading:commands', json_encode([
                'command' => 'trigger_analysis',
                'user_id' => $user->id,
                'analysis_id' => $analysis->id,
                'timestamp' => now()->toISOString(),
            ]));
        } catch (\Exception $e) {
            // Redis might not be available — Python will still pick it up on next cycle
        }

        return redirect('/strategies')->with('success', 'Analysis triggered — status: pending. Refresh to see results when ready.');
    }

    public function cancel($id)
    {
        $analysis = ClaudeAnalysis::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $analysis->update([
            'status' => 'failed',
            'reasoning' => 'Cancelled by user.',
        ]);

        return back()->with('success', 'Analysis cancelled.');
    }
}
