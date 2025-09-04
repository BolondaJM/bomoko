# PHP Executable Path Troubleshooting Guide

## Quick Diagnosis

1. **Run the debug script**: `http://localhost/bomoko/debug_php_path.php`
2. **Check system status**: `http://localhost/bomoko/status.php`
3. **Test basic functionality**: `http://localhost/bomoko/test_system.php`

## Common Issues & Solutions

### Issue 1: "php is not recognized as an internal or external command"

**Symptoms:**
- Command line `php -v` fails
- XAMPP services won't start
- PHP scripts don't execute

**Solutions:**

#### Method 1: Add to System PATH (Recommended)
1. Right-click on "This PC" or "My Computer"
2. Select "Properties"
3. Click "Advanced system settings"
4. Click "Environment Variables"
5. Under "System variables", find "Path" and click "Edit"
6. Click "New" and add: `C:\xampp\php`
7. Click "New" and add: `C:\xampp\apache\bin`
8. Click "OK" on all dialogs
9. Restart Command Prompt/Terminal

#### Method 2: Use Full Path
- Instead of `php -v`, use: `C:\xampp\php\php.exe -v`

#### Method 3: Create Alias
- Create a batch file `php.bat` in a directory in your PATH:
```batch
@echo off
C:\xampp\php\php.exe %*
```

### Issue 2: Apache can't find PHP module

**Symptoms:**
- Apache starts but PHP files show as text
- Error: "LoadModule php_module not found"

**Solutions:**

1. **Check Apache Configuration**
   - Open `C:\xampp\apache\conf\httpd.conf`
   - Ensure these lines are uncommented (no # at start):
   ```apache
   LoadModule php_module modules/libphp.so
   AddHandler application/x-httpd-php .php
   PHPIniDir "C:/xampp/php"
   ```

2. **Check PHP Module File**
   - Verify `C:\xampp\apache\modules\libphp.so` exists
   - If missing, reinstall XAMPP

3. **Restart Apache**
   - Stop Apache in XAMPP Control Panel
   - Start Apache again

### Issue 3: PHP extensions not loading

**Symptoms:**
- Database connection errors
- "PDO extension not loaded" errors
- Missing function errors

**Solutions:**

1. **Edit php.ini**
   - Open `C:\xampp\php\php.ini`
   - Uncomment these lines (remove semicolon):
   ```ini
   extension=pdo
   extension=pdo_mysql
   extension=mysqli
   extension=openssl
   extension=mbstring
   extension=fileinfo
   ```

2. **Restart Apache**
   - Stop and start Apache in XAMPP Control Panel

### Issue 4: XAMPP services won't start

**Symptoms:**
- Apache/MySQL show red status
- Port conflicts
- Permission errors

**Solutions:**

#### Apache Issues:
1. **Port 80 conflict**
   - Stop IIS: `net stop w3svc`
   - Stop Skype (uses port 80)
   - Change Apache port in `httpd.conf`:
   ```apache
   Listen 8080
   ```

2. **Permission issues**
   - Run XAMPP Control Panel as Administrator
   - Check folder permissions

#### MySQL Issues:
1. **Port 3306 conflict**
   - Stop other MySQL services
   - Change MySQL port in `my.ini`

2. **Data directory issues**
   - Check `C:\xampp\mysql\data` permissions
   - Ensure directory is writable

### Issue 5: Database connection failed

**Symptoms:**
- "Connection refused" errors
- "Access denied" errors
- PDO connection failures

**Solutions:**

1. **Start MySQL**
   - Ensure MySQL is running in XAMPP Control Panel
   - Check for error messages in logs

2. **Check credentials**
   - Default XAMPP MySQL credentials:
     - Host: `localhost`
     - User: `root`
     - Password: `` (empty)

3. **Create database**
   - Run: `http://localhost/bomoko/setup_database.php`

## Step-by-Step Fix Process

### Step 1: Verify XAMPP Installation
```batch
# Check if XAMPP is installed
dir C:\xampp
dir C:\xampp\php
dir C:\xampp\apache
dir C:\xampp\mysql
```

### Step 2: Add to PATH
1. Open System Properties
2. Environment Variables
3. Edit System PATH
4. Add: `C:\xampp\php`
5. Add: `C:\xampp\apache\bin`

### Step 3: Test PHP
```batch
php -v
php -m | findstr pdo
```

### Step 4: Start Services
1. Open XAMPP Control Panel as Administrator
2. Start Apache
3. Start MySQL
4. Check for errors

### Step 5: Test Web Access
1. Open browser
2. Go to: `http://localhost/bomoko/`
3. If issues: `http://localhost/bomoko/debug_php_path.php`

## Advanced Troubleshooting

### Check PHP Configuration
```batch
php --ini
php -m
php -i | findstr "Configuration File"
```

### Check Apache Configuration
```batch
# Test Apache config
C:\xampp\apache\bin\httpd.exe -t
```

### Check MySQL Status
```batch
# Connect to MySQL
C:\xampp\mysql\bin\mysql.exe -u root -p
```

### View Logs
- Apache logs: `C:\xampp\apache\logs\`
- MySQL logs: `C:\xampp\mysql\data\`
- PHP errors: Check `php.ini` error_log setting

## Environment Variables Reference

### Required PATH entries:
```
C:\xampp\php
C:\xampp\apache\bin
C:\xampp\mysql\bin
```

### PHP Environment Variables:
```
PHPRC=C:\xampp\php
PHP_INI_SCAN_DIR=C:\xampp\php\extras
```

## Quick Test Commands

### Test PHP from command line:
```batch
php -v
php -m
php -r "echo 'PHP is working!';"
```

### Test Apache:
```batch
curl http://localhost/
```

### Test MySQL:
```batch
mysql -u root -p -e "SELECT VERSION();"
```

## Emergency Fixes

### If nothing works:
1. **Reinstall XAMPP**
   - Backup your project files
   - Uninstall XAMPP
   - Download fresh XAMPP
   - Install as Administrator

2. **Use Alternative**
   - Try WAMP Server
   - Try Laragon
   - Use Docker with PHP/MySQL

### Quick Recovery:
1. Run `set_php_path.bat` (if available)
2. Visit `http://localhost/bomoko/fix_php_path.php`
3. Follow the diagnostic steps

## Support Tools

- **Debug Script**: `debug_php_path.php`
- **Fix Tool**: `fix_php_path.php`
- **System Test**: `test_system.php`
- **Status Check**: `status.php`
- **Database Setup**: `setup_database.php`

## Contact & Support

If issues persist:
1. Check XAMPP forums
2. Review error logs
3. Try alternative PHP servers
4. Consider Docker deployment 