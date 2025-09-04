<?php
echo "<!DOCTYPE html>";
echo "<html><head><title>System Test</title><style>";
echo "body{font-family:Arial,sans-serif;margin:20px;}";
echo ".success{color:green;} .error{color:red;} .warning{color:orange;}";
echo "</style></head><body>";
echo "<h1>DRC Embassy Management System - System Test</h1>";

// Test 1: PHP is working
echo "<h2>✓ PHP is working</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test 2: Database connection
echo "<h2>Database Connection Test</h2>";
try {
    require_once 'db_connect.php';
    echo "<p class='success'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p><a href='setup_database.php'>Click here to set up the database</a></p>";
}

// Test 3: Required extensions
echo "<h2>Required Extensions</h2>";
$extensions = ['pdo', 'pdo_mysql', 'session'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✓ $ext extension loaded</p>";
    } else {
        echo "<p class='error'>✗ $ext extension not loaded</p>";
    }
}

// Test 4: File permissions
echo "<h2>File Permissions</h2>";
if (is_dir('uploads') && is_writable('uploads')) {
    echo "<p class='success'>✓ Uploads directory exists and is writable</p>";
} else {
    echo "<p class='error'>✗ Uploads directory missing or not writable</p>";
}

// Test 5: Session functionality
echo "<h2>Session Test</h2>";
session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p class='success'>✓ Sessions are working</p>";
} else {
    echo "<p class='error'>✗ Sessions are not working</p>";
}

echo "<h2>Next Steps</h2>";
echo "<ul>";
echo "<li><a href='setup_database.php'>Set up database</a></li>";
echo "<li><a href='php_check.php'>Detailed configuration check</a></li>";
echo "<li><a href='index.php'>Go to main system</a></li>";
echo "<li><a href='register.php'>Register a new user</a></li>";
echo "</ul>";

echo "<h2>Quick Links</h2>";
echo "<p><a href='login.php'>Login</a> | <a href='register.php'>Register</a> | <a href='admin.php'>Admin</a> | <a href='visa.php'>Visa Application</a></p>";

echo "</body></html>";
?> 