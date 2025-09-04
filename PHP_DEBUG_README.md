# PHP Executable Path Debug Tools

This directory contains comprehensive tools to diagnose and fix PHP executable path issues in XAMPP.

## Quick Start

1. **Run the PowerShell script** (as Administrator):
   ```powershell
   .\add_php_to_path.ps1
   ```

2. **Or run the batch file**:
   ```cmd
   set_php_path.bat
   ```

3. **Check your system**:
   - Visit: `http://localhost/bomoko/debug_php_path.php`
   - Visit: `http://localhost/bomoko/status.php`

## Debug Tools

### 1. `debug_php_path.php`
**Comprehensive PHP path diagnostic tool**
- Checks PHP version and location
- Verifies XAMPP installation
- Tests system PATH
- Checks Apache configuration
- Validates PHP extensions
- Tests service connectivity

**Usage**: Visit `http://localhost/bomoko/debug_php_path.php`

### 2. `fix_php_path.php`
**Step-by-step fix instructions**
- Detects common issues
- Provides manual fix instructions
- Includes troubleshooting table
- Links to other diagnostic tools

**Usage**: Visit `http://localhost/bomoko/fix_php_path.php`

### 3. `add_php_to_path.ps1`
**PowerShell script to add PHP to PATH**
- Automatically adds XAMPP paths to system PATH
- Must be run as Administrator
- Tests PHP functionality
- Provides next steps

**Usage**: Right-click → "Run as Administrator"

### 4. `set_php_path.bat`
**Batch file for PATH checking**
- Checks XAMPP installation
- Tests PHP from command line
- Provides manual PATH instructions

**Usage**: Double-click to run

### 5. `status.php`
**System status overview**
- Quick system health check
- Database connection test
- File permission verification
- Service status check

**Usage**: Visit `http://localhost/bomoko/status.php`

### 6. `test_system.php`
**Basic system test**
- PHP functionality test
- Database connection test
- Extension verification
- Quick links to other tools

**Usage**: Visit `http://localhost/bomoko/test_system.php`

## Common Issues & Solutions

### Issue: "php is not recognized as an internal or external command"

**Solution 1**: Add to System PATH
1. Right-click "This PC" → Properties
2. Advanced system settings → Environment Variables
3. Edit System PATH
4. Add: `C:\xampp\php`
5. Add: `C:\xampp\apache\bin`
6. Restart terminal

**Solution 2**: Use PowerShell script
```powershell
# Run as Administrator
.\add_php_to_path.ps1
```

**Solution 3**: Use full path
```cmd
C:\xampp\php\php.exe -v
```

### Issue: Apache can't find PHP module

**Solution**:
1. Open `C:\xampp\apache\conf\httpd.conf`
2. Ensure these lines are uncommented:
   ```apache
   LoadModule php_module modules/libphp.so
   AddHandler application/x-httpd-php .php
   PHPIniDir "C:/xampp/php"
   ```
3. Restart Apache

### Issue: PHP extensions not loading

**Solution**:
1. Open `C:\xampp\php\php.ini`
2. Uncomment these lines:
   ```ini
   extension=pdo
   extension=pdo_mysql
   extension=mysqli
   extension=openssl
   extension=mbstring
   ```
3. Restart Apache

## Testing Your Fix

After making changes, test with these URLs:

1. **Basic test**: `http://localhost/bomoko/test_system.php`
2. **Detailed check**: `http://localhost/bomoko/debug_php_path.php`
3. **System status**: `http://localhost/bomoko/status.php`
4. **Database setup**: `http://localhost/bomoko/setup_database.php`
5. **Main system**: `http://localhost/bomoko/landing.php`

## Command Line Testing

```cmd
# Test PHP
php -v
php -m | findstr pdo

# Test with full path
C:\xampp\php\php.exe -v

# Test Apache
curl http://localhost/

# Test MySQL
mysql -u root -p -e "SELECT VERSION();"
```

## Troubleshooting Guide

See `PHP_TROUBLESHOOTING_GUIDE.md` for comprehensive troubleshooting instructions.

## File Structure

```
bomoko/
├── debug_php_path.php          # Comprehensive PHP path debug
├── fix_php_path.php            # Step-by-step fix instructions
├── add_php_to_path.ps1         # PowerShell PATH setup script
├── set_php_path.bat            # Batch file for PATH checking
├── status.php                  # System status overview
├── test_system.php             # Basic system test
├── setup_database.php          # Database setup
├── PHP_TROUBLESHOOTING_GUIDE.md # Comprehensive guide
└── PHP_DEBUG_README.md         # This file
```

## Support

If issues persist:
1. Check XAMPP forums
2. Review error logs in `C:\xampp\apache\logs\`
3. Try alternative PHP servers (WAMP, Laragon)
4. Consider Docker deployment

## Quick Commands

```cmd
# Check if PHP is in PATH
where php

# Check XAMPP installation
dir C:\xampp\php\php.exe

# Test PHP directly
C:\xampp\php\php.exe -v

# Check Apache config
C:\xampp\apache\bin\httpd.exe -t
``` 