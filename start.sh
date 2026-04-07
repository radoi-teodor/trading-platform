#!/bin/bash
# =============================================================================
# AI Trading Platform — Start Script
# Starts Laravel server, queue worker, and Python agents
# =============================================================================

set -e

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
PIDS=()

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

cleanup() {
    echo ""
    echo -e "${YELLOW}Shutting down...${NC}"
    for pid in "${PIDS[@]}"; do
        kill "$pid" 2>/dev/null || true
    done
    wait 2>/dev/null || true
    echo -e "${GREEN}All services stopped.${NC}"
    exit 0
}
trap cleanup SIGINT SIGTERM

echo -e "${GREEN}Starting AI Trading Platform...${NC}"

# --- Ensure SQLite DB exists ---
if [ -f "$PROJECT_DIR/laravel/.env" ] && grep -q "DB_CONNECTION=sqlite" "$PROJECT_DIR/laravel/.env"; then
    touch "$PROJECT_DIR/laravel/database/database.sqlite" 2>/dev/null || true
fi

# --- Laravel migrations & seed ---
echo "Running migrations..."
cd "$PROJECT_DIR/laravel"
php artisan migrate --force --quiet 2>/dev/null || php artisan migrate --force
php artisan db:seed --force --quiet 2>/dev/null || true

# --- Queue worker ---
echo "Starting queue worker..."
php artisan queue:work --tries=3 --timeout=120 --quiet &
PIDS+=($!)

# --- Laravel dev server ---
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000 --quiet &
PIDS+=($!)
cd "$PROJECT_DIR"

# --- Python agents ---
echo "Starting Python agents..."
cd "$PROJECT_DIR/agents"
if [ -d "venv" ]; then
    source venv/bin/activate
else
    echo "Creating Python virtual environment..."
    python3 -m venv venv
    source venv/bin/activate
    pip install -r requirements.txt -q
fi
python run.py &
PIDS+=($!)
cd "$PROJECT_DIR"

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}  AI Trading Platform is running${NC}"
echo -e "${GREEN}=========================================${NC}"
echo "  Web UI:    http://localhost:8000"
echo "  Login:     demo@example.com / password"
echo ""
echo "  Services running:"
echo "    - Laravel server    (PID ${PIDS[1]})"
echo "    - Queue worker      (PID ${PIDS[0]})"
echo "    - Python agents     (PID ${PIDS[2]:-N/A})"
echo ""
echo "  Press Ctrl+C to stop all services."
echo -e "${GREEN}=========================================${NC}"

wait
