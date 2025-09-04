<?php
echo "<!DOCTYPE html>";
echo "<html><head><title>System Status</title>";
echo "<style>";
echo "body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".container{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo ".success{color:#28a745;font-weight:bold;}";
echo ".error{color:#dc3545;font-weight:bold;}";
echo ".warning{color:#ffc107;font-weight:bold;}";
echo ".info{color:#17a2b8;font-weight:bold;}";
echo ".section{margin:20px 0;padding:15px;border-left:4px solid #007bff;background:#f8f9fa;}";
echo ".btn{display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:5px;}";
echo ".btn:hover{background:#0056b3;}";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>DRC Embassy Management System - Status Check</h1>";

// Check 1: PHP Version and Basic Functionality
echo "<div class='section'>";
echo "<h2>1. PHP Environment</h2>";
echo "<p><span class='success'>✓ PHP Version:</span> " . phpversion() . "</p>";
echo "<p><span class='success'>✓ PHP SAPI:</span> " . php_sapi_name() . "</p>";
echo "<p><span class='success'>✓ PHP Binary:</span> " . PHP_BINARY . "</p>";
echo "</div>";

// Check 2: Required Extensions
echo "<div class='section'>";
echo "<h2>2. Required Extensions</h2>";
$required_extensions = [
    'pdo' => 'Database connectivity',
    'pdo_mysql' => 'MySQL database support',
    'session' => 'Session management',
    'fileinfo' => 'File upload handling'
];

foreach ($required_extensions as $ext => $description) {
    if (extension_loaded($ext)) {
        echo "<p><span class='success'>✓ $ext:</span> $description</p>";
    } else {
        echo "<p><span class='error'>✗ $ext:</span> $description - <span class='warning'>MISSING!</span></p>";
    }
}
echo "</div>";

// Check 3: Database Connection
echo "<div class='section'>";
echo "<h2>3. Database Connection</h2>";
try {
    require_once 'db_connect.php';
    echo "<p><span class='success'>✓ Database connection successful</span></p>";
    
    // Check tables
    $tables = ['users', 'visa_applications', 'incident_reports', 'visa_documents'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p><span class='success'>✓ Table '$table' exists</span></p>";
        } else {
            echo "<p><span class='error'>✗ Table '$table' missing</span></p>";
        }
    }
} catch (Exception $e) {
    echo "<p><span class='error'>✗ Database connection failed:</span> " . $e->getMessage() . "</p>";
    echo "<p><a href='setup_database.php' class='btn'>Set up Database</a></p>";
}
echo "</div>";

// Check 4: File System
echo "<div class='section'>";
echo "<h2>4. File System</h2>";
$required_dirs = ['uploads', 'assets'];
foreach ($required_dirs as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir) ? "writable" : "not writable";
        $status = is_writable($dir) ? "success" : "warning";
        echo "<p><span class='$status'>✓ Directory '$dir' exists and is $writable</span></p>";
    } else {
        echo "<p><span class='error'>✗ Directory '$dir' missing</span></p>";
    }
}

// Check for logo file
if (file_exists('assets/drc logo.png')) {
    echo "<p><span class='success'>✓ Logo file exists</span></p>";
} else {
    echo "<p><span class='warning'>⚠ Logo file missing (assets/drc logo.png)</span></p>";
}
echo "</div>";

// Check 5: Session Functionality
echo "<div class='section'>";
echo "<h2>5. Session Management</h2>";
session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p><span class='success'>✓ Sessions are working</span></p>";
} else {
    echo "<p><span class='error'>✗ Sessions are not working</span></p>";
}
echo "</div>";

// Check 6: XAMPP Services
echo "<div class='section'>";
echo "<h2>6. XAMPP Services Status</h2>";
echo "<p><span class='info'>ℹ To check XAMPP services:</span></p>";
echo "<ul>";
echo "<li>Open XAMPP Control Panel</li>";
echo "<li>Ensure Apache shows green status</li>";
echo "<li>Ensure MySQL shows green status</li>";
echo "<li>If services are red, click 'Start'</li>";
echo "</ul>";
echo "</div>";

// Recommendations
echo "<div class='section'>";
echo "<h2>7. Recommendations</h2>";
echo "<ul>";
echo "<li><a href='setup_database.php' class='btn'>Set up Database</a> - If database tables are missing</li>";
echo "<li><a href='test_system.php' class='btn'>Test System</a> - Quick system test</li>";
echo "<li><a href='php_check.php' class='btn'>Detailed Check</a> - Comprehensive configuration check</li>";
echo "<li><a href='landing.php' class='btn'>Go to Landing Page</a> - Main system entry</li>";
echo "</ul>";
echo "</div>";

// Quick Actions
echo "<div class='section'>";
echo "<h2>8. Quick Actions</h2>";
echo "<p><a href='landing.php' class='btn'>Landing Page</a> ";
echo "<a href='login.php' class='btn'>Login</a> ";
echo "<a href='register.php' class='btn'>Register</a> ";
echo "<a href='admin.php' class='btn'>Admin</a></p>";
echo "</div>";

echo "</div></body></html>";
?> 