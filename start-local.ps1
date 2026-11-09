<#
.SYNOPSIS
    Starts the trading platform on this Windows machine, without Docker.

.DESCRIPTION
    Each run checks what is missing and sets it up, then starts the processes:

    1. PostgreSQL 17, portable, in %LOCALAPPDATA%\TradingPlatform (no service, no administrator rights).
       The databases trading, trading_test and trading_e2e are created on the first run.
    2. .env, the app key, Composer and npm dependencies, the front-end build, pending migrations.
    3. The web process, the simulation worker and the notifications worker, in this window.
       Optional: the market data streams with the scheduler (-Stream), the scheduler alone (-Scheduler) and
       paper or testnet engines (-Engine).

    Ctrl+C stops the processes and PostgreSQL. If the window was closed instead, run it with -Stop.

    Redis is not used locally: the cache and the default queue are the database (docs/runbook.md, section 2).
    PHP runs with memory_limit=2G for the processes started here; for a command typed by hand, use
    php -d memory_limit=2G artisan ...

.EXAMPLE
    .\start-local.ps1
.EXAMPLE
    .\start-local.ps1 -Stream -Engine paper
.EXAMPLE
    .\start-local.ps1 -Stop
#>
[CmdletBinding()]
param(
    # Port of the web process.
    [int] $Port = 8000,
    # Also run market:stream spot and market:stream perp (and the scheduler).
    [switch] $Stream,
    # Also run the scheduler (daily report, health check, ladder review) without the streams.
    [switch] $Scheduler,
    # Also run engine:run for these accounts (paper or testnet), such as -Engine paper.
    [string[]] $Engine = @(),
    # Rebuild the front-end even when the build looks current.
    [switch] $Build,
    # Do not open the browser.
    [switch] $NoBrowser,
    # Leave PostgreSQL running after the processes stop.
    [switch] $KeepDatabase,
    # Stop PostgreSQL and leftover processes of this project, then exit.
    [switch] $Stop
)

$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
Set-Location -LiteralPath $PSScriptRoot

$PgVersion = '17.11'
$Home_ = Join-Path $env:LOCALAPPDATA 'TradingPlatform'
$PgBin = Join-Path $Home_ 'pgsql\bin'
$PgData = Join-Path $Home_ 'pgdata'
$PgLog = Join-Path $Home_ 'postgres.log'
$PidFile = Join-Path $Home_ 'processes.pid'

# PHP's usual 128 MB is too little for backtests over months of 1-minute candles. An extra ini file, found
# through PHP_INI_SCAN_DIR, raises it for every PHP process started from here, the children of the scheduler and
# the workers included, without touching the PHP installation that other projects share.
$PhpIniDir = Join-Path $Home_ 'php'
New-Item -ItemType Directory -Force -Path $PhpIniDir | Out-Null
[IO.File]::WriteAllText((Join-Path $PhpIniDir 'trading-platform.ini'), "memory_limit=2G`n")
$env:PHP_INI_SCAN_DIR = $PhpIniDir

function Write-Step([string] $Text) { Write-Host "==> $Text" -ForegroundColor Cyan }
function Write-Note([string] $Text) { Write-Host "    $Text" -ForegroundColor DarkGray }

# Runs a native command and stops the script when it fails.
function Invoke-Native([string] $File, [string[]] $Arguments) {
    # Native tools write notices to stderr; Windows PowerShell must not turn those into errors.
    $ErrorActionPreference = 'Continue'
    & $File @Arguments
    if ($LASTEXITCODE -ne 0) { throw "$File $($Arguments -join ' ') failed with exit code $LASTEXITCODE." }
}

# Reads KEY=value pairs from .env (quotes removed, comments ignored).
function Read-DotEnv {
    $values = @{}
    foreach ($line in Get-Content -LiteralPath '.env') {
        if ($line -match '^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$') {
            $values[$Matches[1]] = $Matches[2].Trim().Trim('"').Trim("'")
        }
    }
    return $values
}

# Sets KEY=value in .env, replacing the line or appending it.
function Set-DotEnv([string] $Key, [string] $Value) {
    $lines = @(Get-Content -LiteralPath '.env')
    $found = $false
    for ($i = 0; $i -lt $lines.Count; $i++) {
        if ($lines[$i] -match "^\s*$Key\s*=") { $lines[$i] = "$Key=$Value"; $found = $true }
    }
    if (-not $found) { $lines += "$Key=$Value" }
    [IO.File]::WriteAllLines((Join-Path $PSScriptRoot '.env'), $lines)
}

function Test-PostgresRunning {
    if (-not (Test-Path -LiteralPath (Join-Path $PgData 'PG_VERSION'))) { return $false }
    $ErrorActionPreference = 'Continue'
    & (Join-Path $PgBin 'pg_ctl.exe') status -D $PgData *> $null
    return ($LASTEXITCODE -eq 0)
}

function Stop-Postgres {
    if (Test-PostgresRunning) {
        Write-Step 'Stopping PostgreSQL'
        $ErrorActionPreference = 'Continue'
        & (Join-Path $PgBin 'pg_ctl.exe') stop -D $PgData -m fast -w | Out-Null
    }
}

# Stops the process tree this script started (concurrently and everything under it), found by its PID file.
function Stop-ProcessTree {
    if (-not (Test-Path -LiteralPath $PidFile)) { return }
    $treePid = [int] (Get-Content -LiteralPath $PidFile -TotalCount 1)
    $root = Get-CimInstance Win32_Process -Filter "ProcessId = $treePid" -ErrorAction SilentlyContinue
    if ($root -and $root.Name -eq 'node.exe') {
        # Give the processes a moment to finish after Ctrl+C, then end what is left.
        Start-Sleep -Seconds 2
        if (Get-Process -Id $treePid -ErrorAction SilentlyContinue) {
            Write-Step 'Stopping the processes'
            $ErrorActionPreference = 'Continue'
            & taskkill.exe /T /F /PID $treePid *> $null
        }
    }
    Remove-Item -LiteralPath $PidFile -Force -ErrorAction SilentlyContinue
}

if ($Stop) {
    Stop-ProcessTree
    Stop-Postgres
    Write-Step 'Stopped'
    return
}

# --- Tools -----------------------------------------------------------------------------------------------------

Write-Step 'Checking the tools'
foreach ($tool in 'php', 'composer', 'node', 'npm') {
    if (-not (Get-Command $tool -ErrorAction SilentlyContinue)) {
        throw "$tool was not found in PATH. PHP and Composer come with Laravel Herd; Node from nodejs.org."
    }
}
$extensions = (& php -m) -join ' '
foreach ($ext in 'bcmath', 'intl', 'pdo_pgsql') {
    if ($extensions -notmatch "\b$ext\b") { throw "The PHP extension $ext is not enabled (php --ini shows the php.ini)." }
}

# --- .env ------------------------------------------------------------------------------------------------------

if (-not (Test-Path -LiteralPath '.env')) {
    Write-Step 'Creating .env from .env.example'
    Copy-Item -LiteralPath '.env.example' -Destination '.env'
    # No Redis on this machine: the cache and the default queue use the database (runbook, section 2).
    Set-DotEnv 'CACHE_STORE' 'database'
    Set-DotEnv 'QUEUE_CONNECTION' 'database'
}
Set-DotEnv 'APP_URL' "http://127.0.0.1:$Port"
$envValues = Read-DotEnv
if ($envValues['DB_CONNECTION'] -ne 'pgsql') { throw '.env must have DB_CONNECTION=pgsql (D-13).' }
$DbHost = $envValues['DB_HOST']
$DbPort = $envValues['DB_PORT']
$DbName = $envValues['DB_DATABASE']
$DbUser = $envValues['DB_USERNAME']
$DbPassword = $envValues['DB_PASSWORD']
if ($DbHost -notin '127.0.0.1', 'localhost') { throw "DB_HOST is $DbHost; this script only runs a local database." }

# --- PostgreSQL --------------------------------------------------------------------------------------------------

if (-not (Test-Path -LiteralPath (Join-Path $PgBin 'pg_ctl.exe'))) {
    Write-Step "Downloading PostgreSQL $PgVersion (about 340 MB, once)"
    New-Item -ItemType Directory -Force -Path $Home_ | Out-Null
    $zip = Join-Path $Home_ "postgresql-$PgVersion-1-windows-x64-binaries.zip"
    if (-not (Test-Path -LiteralPath $zip)) {
        Invoke-WebRequest -UseBasicParsing -OutFile $zip `
            -Uri "https://get.enterprisedb.com/postgresql/postgresql-$PgVersion-1-windows-x64-binaries.zip"
    }
    Write-Step 'Extracting PostgreSQL'
    Push-Location -LiteralPath $Home_
    try {
        Invoke-Native 'tar.exe' @('-xf', $zip, '--exclude', 'pgsql/pgAdmin 4', '--exclude', 'pgsql/StackBuilder',
            '--exclude', 'pgsql/symbols', '--exclude', 'pgsql/doc')
    } finally { Pop-Location }
}

if (-not (Test-Path -LiteralPath (Join-Path $PgData 'PG_VERSION'))) {
    Write-Step "Creating the database cluster in $PgData"
    $pwFile = Join-Path $Home_ 'initdb.pw'
    [IO.File]::WriteAllText($pwFile, $DbPassword)
    try {
        Invoke-Native (Join-Path $PgBin 'initdb.exe') @('-D', $PgData, '-U', $DbUser, "--pwfile=$pwFile",
            '--auth=scram-sha-256', '--encoding=UTF8', '--locale-provider=builtin', '--builtin-locale=C.UTF-8')
    } finally { Remove-Item -LiteralPath $pwFile -Force -ErrorAction SilentlyContinue }
}

$env:PGPASSWORD = $DbPassword
if (Test-PostgresRunning) {
    Write-Step 'PostgreSQL is already running'
} else {
    Write-Step "Starting PostgreSQL on 127.0.0.1:$DbPort"
    Invoke-Native (Join-Path $PgBin 'pg_ctl.exe') @('start', '-D', $PgData, '-l', $PgLog, '-w',
        '-o', "-p $DbPort -c listen_addresses=127.0.0.1")
}

foreach ($db in $DbName, 'trading_test', 'trading_e2e') {
    $exists = & (Join-Path $PgBin 'psql.exe') -h 127.0.0.1 -p $DbPort -U $DbUser -d postgres -tAc "SELECT 1 FROM pg_database WHERE datname = '$db'"
    if ($LASTEXITCODE -ne 0) { throw "Cannot connect to PostgreSQL; see $PgLog." }
    if ("$exists".Trim() -ne '1') {
        Write-Step "Creating the database $db"
        Invoke-Native (Join-Path $PgBin 'createdb.exe') @('-h', '127.0.0.1', '-p', $DbPort, '-U', $DbUser, '-O', $DbUser, $db)
    }
}

# --- Dependencies, key, build, migrations ------------------------------------------------------------------------

function Test-Stale([string] $Output, [string[]] $Inputs) {
    if (-not (Test-Path -LiteralPath $Output)) { return $true }
    $built = (Get-Item -LiteralPath $Output).LastWriteTimeUtc
    foreach ($in in $Inputs) {
        if ((Test-Path -LiteralPath $in) -and (Get-Item -LiteralPath $in).LastWriteTimeUtc -gt $built) { return $true }
    }
    return $false
}

if (Test-Stale 'vendor\composer\installed.json' @('composer.lock')) {
    Write-Step 'Installing the PHP dependencies'
    Invoke-Native 'composer' @('install', '--no-interaction')
}
if (Test-Stale 'tools\pint\vendor\composer\installed.json' @('tools\pint\composer.lock')) {
    Write-Step 'Installing Pint (tools/pint)'
    Invoke-Native 'composer' @('tools:install')
}
if (Test-Stale 'node_modules\.package-lock.json' @('package-lock.json')) {
    Write-Step 'Installing the front-end dependencies'
    Invoke-Native 'npm' @('install', '--ignore-scripts')
}

if (-not (Read-DotEnv)['APP_KEY']) {
    Write-Step 'Generating the app key'
    Invoke-Native 'php' @('artisan', 'key:generate', '--force')
}

$manifest = 'public\build\manifest.json'
$needsBuild = $Build -or -not (Test-Path -LiteralPath $manifest)
if (-not $needsBuild) {
    $builtAt = (Get-Item -LiteralPath $manifest).LastWriteTimeUtc
    $newer = Get-ChildItem -LiteralPath 'resources' -Recurse -File |
        Where-Object { $_.Extension -in '.css', '.js', '.mjs', '.ts' -and $_.LastWriteTimeUtc -gt $builtAt } |
        Select-Object -First 1
    $needsBuild = $null -ne $newer -or (Test-Stale $manifest @('vite.config.js', 'package-lock.json'))
}
if ($needsBuild) {
    Write-Step 'Building the front-end'
    Invoke-Native 'npm' @('run', 'build')
}

Write-Step 'Running pending migrations'
Invoke-Native 'php' @('artisan', 'migrate', '--force')

$users = & (Join-Path $PgBin 'psql.exe') -h 127.0.0.1 -p $DbPort -U $DbUser -d $DbName -tAc 'SELECT count(*) FROM users'
if ("$users".Trim() -eq '0') {
    Write-Host ''
    Write-Host '    No operator account yet. In another window, create it with:' -ForegroundColor Yellow
    Write-Host '        php artisan user:create you@example.com' -ForegroundColor Yellow
    Write-Host ''
}

# --- Processes ---------------------------------------------------------------------------------------------------

$names = @('web', 'simulation', 'notify')
$commands = @(
    "php artisan serve --host=127.0.0.1 --port=$Port --no-reload",
    'php artisan queue:work simulation --queue=simulation,market --timeout=21600 --tries=1',
    'php artisan queue:work database --queue=notifications --sleep=1'
)
if ($Stream) {
    $names += 'stream-spot', 'stream-perp'
    $commands += 'php artisan market:stream spot', 'php artisan market:stream perp'
}
# The scheduler runs platform:health every minute (D-78); without the streams every check fails and is logged
# with a stack trace, so it starts with the streams, or on request.
if ($Stream -or $Scheduler) {
    $names += 'scheduler'
    $commands += 'php artisan schedule:work'
}
foreach ($account in $Engine) {
    if ($account -notmatch '^[A-Za-z0-9_-]+$') { throw "Invalid account name: $account" }
    $names += "engine-$account"
    $commands += "php artisan engine:run $account"
}

$url = "http://127.0.0.1:$Port"
Write-Step "Starting: $($names -join ', ')"
Write-Note "Site: $url   (Ctrl+C stops everything)"
Write-Note "Logs: storage\logs\laravel.log, PostgreSQL: $PgLog"

$browserJob = $null
if (-not $NoBrowser) {
    # Opens the browser once the web process answers, without blocking this window.
    $browserJob = Start-Job -ArgumentList $url -ScriptBlock {
        param($u)
        for ($i = 0; $i -lt 60; $i++) {
            try { Invoke-WebRequest -UseBasicParsing -Uri "$u/up" -TimeoutSec 2 | Out-Null; Start-Process $u; return } catch { Start-Sleep -Seconds 1 }
        }
    }
}

# concurrently runs every process in this window with a coloured prefix and restarts one that dies (the
# supervisor of runbook section 2). It is called through node, not the .cmd shim, so Ctrl+C does not ask
# "Terminate batch job?".
$arguments = @('node_modules\concurrently\dist\bin\index.js', '--names', ($names -join ','),
    '--prefix-colors', 'cyan,magenta,yellow,green,blue,white,red,red',
    '--restart-tries', '-1', '--restart-after', '5000') + ($commands | ForEach-Object { '"' + $_ + '"' })
$process = Start-Process -FilePath 'node' -ArgumentList $arguments -NoNewWindow -PassThru
Set-Content -LiteralPath $PidFile -Value $process.Id
try {
    $process.WaitForExit()
} finally {
    if ($browserJob) { Remove-Job -Job $browserJob -Force -ErrorAction SilentlyContinue }
    Stop-ProcessTree
    if (-not $KeepDatabase) { Stop-Postgres }
}
