# AI Trading Platform - Windows Start Script
# Starts all platform components as background jobs
# Press Ctrl+C to stop everything
#
# Usage: .\start-windows.ps1

$ErrorActionPreference = "Continue"
$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
$LaravelDir = Join-Path $ProjectRoot "laravel"
$AgentsDir = Join-Path $ProjectRoot "agents"

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  AI Trading Platform - Starting..." -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Track all jobs for cleanup
$jobs = @()

# -------------------------------------------------------------------
# Start Laravel web server
# -------------------------------------------------------------------

Write-Host "Starting Laravel web server..." -ForegroundColor Yellow
$webJob = Start-Job -Name "Laravel-Web" -ScriptBlock {
    param($dir)
    Set-Location $dir
    php artisan serve --host=0.0.0.0 --port=8000 2>&1
} -ArgumentList $LaravelDir
$jobs += $webJob
Write-Host "  [OK] Laravel web server started (Job $($webJob.Id))" -ForegroundColor Green

# -------------------------------------------------------------------
# Start Laravel queue worker
# -------------------------------------------------------------------

Write-Host "Starting queue worker..." -ForegroundColor Yellow
$queueJob = Start-Job -Name "Laravel-Queue" -ScriptBlock {
    param($dir)
    Set-Location $dir
    php artisan queue:work --tries=3 --timeout=120 2>&1
} -ArgumentList $LaravelDir
$jobs += $queueJob
Write-Host "  [OK] Queue worker started (Job $($queueJob.Id))" -ForegroundColor Green

# -------------------------------------------------------------------
# Start Python agents
# -------------------------------------------------------------------

Write-Host "Starting Python agents..." -ForegroundColor Yellow
$venvPython = Join-Path $AgentsDir "venv\Scripts\python.exe"
$mainPy = Join-Path $AgentsDir "run.py"

if (Test-Path $venvPython) {
    $agentJob = Start-Job -Name "Python-Agents" -ScriptBlock {
        param($pythonExe, $script, $dir)
        Set-Location $dir
        & $pythonExe $script 2>&1
    } -ArgumentList $venvPython, $mainPy, $AgentsDir
    $jobs += $agentJob
    Write-Host "  [OK] Python agents started (Job $($agentJob.Id))" -ForegroundColor Green
} else {
    Write-Host "  [SKIP] Python venv not found. Run setup-windows.ps1 first." -ForegroundColor DarkYellow
    Write-Host "         Agents dir: $AgentsDir" -ForegroundColor DarkYellow
}

# -------------------------------------------------------------------
# Status display
# -------------------------------------------------------------------

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  All components started!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Web UI:       http://localhost:8000" -ForegroundColor White
Write-Host "  Login:        demo@example.com / password" -ForegroundColor White
Write-Host ""
Write-Host "  Running jobs:" -ForegroundColor White
foreach ($job in $jobs) {
    Write-Host "    - $($job.Name) (Job $($job.Id))" -ForegroundColor Gray
}
Write-Host ""
Write-Host "  Press Ctrl+C to stop all components." -ForegroundColor Yellow
Write-Host ""

# -------------------------------------------------------------------
# Wait and cleanup
# -------------------------------------------------------------------

# Register cleanup handler
$cleanup = {
    Write-Host ""
    Write-Host "Stopping all components..." -ForegroundColor Yellow
    Get-Job -Name "Laravel-Web", "Laravel-Queue", "Python-Agents" -ErrorAction SilentlyContinue | ForEach-Object {
        Write-Host "  Stopping $($_.Name)..." -ForegroundColor Gray
        Stop-Job $_ -ErrorAction SilentlyContinue
        Remove-Job $_ -Force -ErrorAction SilentlyContinue
    }
    Write-Host "All components stopped." -ForegroundColor Green
    Write-Host ""
}

try {
    # Keep running and show job output
    while ($true) {
        # Check for failed jobs
        foreach ($job in $jobs) {
            if ($job.State -eq "Failed") {
                Write-Host "  [WARN] $($job.Name) has stopped unexpectedly." -ForegroundColor Red
                Receive-Job $job -ErrorAction SilentlyContinue | Write-Host -ForegroundColor DarkRed
            }
        }

        # Print any output from jobs
        foreach ($job in $jobs) {
            $output = Receive-Job $job -ErrorAction SilentlyContinue
            if ($output) {
                foreach ($line in $output) {
                    Write-Host "  [$($job.Name)] $line" -ForegroundColor DarkGray
                }
            }
        }

        Start-Sleep -Seconds 2
    }
} finally {
    # Cleanup on Ctrl+C or exit
    & $cleanup
}
