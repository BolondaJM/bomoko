# PowerShell script to add XAMPP PHP to system PATH
# Run this script as Administrator

Write-Host "========================================" -ForegroundColor Green
Write-Host "DRC Embassy Management System" -ForegroundColor Green
Write-Host "PHP Path Setup Script" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")
if (-not $isAdmin) {
    Write-Host "ERROR: This script must be run as Administrator!" -ForegroundColor Red
    Write-Host "Right-click on PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check if XAMPP is installed
$xamppPath = "C:\xampp"
$phpPath = "C:\xampp\php"
$apachePath = "C:\xampp\apache\bin"

if (-not (Test-Path $xamppPath)) {
    Write-Host "ERROR: XAMPP not found at $xamppPath" -ForegroundColor Red
    Write-Host "Please install XAMPP first." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

if (-not (Test-Path $phpPath)) {
    Write-Host "ERROR: PHP not found at $phpPath" -ForegroundColor Red
    Write-Host "Please ensure XAMPP is properly installed." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "✓ XAMPP found at: $xamppPath" -ForegroundColor Green
Write-Host "✓ PHP found at: $phpPath" -ForegroundColor Green

# Get current PATH
$currentPath = [Environment]::GetEnvironmentVariable("PATH", "Machine")
Write-Host ""
Write-Host "Current system PATH:" -ForegroundColor Cyan
Write-Host $currentPath -ForegroundColor Gray

# Check if XAMPP paths are already in PATH
$xamppInPath = $currentPath -like "*xampp*"
if ($xamppInPath) {
    Write-Host ""
    Write-Host "✓ XAMPP paths already found in system PATH" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "⚠ XAMPP paths not found in system PATH" -ForegroundColor Yellow
    
    # Add XAMPP paths to system PATH
    Write-Host "Adding XAMPP paths to system PATH..." -ForegroundColor Cyan
    
    $newPath = $currentPath
    if ($newPath -notlike "*$phpPath*") {
        $newPath = "$phpPath;$newPath"
        Write-Host "Added: $phpPath" -ForegroundColor Green
    }
    
    if ($newPath -notlike "*$apachePath*") {
        $newPath = "$apachePath;$newPath"
        Write-Host "Added: $apachePath" -ForegroundColor Green
    }
    
    # Update system PATH
    try {
        [Environment]::SetEnvironmentVariable("PATH", $newPath, "Machine")
        Write-Host ""
        Write-Host "✓ Successfully updated system PATH" -ForegroundColor Green
        Write-Host "Note: You may need to restart your terminal/command prompt for changes to take effect" -ForegroundColor Yellow
    } catch {
        Write-Host "ERROR: Failed to update system PATH" -ForegroundColor Red
        Write-Host $_.Exception.Message -ForegroundColor Red
    }
}

# Test PHP
Write-Host ""
Write-Host "Testing PHP..." -ForegroundColor Cyan
try {
    $phpVersion = & "$phpPath\php.exe" -v 2>&1 | Select-Object -First 1
    Write-Host "✓ PHP is working: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP test failed" -ForegroundColor Red
}

# Test if php command works (after PATH update)
Write-Host ""
Write-Host "Testing 'php' command (may not work until terminal restart)..." -ForegroundColor Cyan
try {
    $phpCmd = php -v 2>&1 | Select-Object -First 1
    Write-Host "✓ 'php' command works: $phpCmd" -ForegroundColor Green
} catch {
    Write-Host "⚠ 'php' command not working yet (restart terminal)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Next Steps:" -ForegroundColor Cyan
Write-Host "1. Restart your terminal/command prompt" -ForegroundColor White
Write-Host "2. Test: php -v" -ForegroundColor White
Write-Host "3. Open XAMPP Control Panel" -ForegroundColor White
Write-Host "4. Start Apache and MySQL" -ForegroundColor White
Write-Host "5. Open browser: http://localhost/bomoko/" -ForegroundColor White
Write-Host "6. If issues: http://localhost/bomoko/debug_php_path.php" -ForegroundColor White
Write-Host "========================================" -ForegroundColor Green

Read-Host "Press Enter to exit" 