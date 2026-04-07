# AI Trading Platform - Windows Setup Script
# Run in PowerShell (admin recommended)
#
# Usage: .\setup-windows.ps1

$ErrorActionPreference = "Continue"
$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  AI Trading Platform - Windows Setup" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# -------------------------------------------------------------------
# 1. Check prerequisites
# -------------------------------------------------------------------

Write-Host "[1/4] Checking prerequisites..." -ForegroundColor Yellow
Write-Host ""

$allGood = $true

# PHP
$phpVersion = $null
try {
    $phpVersion = (php -v 2>&1 | Select-Object -First 1)
    Write-Host "  [OK] PHP found: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "  [MISSING] PHP not found. Install PHP 8.3 from https://windows.php.net/download/" -ForegroundColor Red
    Write-Host "           Extract to C:\php and add to System PATH." -ForegroundColor Red
    $allGood = $false
}

# Composer
try {
    $composerVersion = (composer -V 2>&1 | Select-Object -First 1)
    Write-Host "  [OK] Composer found: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "  [MISSING] Composer not found. Install from https://getcomposer.org/download/" -ForegroundColor Red
    $allGood = $false
}

# Python
$pythonCmd = $null
try {
    $pyVer = (python --version 2>&1)
    Write-Host "  [OK] Python found: $pyVer" -ForegroundColor Green
    $pythonCmd = "python"
} catch {
    try {
        $pyVer = (python3 --version 2>&1)
        Write-Host "  [OK] Python found: $pyVer" -ForegroundColor Green
        $pythonCmd = "python3"
    } catch {
        Write-Host "  [MISSING] Python not found. Install from https://www.python.org/downloads/" -ForegroundColor Red
        Write-Host "           Check 'Add Python to PATH' during installation." -ForegroundColor Red
        $allGood = $false
    }
}

# Claude CLI
try {
    $claudeVersion = (claude --version 2>&1 | Select-Object -First 1)
    Write-Host "  [OK] Claude CLI found: $claudeVersion" -ForegroundColor Green
} catch {
    Write-Host "  [WARN] Claude CLI not found. AI features will not work." -ForegroundColor DarkYellow
}

# Redis (optional check)
$redisAvailable = $false
try {
    $redisPing = (redis-cli ping 2>&1)
    if ($redisPing -eq "PONG") {
        Write-Host "  [OK] Redis is running" -ForegroundColor Green
        $redisAvailable = $true
    } else {
        Write-Host "  [SKIP] Redis not responding. Platform will work without it." -ForegroundColor DarkYellow
    }
} catch {
    Write-Host "  [SKIP] Redis not found. Platform will work without it (no real-time pub/sub)." -ForegroundColor DarkYellow
}

Write-Host ""
if (-not $allGood) {
    Write-Host "Some prerequisites are missing. Install them and re-run this script." -ForegroundColor Red
    Write-Host "Continuing anyway (some steps may fail)..." -ForegroundColor DarkYellow
    Write-Host ""
}

# -------------------------------------------------------------------
# 2. Laravel setup
# -------------------------------------------------------------------

Write-Host "[2/4] Setting up Laravel..." -ForegroundColor Yellow

$laravelDir = Join-Path $ProjectRoot "laravel"

if (-not (Test-Path $laravelDir)) {
    Write-Host "  [ERROR] Laravel directory not found at $laravelDir" -ForegroundColor Red
    exit 1
}

# Copy .env file
$envSource = Join-Path $ProjectRoot ".env.example"
$envDest = Join-Path $laravelDir ".env"
if (-not (Test-Path $envDest)) {
    if (Test-Path $envSource) {
        Copy-Item $envSource $envDest
        Write-Host "  [OK] Copied .env.example to laravel/.env" -ForegroundColor Green
    } else {
        Write-Host "  [WARN] .env.example not found at project root" -ForegroundColor DarkYellow
    }
} else {
    Write-Host "  [OK] .env file already exists" -ForegroundColor Green
}

# Composer install
Write-Host "  Installing Composer dependencies..." -ForegroundColor Gray
Push-Location $laravelDir
try {
    composer install --no-interaction 2>&1 | Out-Null
    Write-Host "  [OK] Composer dependencies installed" -ForegroundColor Green
} catch {
    Write-Host "  [ERROR] Composer install failed: $_" -ForegroundColor Red
}

# Generate app key
try {
    php artisan key:generate --no-interaction 2>&1 | Out-Null
    Write-Host "  [OK] Application key generated" -ForegroundColor Green
} catch {
    Write-Host "  [ERROR] Key generation failed: $_" -ForegroundColor Red
}

# Create SQLite database
$dbDir = Join-Path $laravelDir "database"
$dbFile = Join-Path $dbDir "database.sqlite"
if (-not (Test-Path $dbFile)) {
    New-Item -Path $dbFile -ItemType File -Force | Out-Null
    Write-Host "  [OK] Created SQLite database file" -ForegroundColor Green
} else {
    Write-Host "  [OK] SQLite database file already exists" -ForegroundColor Green
}

# Run migrations
try {
    php artisan migrate --no-interaction --force 2>&1 | Out-Null
    Write-Host "  [OK] Database migrations completed" -ForegroundColor Green
} catch {
    Write-Host "  [ERROR] Migrations failed: $_" -ForegroundColor Red
}

# Seed database
try {
    php artisan db:seed --no-interaction --force 2>&1 | Out-Null
    Write-Host "  [OK] Database seeded" -ForegroundColor Green
} catch {
    Write-Host "  [ERROR] Database seeding failed: $_" -ForegroundColor Red
}

Pop-Location
Write-Host ""

# -------------------------------------------------------------------
# 3. Python agents setup
# -------------------------------------------------------------------

Write-Host "[3/4] Setting up Python agents..." -ForegroundColor Yellow

$agentsDir = Join-Path $ProjectRoot "agents"

if (-not (Test-Path $agentsDir)) {
    Write-Host "  [ERROR] Agents directory not found at $agentsDir" -ForegroundColor Red
} else {
    Push-Location $agentsDir

    if ($pythonCmd) {
        # Create virtual environment
        $venvDir = Join-Path $agentsDir "venv"
        if (-not (Test-Path $venvDir)) {
            Write-Host "  Creating virtual environment..." -ForegroundColor Gray
            & $pythonCmd -m venv venv 2>&1 | Out-Null
            Write-Host "  [OK] Virtual environment created" -ForegroundColor Green
        } else {
            Write-Host "  [OK] Virtual environment already exists" -ForegroundColor Green
        }

        # Install requirements
        $reqFile = Join-Path $agentsDir "requirements.txt"
        if (Test-Path $reqFile) {
            Write-Host "  Installing Python dependencies..." -ForegroundColor Gray
            $pipPath = Join-Path $venvDir "Scripts\pip.exe"
            if (Test-Path $pipPath) {
                & $pipPath install -r requirements.txt 2>&1 | Out-Null
                Write-Host "  [OK] Python dependencies installed" -ForegroundColor Green
            } else {
                Write-Host "  [ERROR] pip not found in venv" -ForegroundColor Red
            }
        } else {
            Write-Host "  [WARN] requirements.txt not found" -ForegroundColor DarkYellow
        }
    } else {
        Write-Host "  [SKIP] Python not available, skipping agents setup" -ForegroundColor DarkYellow
    }

    Pop-Location
}

Write-Host ""

# -------------------------------------------------------------------
# 4. Done
# -------------------------------------------------------------------

Write-Host "[4/4] Setup complete!" -ForegroundColor Yellow
Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Setup finished successfully!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "To start the platform, run:" -ForegroundColor White
Write-Host ""
Write-Host "  .\start-windows.ps1" -ForegroundColor Cyan
Write-Host ""
Write-Host "Or start each component manually in separate terminals:" -ForegroundColor White
Write-Host ""
Write-Host "  Terminal 1 (Web):   cd laravel && php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Gray
Write-Host "  Terminal 2 (Queue): cd laravel && php artisan queue:work --tries=3 --timeout=120" -ForegroundColor Gray
Write-Host "  Terminal 3 (Agent): cd agents && .\venv\Scripts\activate && python run.py" -ForegroundColor Gray
Write-Host ""
Write-Host "Access: http://localhost:8000" -ForegroundColor White
Write-Host "Login:  demo@example.com / password" -ForegroundColor White
Write-Host ""
