"""
StrategyParser – validates and normalises the JSON strategy returned by Claude.
"""

from __future__ import annotations

import json
import logging
import re
from typing import Any

logger = logging.getLogger(__name__)


class StrategyValidationError(Exception):
    """Raised when Claude's response fails validation."""


class StrategyParser:
    """Parse, validate, and normalise a Claude strategy response."""

    VALID_OUTLOOKS = {"bullish", "bearish", "neutral"}
    VALID_ACTIONS = {"buy", "sell"}
    VALID_ORDER_TYPES = {"market", "limit"}
    VALID_URGENCIES = {"immediate", "wait_for_dip", "end_of_day"}

    REQUIRED_TOP_LEVEL = {"date", "market_outlook", "confidence", "reasoning", "trades"}
    REQUIRED_TRADE_FIELDS = {"symbol", "action", "order_type", "quantity"}

    @classmethod
    def parse(cls, raw_text: str, constraints: dict[str, Any] | None = None) -> dict[str, Any]:
        """Parse *raw_text* into a validated strategy dict.

        Parameters
        ----------
        raw_text:
            The raw string returned by the Claude CLI (expected JSON, but may
            be wrapped in markdown code fences).
        constraints:
            User-specific trading constraints used for additional validation.

        Returns
        -------
        dict
            The validated strategy dictionary.

        Raises
        ------
        StrategyValidationError
            If the response is unparseable or violates any rule.
        """
        constraints = constraints or {}
        cleaned = cls._strip_markdown(raw_text)
        data = cls._parse_json(cleaned)
        cls._validate_top_level(data)
        cls._validate_trades(data.get("trades", []), constraints)
        return data

    # ── Helpers ──────────────────────────────────────────────────────────

    @staticmethod
    def _strip_markdown(text: str) -> str:
        """Remove markdown code fences (```json ... ```) if present."""
        text = text.strip()
        # Remove opening ```json or ``` and closing ```
        text = re.sub(r"^```(?:json)?\s*\n?", "", text)
        text = re.sub(r"\n?```\s*$", "", text)
        return text.strip()

    @staticmethod
    def _parse_json(text: str) -> dict[str, Any]:
        """Extract and parse the first JSON object from *text*.

        Claude sometimes adds extra text before/after the JSON.
        This method finds the first { ... } block and parses it.
        """
        # Try direct parse first
        try:
            data = json.loads(text)
            if isinstance(data, dict):
                return data
        except json.JSONDecodeError:
            pass

        # Find the first { and match to its closing }
        start = text.find("{")
        if start == -1:
            raise StrategyValidationError("No JSON object found in response")

        depth = 0
        in_string = False
        escape_next = False
        end = start

        for i in range(start, len(text)):
            ch = text[i]
            if escape_next:
                escape_next = False
                continue
            if ch == "\\":
                escape_next = True
                continue
            if ch == '"' and not escape_next:
                in_string = not in_string
                continue
            if in_string:
                continue
            if ch == "{":
                depth += 1
            elif ch == "}":
                depth -= 1
                if depth == 0:
                    end = i + 1
                    break

        if depth != 0:
            raise StrategyValidationError("Unbalanced braces in JSON response")

        json_str = text[start:end]
        try:
            data = json.loads(json_str)
        except json.JSONDecodeError as exc:
            raise StrategyValidationError(f"Extracted JSON is invalid: {exc}") from exc

        if not isinstance(data, dict):
            raise StrategyValidationError(
                f"Expected a JSON object at top level, got {type(data).__name__}"
            )
        return data

    @classmethod
    def _validate_top_level(cls, data: dict[str, Any]) -> None:
        """Check required top-level fields and enum values."""
        missing = cls.REQUIRED_TOP_LEVEL - data.keys()
        if missing:
            raise StrategyValidationError(f"Missing required top-level fields: {missing}")

        outlook = data["market_outlook"]
        if outlook not in cls.VALID_OUTLOOKS:
            raise StrategyValidationError(
                f"Invalid market_outlook '{outlook}'; must be one of {cls.VALID_OUTLOOKS}"
            )

        confidence = data["confidence"]
        try:
            conf_float = float(confidence)
        except (TypeError, ValueError):
            raise StrategyValidationError(f"confidence must be a number, got {confidence!r}")
        if not 0.0 <= conf_float <= 1.0:
            raise StrategyValidationError(f"confidence must be 0.0–1.0, got {conf_float}")
        data["confidence"] = conf_float

        if not isinstance(data["trades"], list):
            raise StrategyValidationError("trades must be a list")

    @classmethod
    def _validate_trades(
        cls,
        trades: list[dict[str, Any]],
        constraints: dict[str, Any],
    ) -> None:
        """Validate every trade entry against schema rules and user constraints."""
        max_trades = constraints.get("max_trades")
        if max_trades is not None and len(trades) > int(max_trades):
            raise StrategyValidationError(
                f"Number of trades ({len(trades)}) exceeds max_trades ({max_trades})"
            )

        max_position_pct = constraints.get("max_position_pct")
        allowed_symbols = constraints.get("allowed_symbols")  # list or "any"
        forbidden_symbols = set(constraints.get("forbidden_symbols", []))

        for idx, trade in enumerate(trades):
            label = f"trades[{idx}]"

            # Required fields
            missing = cls.REQUIRED_TRADE_FIELDS - trade.keys()
            if missing:
                raise StrategyValidationError(f"{label}: missing fields {missing}")

            # Enum checks
            if trade["action"] not in cls.VALID_ACTIONS:
                raise StrategyValidationError(
                    f"{label}: invalid action '{trade['action']}'; "
                    f"must be one of {cls.VALID_ACTIONS}"
                )

            if trade["order_type"] not in cls.VALID_ORDER_TYPES:
                raise StrategyValidationError(
                    f"{label}: invalid order_type '{trade['order_type']}'; "
                    f"must be one of {cls.VALID_ORDER_TYPES}"
                )

            urgency = trade.get("urgency", "normal")
            if urgency not in cls.VALID_URGENCIES:
                raise StrategyValidationError(
                    f"{label}: invalid urgency '{urgency}'; "
                    f"must be one of {cls.VALID_URGENCIES}"
                )

            # Quantity must be positive
            try:
                qty = float(trade["quantity"])
            except (TypeError, ValueError):
                raise StrategyValidationError(
                    f"{label}: quantity must be a number, got {trade['quantity']!r}"
                )
            if qty <= 0:
                raise StrategyValidationError(f"{label}: quantity must be > 0, got {qty}")

            # Symbol constraints
            symbol = trade["symbol"].upper()
            trade["symbol"] = symbol

            if symbol in forbidden_symbols:
                raise StrategyValidationError(
                    f"{label}: symbol '{symbol}' is in the forbidden list"
                )

            if (
                allowed_symbols
                and allowed_symbols != "any"
                and isinstance(allowed_symbols, list)
                and symbol not in [s.upper() for s in allowed_symbols]
            ):
                raise StrategyValidationError(
                    f"{label}: symbol '{symbol}' is not in the allowed list"
                )

            # Max position size (percentage of equity) – we can only flag it
            # here; actual dollar validation happens at execution time.
            if max_position_pct is not None:
                trade["_max_position_pct"] = float(max_position_pct)
