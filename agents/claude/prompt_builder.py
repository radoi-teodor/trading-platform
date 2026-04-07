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
            "reasoning": "string – free-form explanation",
            "risk_assessment": "string – overall risk warnings and assessment",
            "lessons_from_yesterday": "string – what was learned from the previous session",
            "next_review": "midday | end_of_day | tomorrow",
            "trades": [
                {
                    "symbol": "string",
                    "action": "buy | sell",
                    "order_type": "market | limit",
                    "quantity": "number",
                    "entry_price": "number | null (price in cents; null for market orders)",
                    "stop_loss": "number | null (price in cents)",
                    "take_profit": "number | null (price in cents)",
                    "urgency": "immediate | wait_for_dip | end_of_day",
                    "confidence": "0.0–1.0",
                    "reasoning": "string",
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
                    "condition_text": "string – e.g. 'price drops below 15000'",
                    "parsed": {
                        "operator": "lt | gt | lte | gte",
                        "value": "number (cents)",
                    },
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

        sections: list[str] = []

        # ── System instruction ───────────────────────────────────────────
        sections.append(
            "SYSTEM: You are an expert AI trading strategist. "
            "Respond ONLY with valid JSON conforming exactly to the schema "
            "provided at the end of this prompt. Do NOT include any markdown, "
            "explanation, or text outside the JSON object."
        )

        # ── Section 1: User profile ──────────────────────────────────────
        sections.append(
            "=== SECTION 1: USER PROFILE ===\n"
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
        sections.append(
            "=== SECTION 6: CONSTRAINTS ===\n"
            f"Max position size (% of equity): {constraints.get('max_position_pct', 20)}%\n"
            f"Max daily loss: ${constraints.get('max_daily_loss', 0):,.2f}\n"
            f"Max trades per day: {constraints.get('max_trades', 10)}\n"
            f"Allowed symbols: {constraints.get('allowed_symbols', 'any')}\n"
            f"Forbidden symbols: {constraints.get('forbidden_symbols', 'none')}"
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
