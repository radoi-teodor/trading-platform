# Windows Setup Guide

Complete installation and setup instructions for running the AI Trading Platform on Windows 10/11.

---

## Prerequisites

### PHP 8.3

1. Download the **VS16 x64 Thread Safe** ZIP from [windows.php.net/download](https://windows.php.net/download/).
2. Extract to `C:\php`.
3. Add `C:\php` to your **System PATH**:
   - Open **Settings > System > About > Advanced system settings > Environment Variables**.
   - Under **System variables**, select `Path`, click **Edit**, and add `C:\php`.
4. Copy `php.ini-development` to `php.ini` inside `C:\php`.
5. Open `php.ini` and enable the following extensions (remove the leading `;`):
   ```ini
   extension=curl
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=pdo_sqlite
   extension=sqlite3
   extension=zip
   ```
6. For Redis support, also enable:
   ```ini
   extension=redis
   ```
   You may need to download `php_redis.dll` separately from [PECL](https://pecl.php.net/package/redis) or [GitHub releases](https://github.com/phpredis/phpredis/releases) and place it in `C:\php\ext\`.
7. Verify: open a **new** terminal and run `php -v`. You should see PHP 8.3.x.

### Composer

1. Download and run the installer from [getcomposer.org/download](https://getcomposer.org/download/).
2. The installer will detect your PHP installation and add `composer` to PATH.
3. Verify: `composer -V`.

### Python 3.11+

1. Download from [python.org/downloads](https://www.python.org/downloads/).
2. During installation, **check "Add Python to PATH"**.
3. Verify: `python --version`.

### Redis (optional)

Redis is used for caching and pub/sub communication between Laravel and Python agents. Choose one option:

**Option A: Memurai (recommended for Windows)**
- Download the free developer edition from [memurai.com](https://www.memurai.com/).
- Install and it runs as a Windows service automatically.
- Default port 6379, no configuration needed.

**Option B: WSL2 with Redis**
```powershell
# Install WSL if not already installed
wsl --install

# After reboot, open WSL terminal
sudo apt update
sudo apt install redis-server -y
sudo service redis-server start
```

**Option C: Docker Desktop**
```powershell
docker run -d --name redis -p 6379:6379 redis:7-alpine
```

**Option D: Skip Redis entirely**
- The platform works without Redis for the web UI (Laravel + SQLite).
- Python agents will not be able to communicate with Laravel in real-time via pub/sub.
- They still share the SQLite database, so data is not lost.
- In `.env`, set `CACHE_STORE=file` and `QUEUE_CONNECTION=database`.

### Claude CLI

The Claude CLI binary should already be installed and authenticated. Verify with:

```powershell
claude --version
```

### Git (optional)

Download from [git-scm.com](https://git-scm.com/download/win) if you want version control.

---

## Step-by-Step Setup

### 1. Open PowerShell or Windows Terminal

Right-click the Start menu and select **Terminal (Admin)** or **Windows PowerShell (Admin)**.

Navigate to the project directory:

```powershell
cd C:\Proj\trading-platform
```

### 2. Verify prerequisites

```powershell
php -v
composer -V
python --version
claude --version
```

All commands should return version information. If any command is not found, install the prerequisite above.

### 3. Laravel setup

```powershell
cd laravel

# Copy environment file
copy ..\.env.example .env

# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Create SQLite database file (if it does not exist)
if (-not (Test-Path database\database.sqlite)) {
    New-Item database\database.sqlite -ItemType File
}

# Run database migrations
php artisan migrate

# Seed the database with demo data
php artisan db:seed
```

### 4. Python agents setup

```powershell
cd ..\agents

# Create virtual environment
python -m venv venv

# Activate it
.\venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt
```

### 5. Running the platform

You need **three separate terminals**. Open three PowerShell/Terminal windows:

**Terminal 1 — Laravel web server:**
```powershell
cd C:\Proj\trading-platform\laravel
php artisan serve --host=0.0.0.0 --port=8000
```

**Terminal 2 — Queue worker:**
```powershell
cd C:\Proj\trading-platform\laravel
php artisan queue:work --tries=3 --timeout=120
```

**Terminal 3 — Python agents:**
```powershell
cd C:\Proj\trading-platform\agents
.\venv\Scripts\activate
python main.py
```

Alternatively, use the provided PowerShell start script:

```powershell
cd C:\Proj\trading-platform
.\start-windows.ps1
```

### 6. Access the platform

Open your browser and navigate to:

```
http://localhost:8000
```

Login with the demo account:

- **Email:** demo@example.com
- **Password:** password

---

## Troubleshooting

### PHP not found in PATH

If `php -v` returns "not recognized", add `C:\php` to your System PATH and open a **new** terminal window. Existing terminals will not pick up PATH changes.

### php_redis extension not loading

If you see an error about the Redis extension:

1. Download the correct `php_redis.dll` for PHP 8.3 TS x64 from [PECL](https://pecl.php.net/package/redis).
2. Copy `php_redis.dll` to `C:\php\ext\`.
3. Add `extension=redis` to `php.ini`.
4. Restart your terminal.

If you do not need Redis, skip this step. Set the following in your `.env`:

```
CACHE_STORE=file
QUEUE_CONNECTION=database
```

### Python venv activation

On Windows, activate the virtual environment with:

```powershell
.\venv\Scripts\activate
```

Do **not** use `source venv/bin/activate` — that is the Linux/macOS syntax.

If you get an execution policy error, run:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### SQLite "database does not exist"

Create the file manually:

```powershell
New-Item C:\Proj\trading-platform\laravel\database\database.sqlite -ItemType File
```

Then run `php artisan migrate` again.

### Long path issues

Windows has a 260-character path limit by default. If you encounter path-related errors:

1. Open **Registry Editor** (`regedit`).
2. Navigate to `HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\FileSystem`.
3. Set `LongPathsEnabled` to `1`.
4. Restart your computer.

Or run in an elevated PowerShell:

```powershell
New-ItemProperty -Path "HKLM:\SYSTEM\CurrentControlSet\Control\FileSystem" -Name "LongPathsEnabled" -Value 1 -PropertyType DWORD -Force
```

### Firewall blocking connections

If `localhost:8000` is not reachable, allow PHP through Windows Firewall:

1. Open **Windows Security > Firewall & network protection > Allow an app through firewall**.
2. Click **Allow another app** and browse to `C:\php\php.exe`.
3. Check both **Private** and **Public** checkboxes.

### Redis not available at runtime

If Redis is not installed or not running, the web UI (Laravel + SQLite) still works fully. Only the Python agents' real-time pub/sub features will be unavailable. The agents still read and write to the shared SQLite database.

### Composer install fails with memory error

Increase PHP memory limit in `php.ini`:

```ini
memory_limit = 512M
```

### Port 8000 already in use

Use a different port:

```powershell
php artisan serve --host=0.0.0.0 --port=8080
```

---

## PowerShell Scripts

Two convenience scripts are provided in the project root:

| Script | Purpose |
|--------|---------|
| `setup-windows.ps1` | Checks prerequisites, installs dependencies, sets up database |
| `start-windows.ps1` | Starts all 3 components as background jobs, stops them on Ctrl+C |

Run them from the project root:

```powershell
cd C:\Proj\trading-platform
.\setup-windows.ps1    # First time only
.\start-windows.ps1    # Every time you want to run the platform
```
