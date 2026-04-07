"""
PromptBuilder – constructs the structured prompts sent to Claude CLI.

Each static method assembles a full prompt string with all the context
sections Claude needs to produce a valid JSON trading strategy.
"""

from __future__ import annotations

import json
from typing import Any


class PromptBuilder:
    """Static helper that builds prompt strings for the ClaudeEngine."""

    # ── JSON response schema that Claude must follow ─────────────────────
    RESPONSE_SCHEMA: str = json.dumps(
        {
            "date": "YYYY-MM-DD",
            "market_outlook": "bullish | bearish | neutral",
            "confidence": "0.0–1.0",
            "reasoning": "string – detailed market analysis",
            "risk_assessment": "string – risk warnings",
            "lessons_from_yesterday": "string – what was learned",
            "next_review": "midday | end_of_day | tomorrow",
            "max_open_positions": "number (max positions open at same time, e.g. 2-3)",
            "trades": [
                {
                    "symbol": "string",
                    "direction": "long | short",
                    "quantity": "number (amount to buy/sell)",
                    "order_type": "market | limit",
                    "entry_price": "number | null (cents; null = market price)",
                    "entry_condition": "price_below | price_above",
                    "stop_loss_pct": "number (e.g. 0.3 = close if -0.3% from entry)",
                    "take_profit_pct": "number (e.g. 0.8 = close if +0.8% from entry)",
                    "stop_loss": "number | null (fixed price in cents, alternative to pct)",
                    "take_profit": "number | null (fixed price in cents, alternative to pct)",
                    "confidence": "0.0–1.0",
                    "reasoning": "string",
                    "reentry_allowed": "boolean – re-open after TP/SL close",
                    "max_entries": "number (max cycles for this setup today)",
                }
            ],
            "positions_to_close": [
                {
                    "symbol": "string",
                    "reason": "string",
                    "urgency": "immediate | end_of_day",
                }
            ],
            "watchlist_alerts": [
                {
                    "symbol": "string",
                    "condition_text": "string",
                    "parsed": {"operator": "lt | gt", "value": "number (cents)"},
                }
            ],
        },
        indent=2,
    )

    # ──────────────────────────────────────────────────────────────────────
    @staticmethod
    def build_daily_analysis(context: dict[str, Any]) -> str:
        """Build the full daily-analysis mega-prompt.

        Sections:
        1. System context / JSON-only instruction
        2. User profile
        3. Portfolio state
        4. Market data
        5. News & sentiment
        6. Yesterday's results
        7. Constraints
        8. Required response schema
        """
        user = context.get("user_profile", {})
        portfolio = context.get("portfolio", {})
        market = context.get("market_data", {})
        news = context.get("news", [])
        yesterday = context.get("yesterday_results", {})
        constraints = context.get("constraints", {})
        date = context.get("date", "today")
        custom_notes = context.get("custom_notes", "")

        # ── Strategy style instructions ──────────────────────────────────
        strategy_style = context.get("strategy_style", "day_trading")
        style_instructions = {
            "scalping": (
                "SCALPING MODE: Generate 20-50 small trades. Target 0.1-0.3% profit per trade. "
                "Very tight stop losses (0.1-0.2%). Set reentry_allowed=true and max_entries=10-20 "
                "per symbol. Use 1-3% of capital per trade."
            ),
            "day_trading": (
                "DAY TRADING MODE: Generate 5-15 trades. Target 0.5-2% profit per trade. "
                "Moderate stop losses (0.5-1%). Set reentry_allowed=true and max_entries=3-5 "
                "per symbol. Use 3-8% of capital per trade."
            ),
            "swing_trading": (
                "SWING TRADING MODE: Generate 1-3 high-conviction trades. Target 2-5% profit. "
                "Wider stop losses (1-3%). Set reentry_allowed=false. Use 10-20% of capital per trade. "
                "Hold for hours to days."
            ),
            "conservative": (
                "CONSERVATIVE MODE: Generate 1-2 very carefully selected trades only. "
                "Target 1-3% profit. Only enter on strong technical + fundamental confluence. "
                "Use 5-10% of capital per trade. If no clear opportunity exists, recommend 0 trades."
            ),
        }
        active_style_instruction = style_instructions.get(strategy_style, style_instructions["day_trading"])

        sections: list[str] = []

        # ── System instruction ───────────────────────────────────────────
        system_text = (
            "SYSTEM: You are an expert AI trading strategist. "
            "Respond ONLY with valid JSON conforming exactly to the schema "
            "provided at the end of this prompt. Do NOT include any markdown, "
            "explanation, or text outside the JSON object.\n\n"
            f"STRATEGY STYLE: {active_style_instruction}\n\n"
            "POSITION-BASED TRADING (MANDATORY RULES):\n"
            "Every trade you recommend MUST be a complete position with ALL of these:\n"
            "  - symbol: the trading pair\n"
            "  - direction: \"long\" (bot buys to open, sells to close) or \"short\" (bot sells to open, buys to close)\n"
            "  - quantity: how much to buy/sell\n"
            "  - stop_loss_pct: MANDATORY — percentage to exit at loss (e.g., 0.3 for 0.3%)\n"
            "  - take_profit_pct: MANDATORY — percentage to exit at profit (e.g., 0.8 for 0.8%)\n\n"
            "The bot handles the full lifecycle automatically:\n"
            "  1. OPENS position when entry condition is met\n"
            "  2. MONITORS price every 30 seconds\n"
            "  3. CLOSES when take_profit_pct or stop_loss_pct is hit\n"
            "  4. Optionally RE-ENTERS if reentry_allowed=true\n\n"
            "DO NOT send separate \"buy\" and \"sell\" trades for the same symbol.\n"
            "Each trade IS a complete round-trip position.\n\n"
            "CRITICAL: Every trade MUST have stop_loss_pct and take_profit_pct set.\n"
            "If you cannot determine good levels, do not recommend the trade.\n\n"
            "EXISTING POSITIONS:\n"
            "You MUST address ALL existing open positions in your strategy.\n"
            "For each open position, either:\n"
            "  a) Include it in positions_to_close with a reason, OR\n"
            "  b) Acknowledge it in your reasoning and explain why you're keeping it\n"
            "Do NOT ignore existing positions.\n\n"
            "POSITION LIMITS:\n"
            "- Set max_open_positions to control how many positions can be open AT THE SAME TIME\n"
            "- The bot will NOT open a new position if max_open_positions is reached\n"
            "- Consider the user's EXISTING positions when setting this limit\n"
            "- DO NOT over-allocate capital. Total position sizes should not exceed available cash.\n\n"
            "STOP LOSS & TAKE PROFIT:\n"
            "  PREFER percentage mode (stop_loss_pct, take_profit_pct) — adapts to actual entry price.\n"
            "  Example: stop_loss_pct=0.3 means close if price moves -0.3% from entry.\n"
            "  Example: take_profit_pct=0.8 means close if price moves +0.8% from entry.\n"
            "  You can also use fixed prices in cents (stop_loss, take_profit) for key levels.\n"
            "  If both pct and fixed are set, percentage takes priority.\n\n"
            "ENTRY CONDITIONS:\n"
            "  entry_condition='price_below' + entry_price=X → open when price drops to X\n"
            "  entry_condition='price_above' + entry_price=X → open when price rises to X\n"
            "  entry_price=null → open immediately at market price"
        )

        if custom_notes:
            system_text += f"\n\nUSER CUSTOM NOTES FOR THIS ANALYSIS: {custom_notes}"

        sections.append(system_text)

        # ── Section 1: User profile ──────────────────────────────────────
        broker_type = context.get("broker_type", "paper")
        broker_info = {
            "paper": "Paper broker (simulated) — supports all symbols (stocks, crypto, forex)",
            "binance": "Binance — ONLY crypto pairs (BTC, ETH, BNB, SOL, etc. traded against USDT). Do NOT recommend stocks like AAPL/MSFT.",
            "alpaca": "Alpaca — US stocks and ETFs only. Do NOT recommend crypto.",
            "xtb": "XTB — Forex, stocks CFDs, indices, commodities.",
        }.get(broker_type, "Unknown broker")

        sections.append(
            "=== SECTION 1: USER PROFILE ===\n"
            f"Active broker: {broker_type} — {broker_info}\n"
            f"IMPORTANT: Only recommend symbols that are tradeable on {broker_type}.\n"
            f"Risk tolerance: {user.get('risk_tolerance', 'moderate')}\n"
            f"Preferred markets: {', '.join(user.get('markets', ['US equities']))}\n"
            f"Available capital: ${user.get('capital', 0):,.2f}\n"
            f"Trading style: {user.get('trading_style', 'swing')}\n"
            f"Custom instructions: {user.get('custom_instructions', 'None')}"
        )

        # ── Section 2: Portfolio state ───────────────────────────────────
        positions_text = ""
        for pos in portfolio.get("positions", []):
            positions_text += (
                f"  {pos['symbol']}: {pos['quantity']} shares @ "
                f"${pos.get('avg_cost', 0):.2f}  "
                f"current ${pos.get('current_price', 0):.2f}  "
                f"P&L ${pos.get('unrealized_pnl', 0):.2f}\n"
            )

        sections.append(
            "=== SECTION 2: PORTFOLIO STATE ===\n"
            f"Cash available: ${portfolio.get('cash', 0):,.2f}\n"
            f"Total equity: ${portfolio.get('total_equity', 0):,.2f}\n"
            f"Day P&L: ${portfolio.get('day_pnl', 0):,.2f}\n"
            f"Open positions:\n{positions_text or '  (none)'}\n"
            f"Win rate (30d): {portfolio.get('win_rate_30d', 'N/A')}\n"
            f"Sharpe ratio: {portfolio.get('sharpe_ratio', 'N/A')}"
        )

        # ── Section 3: Market data ───────────────────────────────────────
        market_lines: list[str] = []
        for symbol, data in market.items():
            line = (
                f"  {symbol}: O={data.get('open')}, H={data.get('high')}, "
                f"L={data.get('low')}, C={data.get('close')}, V={data.get('volume')}"
            )
            indicators = data.get("indicators", {})
            if indicators:
                parts = [f"{k}={v}" for k, v in indicators.items()]
                line += f"  [{', '.join(parts)}]"
            anomaly = data.get("volume_anomaly")
            if anomaly:
                line += f"  *** VOLUME ANOMALY: {anomaly} ***"
            market_lines.append(line)

        sections.append(
            "=== SECTION 3: MARKET DATA ===\n" + "\n".join(market_lines or ["  (no data)"])
        )

        # ── Section 4: News & sentiment ──────────────────────────────────
        news_lines: list[str] = []
        for article in news[:30]:  # cap to keep prompt reasonable
            news_lines.append(
                f"  [{article.get('source', '?')}] "
                f"{article.get('title', '')}  "
                f"(sentiment={article.get('sentiment_score', 'N/A')}, "
                f"symbols={article.get('symbols', [])})"
            )

        per_symbol: dict[str, list[float]] = {}
        for article in news:
            score = article.get("sentiment_score")
            if score is not None:
                for sym in article.get("symbols", []):
                    per_symbol.setdefault(sym, []).append(float(score))
        agg_lines = [
            f"  {sym}: avg_sentiment={sum(s)/len(s):.2f} ({len(s)} articles)"
            for sym, s in per_symbol.items()
        ]

        sections.append(
            "=== SECTION 4: NEWS & SENTIMENT ===\n"
            + "\n".join(news_lines or ["  (no recent news)"])
            + "\n\nPer-symbol aggregation:\n"
            + "\n".join(agg_lines or ["  (none)"])
        )

        # ── Section 4b: Recent trade history ─────────────────────────────
        recent_trades = context.get("recent_trades", [])
        trades_lines: list[str] = []
        for t in recent_trades[:20]:
            trades_lines.append(
                f"  {t.get('executed_at', '?')} | {t['side'].upper()} {t['symbol']} "
                f"qty={t['quantity']} @ ${t['price']:.2f}  "
                f"P&L=${t.get('pnl', 0):.2f}  fee=${t.get('fee', 0):.2f}"
            )
        sections.append(
            "=== SECTION 4b: RECENT TRADE HISTORY (from broker) ===\n"
            "These are actual executed trades from the broker account. "
            "Use this to understand current exposure and recent performance.\n"
            + "\n".join(trades_lines or ["  (no recent trades)"])
        )

        # ── Section 4c: Currently pending trades ────────────────────────
        pending_trades = context.get("pending_trades", [])
        pending_lines: list[str] = []
        for t in pending_trades:
            entry = f"${t['entry_price']:.2f}" if t.get("entry_price") else "market"
            sl = f"${t['stop_loss']:.2f}" if t.get("stop_loss") else "none"
            tp = f"${t['take_profit']:.2f}" if t.get("take_profit") else "none"
            pending_lines.append(
                f"  {t['action'].upper()} {t['symbol']} qty={t['quantity']} "
                f"entry={entry} SL={sl} TP={tp} urgency={t.get('urgency', '?')}"
            )
        sections.append(
            "=== SECTION 4c: CURRENTLY PENDING TRADES ===\n"
            "These trades are already planned from a previous strategy and are waiting to execute.\n"
            "You may keep, modify, or cancel them. If you don't mention them, they will remain active.\n"
            + "\n".join(pending_lines or ["  (no pending trades)"])
        )

        # ── Section 5: Yesterday's results ───────────────────────────────
        prev_trades_text = ""
        for t in yesterday.get("trades", []):
            prev_trades_text += (
                f"  {t.get('symbol')} {t.get('action')} {t.get('quantity')} "
                f"@ ${t.get('fill_price', 0):.2f}  "
                f"result: ${t.get('realized_pnl', 0):.2f}\n"
            )

        sections.append(
            "=== SECTION 5: YESTERDAY'S RESULTS ===\n"
            f"Previous outlook: {yesterday.get('market_outlook', 'N/A')}\n"
            f"Previous confidence: {yesterday.get('confidence', 'N/A')}\n"
            f"Executed trades:\n{prev_trades_text or '  (none)'}\n"
            f"Total P&L: ${yesterday.get('total_pnl', 0):,.2f}"
        )

        # ── Section 6: Constraints ───────────────────────────────────────
        allowed = constraints.get('allowed_symbols', 'any')
        if isinstance(allowed, list):
            allowed_str = ', '.join(allowed)
        else:
            allowed_str = str(allowed)

        sections.append(
            "=== SECTION 6: CONSTRAINTS ===\n"
            f"Max position size (% of equity): {constraints.get('max_position_pct', 20)}%\n"
            f"Max daily loss (% of equity): {constraints.get('max_daily_loss_pct', 5)}%\n"
            f"Max trades per day: {constraints.get('max_trades', 10)}\n"
            f"ALLOWED SYMBOLS (ONLY trade these): {allowed_str}\n"
            "IMPORTANT: Do NOT recommend trades for any symbol not listed above."
        )

        # ── Required response schema ─────────────────────────────────────
        sections.append(
            "=== REQUIRED RESPONSE SCHEMA ===\n"
            "Respond with a single JSON object matching this schema EXACTLY.\n"
            "IMPORTANT: All price fields (entry_price, stop_loss, take_profit, "
            "and parsed condition values) must be expressed in CENTS, not dollars. "
            "For example, $150.25 should be written as 15025.\n\n"
            f"{PromptBuilder.RESPONSE_SCHEMA}"
        )

        sections.append(f"Analysis date: {date}")

        return "\n\n".join(sections)

    # ──────────────────────────────────────────────────────────────────────
    @staticmethod
    def build_midday_review(context: dict[str, Any]) -> str:
        """Lighter midday check-in prompt."""
        positions = context.get("positions", [])
        market_update = context.get("market_update", {})
        original_strategy = context.get("original_strategy", {})

        pos_text = ""
        for p in positions:
            pos_text += (
                f"  {p['symbol']}: {p['quantity']} @ ${p.get('avg_cost', 0):.2f}  "
                f"now ${p.get('current_price', 0):.2f}  "
                f"P&L ${p.get('unrealized_pnl', 0):.2f}\n"
            )

        return (
            "SYSTEM: You are an AI trading strategist performing a midday review. "
            "Respond ONLY with valid JSON matching the schema below.\n\n"
            "=== CURRENT POSITIONS ===\n"
            f"{pos_text or '  (none)'}\n\n"
            "=== MARKET UPDATE ===\n"
            f"{json.dumps(market_update, indent=2)}\n\n"
            "=== ORIGINAL MORNING STRATEGY ===\n"
            f"{json.dumps(original_strategy, indent=2)}\n\n"
            "Based on the market movement since the morning analysis, "
            "do any positions need adjusting? Should any pending trades "
            "be cancelled or modified? Provide updated trades if needed.\n\n"
            f"=== REQUIRED RESPONSE SCHEMA ===\n{PromptBuilder.RESPONSE_SCHEMA}"
        )

    # ──────────────────────────────────────────────────────────────────────
    @staticmethod
    def build_eod_summary(results: dict[str, Any]) -> str:
        """End-of-day narrative summary prompt."""
        trades_text = ""
        for t in results.get("trades", []):
            trades_text += (
                f"  {t.get('symbol')} {t.get('action')} {t.get('quantity')} "
                f"@ ${t.get('fill_price', 0):.2f}  "
                f"P&L: ${t.get('realized_pnl', 0):.2f}\n"
            )

        return (
            "SYSTEM: You are an AI trading strategist writing an end-of-day summary. "
            "Respond with a clear, narrative text summary (NOT JSON).\n\n"
            "=== TODAY'S RESULTS ===\n"
            f"Date: {results.get('date', 'N/A')}\n"
            f"Starting equity: ${results.get('starting_equity', 0):,.2f}\n"
            f"Ending equity: ${results.get('ending_equity', 0):,.2f}\n"
            f"Day P&L: ${results.get('day_pnl', 0):,.2f}\n"
            f"Day return: {results.get('day_return_pct', 0):.2f}%\n\n"
            "=== EXECUTED TRADES ===\n"
            f"{trades_text or '  (none)'}\n\n"
            f"Morning outlook was: {results.get('morning_outlook', 'N/A')} "
            f"(confidence {results.get('morning_confidence', 'N/A')})\n\n"
            "Please provide:\n"
            "1. A narrative summary of how the day went\n"
            "2. What worked well and what did not\n"
            "3. Key lessons learned\n"
            "4. Any observations for tomorrow's strategy"
        )
