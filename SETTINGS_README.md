# Settings Configuration Guide

This document explains how to use the `settings.json` configuration file for the DRC Embassy Management System.

## Overview

The `settings.json` file contains all configuration settings for the system, including:
- Database connection settings
- XAMPP paths
- Embassy information
- System features and validation rules
- Security settings
- URLs and file paths

## Files

- `settings.json` - Main configuration file
- `config.php` - PHP configuration loader class
- `test_config.php` - Configuration testing script

## Quick Start

1. **Test the configuration**:
   ```
   http://localhost/bomoko/test_config.php
   ```

2. **Use in your PHP files**:
   ```php
   require_once 'config.php';
   
   // Get database settings
   $db_config = Config::getDatabase();
   
   // Check if a feature is enabled
   if (Config::isFeatureEnabled('user_registration')) {
       // Registration is enabled
   }
   
   // Get upload directory
   $upload_dir = Config::getUploadDir();
   ```

## Configuration Sections

### 1. Project Information
```json
{
  "project": {
    "name": "DRC Embassy Management System",
    "version": "1.0.0",
    "description": "Web-based management system for DRC Embassy in Nairobi, Kenya",
    "author": "DRC Embassy Nairobi",
    "year": "2025"
  }
}
```

### 2. Database Configuration
```json
{
  "database": {
    "host": "localhost",
    "name": "bomoko_db",
    "user": "root",
    "password": "",
    "charset": "utf8mb4",
    "port": 3306
  }
}
```

### 3. XAMPP Paths
```json
{
  "xampp": {
    "php_path": "C:\\xampp\\php",
    "apache_path": "C:\\xampp\\apache",
    "mysql_path": "C:\\xampp\\mysql",
    "htdocs_path": "C:\\xampp\\htdocs\\bomoko"
  }
}
```

### 4. Embassy Information
```json
{
  "embassy": {
    "name": "DRC Embassy Nairobi",
    "address": "Riverside Drive, Nairobi, Kenya",
    "email": "info@drcembassy.co.ke",
    "phone": "+254 712 345 678",
    "website": "http://localhost/bomoko/"
  }
}
```

### 5. System Settings
```json
{
  "system": {
    "upload_dir": "uploads/",
    "max_file_size": "10MB",
    "allowed_file_types": ["pdf", "jpg", "jpeg", "png"],
    "session_timeout": 3600,
    "debug_mode": false
  }
}
```

### 6. Feature Flags
```json
{
  "features": {
    "user_registration": true,
    "visa_applications": true,
    "incident_reports": true,
    "admin_dashboard": true,
    "file_upload": true,
    "email_notifications": false
  }
}
```

## Using the Config Class

### Basic Usage

```php
require_once 'config.php';

// Get a specific value
$project_name = Config::get('project.name');
$db_host = Config::get('database.host');

// Get entire sections
$db_config = Config::getDatabase();
$embassy_info = Config::getEmbassy();

// Check feature flags
if (Config::isFeatureEnabled('user_registration')) {
    // Registration is enabled
}

// Get paths
$upload_dir = Config::getUploadDir();
$assets_dir = Config::getAssetsDir();
```

### Database Connection

```php
// Get database DSN
$dsn = Config::getDatabaseDsn();
$db_config = Config::getDatabase();

// Create PDO connection
$pdo = new PDO($dsn, $db_config['user'], $db_config['password']);
```

### Validation

```php
// Validate configuration
$errors = Config::validate();
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "Configuration error: $error\n";
    }
}
```

## Configuration Methods

### Core Methods

- `Config::load()` - Load configuration from settings.json
- `Config::get($key, $default)` - Get a configuration value
- `Config::validate()` - Validate configuration settings

### Database Methods

- `Config::getDatabase()` - Get database configuration
- `Config::getDatabaseDsn()` - Get database connection string

### System Methods

- `Config::getSystem()` - Get system configuration
- `Config::getFeatures()` - Get feature flags
- `Config::getValidation()` - Get validation rules
- `Config::getSecurity()` - Get security settings

### Path Methods

- `Config::getPaths()` - Get file paths
- `Config::getUploadDir()` - Get upload directory
- `Config::getAssetsDir()` - Get assets directory
- `Config::getLogsDir()` - Get logs directory

### URL Methods

- `Config::getUrls()` - Get page URLs
- `Config::getDebugUrls()` - Get debug tool URLs
- `Config::getBaseUrl()` - Get base URL

### Environment Methods

- `Config::getXampp()` - Get XAMPP configuration
- `Config::getPhp()` - Get PHP configuration
- `Config::getApache()` - Get Apache configuration
- `Config::getMysql()` - Get MySQL configuration

### Feature Methods

- `Config::isFeatureEnabled($feature)` - Check if feature is enabled
- `Config::isDebugMode()` - Check if debug mode is enabled

## Environment-Specific Settings

The configuration supports different environments:

```json
{
  "environment": {
    "development": {
      "debug_mode": true,
      "display_errors": true,
      "log_errors": true
    },
    "production": {
      "debug_mode": false,
      "display_errors": false,
      "log_errors": true
    }
  }
}
```

## Security Settings

```json
{
  "security": {
    "password_hashing": true,
    "session_management": true,
    "csrf_protection": true,
    "input_sanitization": true,
    "sql_injection_protection": true
  }
}
```

## Validation Rules

```json
{
  "validation": {
    "password_min_length": 8,
    "require_password_complexity": true,
    "passport_prefix": "OP",
    "dob_future_restriction": true,
    "travel_date_future_restriction": true
  }
}
```

## Customization

### Adding New Settings

1. Add the setting to `settings.json`
2. Access it using `Config::get('section.setting')`
3. Optionally add a helper method to `config.php`

### Example: Adding Email Settings

```json
{
  "email": {
    "smtp_host": "smtp.gmail.com",
    "smtp_port": 587,
    "smtp_user": "your-email@gmail.com",
    "smtp_password": "your-password"
  }
}
```

```php
// In config.php
public static function getEmail() {
    return self::get('email');
}

// Usage
$email_config = Config::getEmail();
```

## Testing Configuration

Run the configuration test:

```
http://localhost/bomoko/test_config.php
```

This will test:
- Configuration loading
- Database connection
- XAMPP paths
- Directory permissions
- Feature flags
- URLs and paths

## Troubleshooting

### Common Issues

1. **Configuration not loading**
   - Check if `settings.json` exists
   - Verify JSON syntax is valid
   - Check file permissions

2. **Database connection failed**
   - Verify database settings in `settings.json`
   - Ensure MySQL is running
   - Check credentials

3. **Paths not found**
   - Update XAMPP paths in `settings.json`
   - Ensure directories exist
   - Check file permissions

### Debug Commands

```php
// Check if configuration loaded
try {
    $config = Config::load();
    echo "Configuration loaded successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Validate configuration
$errors = Config::validate();
if (!empty($errors)) {
    print_r($errors);
}

// Get configuration as JSON
echo Config::toJson();
```

## Best Practices

1. **Never commit sensitive data** - Use environment variables for passwords
2. **Validate configuration** - Always validate before using
3. **Use helper methods** - Use the provided helper methods instead of direct access
4. **Test configuration** - Run `test_config.php` after changes
5. **Backup settings** - Keep a backup of your settings.json

## Migration from Hardcoded Values

If you have hardcoded values in your PHP files, replace them:

```php
// Before
$db_host = 'localhost';
$upload_dir = 'uploads/';

// After
$db_host = Config::get('database.host');
$upload_dir = Config::getUploadDir();
```

## Support

For issues with configuration:
1. Run `test_config.php` to identify problems
2. Check the validation errors
3. Verify JSON syntax in `settings.json`
4. Ensure all required directories exist 