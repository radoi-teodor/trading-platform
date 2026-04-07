"""
XTBBroker – xStation5 streaming JSON socket API integration.

Protocol reference: http://developers.xstore.pro/documentation/
"""

from __future__ import annotations

import json
import logging
import socket
import ssl
import threading
import time
from datetime import datetime, timezone
from typing import Any

from .base import AccountInfo, BaseBroker, OrderResult, Position

# XTB server addresses
XTB_DEMO_HOST = "xapi.xtb.com"
XTB_LIVE_HOST = "xapi.xtb.com"
XTB_DEMO_PORT = 5124
XTB_LIVE_PORT = 5112
XTB_DEMO_STREAM_PORT = 5125
XTB_LIVE_STREAM_PORT = 5113


class XTBBroker(BaseBroker):
    """Broker implementation for the XTB xStation5 platform."""

    def __init__(self, user_id: str, password: str, demo: bool = True) -> None:
        self.user_id = user_id
        self.password = password
        self.demo = demo
        self.logger = logging.getLogger(__name__)

        self._host = XTB_DEMO_HOST if demo else XTB_LIVE_HOST
        self._port = XTB_DEMO_PORT if demo else XTB_LIVE_PORT
        self._stream_port = XTB_DEMO_STREAM_PORT if demo else XTB_LIVE_STREAM_PORT

        self._sock: ssl.SSLSocket | None = None
        self._stream_session_id: str | None = None
        self._keepalive_thread: threading.Thread | None = None
        self._keepalive_running = False

    # ── BaseBroker implementation ────────────────────────────────────────

    def connect(self) -> None:
        ctx = ssl.SSLContext(ssl.PROTOCOL_TLS_CLIENT)
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE

        raw_sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self._sock = ctx.wrap_socket(raw_sock, server_hostname=self._host)
        self._sock.connect((self._host, self._port))
        self._sock.settimeout(30)

        # Login
        response = self._send_command("login", {
            "userId": self.user_id,
            "password": self.password,
        })
        if not response.get("status"):
            raise ConnectionError(f"XTB login failed: {response.get('errorDescr', 'unknown')}")

        self._stream_session_id = response.get("streamSessionId")
        self.logger.info("XTB connected – session %s", self._stream_session_id)

        # Start keepalive ping
        self._keepalive_running = True
        self._keepalive_thread = threading.Thread(target=self._keepalive_loop, daemon=True)
        self._keepalive_thread.start()

    def disconnect(self) -> None:
        self._keepalive_running = False
        if self._sock:
            try:
                self._send_command("logout")
            except Exception:
                pass
            try:
                self._sock.close()
            except Exception:
                pass
            self._sock = None
        self.logger.info("XTB disconnected")

    def get_balance(self) -> float:
        resp = self._send_command("getMarginLevel")
        data = resp.get("returnData", {})
        return float(data.get("balance", 0))

    def get_positions(self) -> list[Position]:
        resp = self._send_command("getTrades", {"openedOnly": True})
        trades = resp.get("returnData", [])
        positions: list[Position] = []
        for t in trades:
            cmd = t.get("cmd", 0)  # 0=buy, 1=sell
            positions.append(
                Position(
                    symbol=t.get("symbol", ""),
                    quantity=float(t.get("volume", 0)),
                    avg_cost=float(t.get("open_price", 0)),
                    current_price=float(t.get("close_price", 0)),
                    unrealized_pnl=float(t.get("profit", 0)),
                    side="long" if cmd == 0 else "short",
                )
            )
        return positions

    def place_order(
        self,
        symbol: str,
        side: str,
        quantity: float,
        order_type: str = "market",
        price: float | None = None,
    ) -> OrderResult:
        # XTB command: 0=BUY, 1=SELL
        cmd = 0 if side == "buy" else 1

        # XTB type: 0=OPEN, 2=BUY_LIMIT, 3=SELL_LIMIT, 4=BUY_STOP, 5=SELL_STOP
        if order_type == "market":
            xtb_type = 0
        elif order_type == "limit":
            xtb_type = 2 if side == "buy" else 3
        elif order_type in ("stop", "stop_limit"):
            xtb_type = 4 if side == "buy" else 5
        else:
            xtb_type = 0

        trade_info: dict[str, Any] = {
            "cmd": cmd,
            "symbol": symbol,
            "volume": quantity,
            "type": xtb_type,
        }
        if price is not None:
            trade_info["price"] = price
        else:
            # For market orders, get the current price
            current = self.get_ticker_price(symbol)
            trade_info["price"] = current

        resp = self._send_command("tradeTransaction", {"tradeTransInfo": trade_info})
        order_id = str(resp.get("returnData", {}).get("order", 0))
        status = "filled" if order_type == "market" else "pending"

        self.logger.info("XTB order %s: %s %s %.4f %s", order_id, side, symbol, quantity, order_type)

        return OrderResult(
            order_id=order_id,
            symbol=symbol,
            side=side,
            quantity=quantity,
            order_type=order_type,
            status=status,
            fill_price=trade_info.get("price"),
            filled_quantity=quantity if status == "filled" else 0.0,
            submitted_at=datetime.now(timezone.utc),
        )

    def cancel_order(self, order_id: str) -> bool:
        try:
            resp = self._send_command("tradeTransaction", {
                "tradeTransInfo": {
                    "order": int(order_id),
                    "type": 4,  # DELETE
                },
            })
            return resp.get("status", False)
        except Exception as exc:
            self.logger.warning("XTB cancel order %s failed: %s", order_id, exc)
            return False

    def get_order_status(self, order_id: str) -> OrderResult:
        resp = self._send_command("tradeTransactionStatus", {"order": int(order_id)})
        data = resp.get("returnData", {})
        status_map = {0: "error", 1: "pending", 3: "filled", 4: "rejected"}
        return OrderResult(
            order_id=order_id,
            symbol="",
            side="",
            quantity=0,
            order_type="",
            status=status_map.get(data.get("requestStatus", 0), "unknown"),
            raw=data,
        )

    def get_ticker_price(self, symbol: str) -> float:
        resp = self._send_command("getSymbol", {"symbol": symbol})
        data = resp.get("returnData", {})
        ask = float(data.get("ask", 0))
        bid = float(data.get("bid", 0))
        return (ask + bid) / 2.0

    def get_account_info(self) -> AccountInfo:
        resp = self._send_command("getMarginLevel")
        data = resp.get("returnData", {})
        return AccountInfo(
            equity=float(data.get("equity", 0)),
            cash=float(data.get("balance", 0)),
            buying_power=float(data.get("margin_free", 0)),
            currency=data.get("currency", "USD"),
            raw=data,
        )

    # ── XTB Socket helpers ───────────────────────────────────────────────

    def _send_command(self, command: str, arguments: dict[str, Any] | None = None) -> dict[str, Any]:
        """Send a JSON command over the XTB socket and return the response."""
        assert self._sock is not None, "Not connected"
        payload: dict[str, Any] = {"command": command}
        if arguments:
            payload["arguments"] = arguments

        raw = json.dumps(payload) + "\n"
        self._sock.sendall(raw.encode("utf-8"))

        # Read response (may arrive in chunks)
        chunks: list[bytes] = []
        while True:
            try:
                chunk = self._sock.recv(8192)
                if not chunk:
                    break
                chunks.append(chunk)
                # XTB terminates JSON responses with \n\n
                if b"\n\n" in b"".join(chunks):
                    break
            except socket.timeout:
                break

        response_text = b"".join(chunks).decode("utf-8").strip()
        if not response_text:
            return {"status": False, "errorDescr": "Empty response"}

        try:
            return json.loads(response_text)
        except json.JSONDecodeError:
            self.logger.error("Invalid JSON from XTB: %s", response_text[:500])
            return {"status": False, "errorDescr": "Invalid JSON response"}

    def _keepalive_loop(self) -> None:
        """Send a ping every 30 seconds to keep the connection alive."""
        while self._keepalive_running:
            time.sleep(30)
            if self._keepalive_running and self._sock:
                try:
                    self._send_command("ping")
                except Exception as exc:
                    self.logger.warning("XTB keepalive ping failed: %s", exc)
