@echo off
echo ========================================
echo DRC Embassy Management System
echo PHP Path Setup Tool
echo ========================================
echo.

echo Checking XAMPP installation...
if exist "C:\xampp\php\php.exe" (
    echo ✓ XAMPP PHP found at C:\xampp\php\php.exe
) else (
    echo ✗ XAMPP PHP not found at C:\xampp\php\php.exe
    echo Please ensure XAMPP is installed correctly.
    pause
    exit /b 1
)

echo.
echo Current PATH environment variable:
echo %PATH%
echo.

echo Checking if XAMPP is already in PATH...
echo %PATH% | findstr /i "xampp" >nul
if %errorlevel% equ 0 (
    echo ✓ XAMPP already found in PATH
) else (
    echo ✗ XAMPP not found in PATH
    echo.
    echo To add XAMPP to PATH manually:
    echo 1. Right-click on 'This PC' or 'My Computer'
    echo 2. Select 'Properties'
    echo 3. Click 'Advanced system settings'
    echo 4. Click 'Environment Variables'
    echo 5. Under 'System variables', find 'Path' and click 'Edit'
    echo 6. Click 'New' and add: C:\xampp\php
    echo 7. Click 'New' and add: C:\xampp\apache\bin
    echo 8. Click 'OK' on all dialogs
    echo 9. Restart your command prompt/terminal
    echo.
)

echo.
echo Testing PHP from command line...
php -v
if %errorlevel% equ 0 (
    echo ✓ PHP is working from command line
) else (
    echo ✗ PHP not working from command line
    echo Please add C:\xampp\php to your PATH
)

echo.
echo ========================================
echo Next Steps:
echo 1. Open XAMPP Control Panel
echo 2. Start Apache and MySQL
echo 3. Open browser and go to: http://localhost/bomoko/
echo 4. If issues persist, visit: http://localhost/bomoko/debug_php_path.php
echo ========================================
echo.
pause 