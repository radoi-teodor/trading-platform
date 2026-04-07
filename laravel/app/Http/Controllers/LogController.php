<?php

namespace App\Http\Controllers;

use App\Models\BotLog;
use App\Models\ClaudeAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = BotLog::where('user_id', Auth::id());

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $logs = $query->orderByDesc('created_at')->paginate(50)->withQueryString();

        return view('logs.index', compact('logs'));
    }

    public function claude(Request $request)
    {
        $query = ClaudeAnalysis::where('user_id', Auth::id())
            ->select(['id', 'date', 'status', 'market_outlook', 'confidence', 'prompt_sent', 'raw_response', 'created_at']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $analyses = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('logs.claude', compact('analyses'));
    }
}
