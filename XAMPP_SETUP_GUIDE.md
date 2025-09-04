# XAMPP Setup Guide for DRC Embassy Management System

## Step 1: Start XAMPP Services

1. **Open XAMPP Control Panel**
   - Navigate to your XAMPP installation directory (usually `C:\xampp`)
   - Run `xampp-control.exe` as Administrator

2. **Start Required Services**
   - Click "Start" for **Apache**
   - Click "Start" for **MySQL**
   - Wait for both services to show green status

## Step 2: Access Your Project

1. **Open your web browser**
2. **Navigate to**: `http://localhost/bomoko/`
3. **If you get a "PHP executable not found" error**, try these URLs:
   - `http://localhost/bomoko/php_check.php` (to diagnose issues)
   - `http://localhost/bomoko/setup_database.php` (to set up database)

## Step 3: Database Setup

1. **Run the database setup script**:
   - Go to: `http://localhost/bomoko/setup_database.php`
   - This will create the database and all required tables

2. **Verify database setup**:
   - Go to: `http://localhost/bomoko/php_check.php`
   - Check that all database tables are marked as "✓ Exists"

## Step 4: Common Issues and Solutions

### Issue: "PHP executable not found"
**Solution**: 
- Ensure Apache is running in XAMPP Control Panel
- Check that your files are in the correct directory: `C:\xampp\htdocs\bomoko\`
- Try accessing via `http://localhost/bomoko/` (not file:// protocol)

### Issue: "Database connection failed"
**Solution**:
- Ensure MySQL is running in XAMPP Control Panel
- Run `setup_database.php` to create the database
- Check that the database credentials in `db_connect.php` are correct

### Issue: "Uploads directory missing"
**Solution**:
- Create an `uploads/` folder in your project directory
- Ensure the folder has write permissions

### Issue: "PDO extension not loaded"
**Solution**:
- Open `C:\xampp\php\php.ini`
- Uncomment the line: `;extension=pdo_mysql` (remove the semicolon)
- Restart Apache in XAMPP Control Panel

## Step 5: Test the System

1. **Check configuration**: `http://localhost/bomoko/php_check.php`
2. **Set up database**: `http://localhost/bomoko/setup_database.php`
3. **Access main system**: `http://localhost/bomoko/`

## Step 6: Create Uploads Directory

If the uploads directory doesn't exist, create it:
1. Navigate to your project folder: `C:\xampp\htdocs\bomoko\`
2. Create a new folder named `uploads`
3. Ensure the folder has write permissions

## File Structure Verification

Your project should have this structure:
```
C:\xampp\htdocs\bomoko\
├── index.php
├── login.php
├── register.php
├── admin.php
├── visa.php
├── incident.php
├── contact.php
├── db_connect.php
├── setup_database.php
├── php_check.php
├── uploads/          (create this folder)
├── assets/
│   └── drc logo.png
└── styles.css
```

## Troubleshooting

### If Apache won't start:
1. Check if port 80 is in use (Skype, IIS, etc.)
2. Change Apache port in `C:\xampp\apache\conf\httpd.conf`
3. Restart XAMPP Control Panel

### If MySQL won't start:
1. Check if port 3306 is in use
2. Check MySQL error logs in `C:\xampp\mysql\data\`
3. Try stopping and starting MySQL again

### If you can't access the site:
1. Ensure you're using `http://localhost/bomoko/` not `file://`
2. Check that all files are in the correct directory
3. Verify Apache and MySQL are running (green status)

## Support

If you continue to have issues:
1. Run `php_check.php` and note any error messages
2. Check XAMPP error logs in `C:\xampp\apache\logs\`
3. Ensure all required PHP extensions are enabled 