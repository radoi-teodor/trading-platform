#!/bin/bash
set -e

# =============================================================================
# AI Trading Platform — Ubuntu Setup Script
# Tested on Ubuntu 22.04 LTS and 24.04 LTS
# =============================================================================

BOLD='\033[1m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

info()  { echo -e "${GREEN}[INFO]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; }
header() { echo -e "\n${BOLD}=== $1 ===${NC}\n"; }

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"

# ---------------------------------------------------------------------------
header "1/8 — System packages"
# ---------------------------------------------------------------------------
sudo apt-get update -qq
sudo apt-get install -y -qq \
    software-properties-common curl git unzip \
    build-essential libssl-dev libffi-dev \
    sqlite3 libsqlite3-dev > /dev/null 2>&1
info "Base packages installed."

# ---------------------------------------------------------------------------
header "2/8 — PHP 8.3"
# ---------------------------------------------------------------------------
if php -v 2>/dev/null | grep -q "8.3"; then
    info "PHP 8.3 already installed."
else
    sudo add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1
    sudo apt-get update -qq
    sudo apt-get install -y -qq \
        php8.3 php8.3-cli php8.3-fpm php8.3-common \
        php8.3-mysql php8.3-sqlite3 php8.3-mbstring \
        php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath \
        php8.3-tokenizer php8.3-redis php8.3-gd > /dev/null 2>&1
    info "PHP 8.3 installed."
fi
php -v | head -1

# ---------------------------------------------------------------------------
header "3/8 — Composer"
# ---------------------------------------------------------------------------
if command -v composer &> /dev/null; then
    info "Composer already installed."
else
    curl -sS https://getcomposer.org/installer | php -- --quiet
    sudo mv composer.phar /usr/local/bin/composer
    info "Composer installed."
fi
composer --version

# ---------------------------------------------------------------------------
header "4/8 — Python 3.11+"
# ---------------------------------------------------------------------------
PYTHON_CMD=""
for cmd in python3.13 python3.12 python3.11 python3; do
    if command -v "$cmd" &> /dev/null; then
        PY_VERSION=$($cmd --version 2>&1 | grep -oP '\d+\.\d+' | head -1)
        PY_MAJOR=$(echo "$PY_VERSION" | cut -d. -f1)
        PY_MINOR=$(echo "$PY_VERSION" | cut -d. -f2)
        if [ "$PY_MAJOR" -ge 3 ] && [ "$PY_MINOR" -ge 11 ]; then
            PYTHON_CMD="$cmd"
            break
        fi
    fi
done

if [ -z "$PYTHON_CMD" ]; then
    sudo add-apt-repository -y ppa:deadsnakes/ppa > /dev/null 2>&1
    sudo apt-get update -qq
    sudo apt-get install -y -qq python3.11 python3.11-venv python3.11-dev python3-pip > /dev/null 2>&1
    PYTHON_CMD="python3.11"
    info "Python 3.11 installed."
else
    info "Python $($PYTHON_CMD --version) found."
fi
$PYTHON_CMD --version

# Also ensure venv module is available
sudo apt-get install -y -qq python3-venv > /dev/null 2>&1 || true

# ---------------------------------------------------------------------------
header "5/8 — Redis"
# ---------------------------------------------------------------------------
if command -v redis-server &> /dev/null; then
    info "Redis already installed."
else
    sudo apt-get install -y -qq redis-server > /dev/null 2>&1
    info "Redis installed."
fi
# Ensure Redis is running
sudo systemctl enable redis-server > /dev/null 2>&1 || true
sudo systemctl start redis-server > /dev/null 2>&1 || true
redis-cli ping 2>/dev/null && info "Redis is running." || warn "Redis may not be running. Start it with: sudo systemctl start redis-server"

# ---------------------------------------------------------------------------
header "6/8 — Claude CLI verification"
# ---------------------------------------------------------------------------
if command -v claude &> /dev/null; then
    info "Claude CLI found: $(claude --version 2>/dev/null || echo 'version unknown')"
    info "Testing Claude CLI..."
    if claude -p "respond with just the word 'ok'" --output-format json > /dev/null 2>&1; then
        info "Claude CLI is authenticated and working."
    else
        warn "Claude CLI found but may not be authenticated."
        warn "Run 'claude' interactively first to complete authentication."
    fi
else
    warn "Claude CLI not found in PATH."
    warn "Install it from https://docs.anthropic.com/en/docs/claude-code"
    warn "The platform will still work but AI analysis won't run."
fi

# ---------------------------------------------------------------------------
header "7/8 — Laravel setup"
# ---------------------------------------------------------------------------
cd "$PROJECT_DIR/laravel"

# Environment file
if [ ! -f .env ]; then
    cp "$PROJECT_DIR/.env.example" .env
    info "Created .env from .env.example"
fi

# Composer dependencies
info "Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --quiet 2>/dev/null || composer install --no-interaction --prefer-dist

# Application key
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\s*$" .env; then
    php artisan key:generate --quiet
    info "Application key generated."
fi

# SQLite database
if grep -q "DB_CONNECTION=sqlite" .env; then
    SQLITE_PATH="$PROJECT_DIR/laravel/database/database.sqlite"
    if [ ! -f "$SQLITE_PATH" ]; then
        touch "$SQLITE_PATH"
        info "SQLite database created."
    fi
fi

# Run migrations and seed
info "Running database migrations..."
php artisan migrate --force --quiet

info "Seeding demo data..."
php artisan db:seed --force --quiet

info "Laravel setup complete."
cd "$PROJECT_DIR"

# ---------------------------------------------------------------------------
header "8/8 — Python agents setup"
# ---------------------------------------------------------------------------
cd "$PROJECT_DIR/agents"

# Create virtual environment
if [ ! -d "venv" ]; then
    $PYTHON_CMD -m venv venv
    info "Python virtual environment created."
fi

source venv/bin/activate

# Install dependencies
info "Installing Python dependencies..."
pip install --upgrade pip -q
pip install -r requirements.txt -q 2>/dev/null || pip install -r requirements.txt

deactivate
cd "$PROJECT_DIR"

# ---------------------------------------------------------------------------
header "Setup Complete!"
# ---------------------------------------------------------------------------
echo ""
echo -e "${GREEN}${BOLD}AI Trading Platform is ready!${NC}"
echo ""
echo "  To start the platform:"
echo "    cd $PROJECT_DIR"
echo "    ./start.sh"
echo ""
echo "  Or start components individually:"
echo "    # Terminal 1: Laravel"
echo "    cd $PROJECT_DIR/laravel && php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "    # Terminal 2: Queue worker"
echo "    cd $PROJECT_DIR/laravel && php artisan queue:work --tries=3 --timeout=120"
echo ""
echo "    # Terminal 3: Python agents"
echo "    cd $PROJECT_DIR/agents && source venv/bin/activate && python main.py"
echo ""
echo "  Web UI:    http://localhost:8000"
echo "  Login:     demo@example.com / password"
echo ""
echo "  Optional: Install MySQL for production:"
echo "    sudo apt-get install mysql-server"
echo "    Update DB_CONNECTION=mysql in .env"
echo ""
