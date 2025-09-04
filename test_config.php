<?php
require_once 'config.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Configuration Test</title>";
echo "<style>";
echo "body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".container{max-width:1000px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo ".success{color:#28a745;font-weight:bold;}";
echo ".error{color:#dc3545;font-weight:bold;}";
echo ".warning{color:#ffc107;font-weight:bold;}";
echo ".info{color:#17a2b8;font-weight:bold;}";
echo ".section{margin:20px 0;padding:15px;border-left:4px solid #007bff;background:#f8f9fa;}";
echo ".code{background:#f1f1f1;padding:10px;border-radius:5px;font-family:monospace;margin:10px 0;white-space:pre-wrap;}";
echo ".btn{display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:5px;}";
echo ".btn:hover{background:#0056b3;}";
echo "table{border-collapse:collapse;width:100%;margin:10px 0;}";
echo "th,td{padding:8px;border:1px solid #ddd;text-align:left;}";
echo "th{background-color:#f2f2f2;}";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>Configuration Test - DRC Embassy Management System</h1>";

// Test 1: Basic Configuration Loading
echo "<div class='section'>";
echo "<h2>1. Configuration Loading Test</h2>";
try {
    $config = Config::load();
    echo "<p><span class='success'>✓ Configuration loaded successfully</span></p>";
    echo "<p><span class='info'>Project:</span> " . Config::get('project.name') . " v" . Config::get('project.version') . "</p>";
} catch (Exception $e) {
    echo "<p><span class='error'>✗ Configuration loading failed:</span> " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 2: Database Configuration
echo "<div class='section'>";
echo "<h2>2. Database Configuration</h2>";
$db_config = Config::getDatabase();
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
foreach ($db_config as $key => $value) {
    $display_value = $key === 'password' ? '***' : $value;
    echo "<tr><td>$key</td><td>$display_value</td></tr>";
}
echo "</table>";

// Test database connection
try {
    $dsn = Config::getDatabaseDsn();
    $pdo = new PDO($dsn, $db_config['user'], $db_config['password']);
    echo "<p><span class='success'>✓ Database connection successful</span></p>";
} catch (PDOException $e) {
    echo "<p><span class='error'>✗ Database connection failed:</span> " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: XAMPP Configuration
echo "<div class='section'>";
echo "<h2>3. XAMPP Configuration</h2>";
$xampp_config = Config::getXampp();
echo "<table>";
echo "<tr><th>Component</th><th>Path</th><th>Status</th></tr>";
foreach ($xampp_config as $component => $path) {
    $exists = file_exists($path) ? "✓ Exists" : "✗ Missing";
    $status_class = file_exists($path) ? "success" : "error";
    echo "<tr><td>$component</td><td>$path</td><td><span class='$status_class'>$exists</span></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 4: System Configuration
echo "<div class='section'>";
echo "<h2>4. System Configuration</h2>";
$system_config = Config::getSystem();
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
foreach ($system_config as $key => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    echo "<tr><td>$key</td><td>$value</td></tr>";
}
echo "</table>";
echo "</div>";

// Test 5: Feature Flags
echo "<div class='section'>";
echo "<h2>5. Feature Flags</h2>";
$features = Config::getFeatures();
echo "<table>";
echo "<tr><th>Feature</th><th>Enabled</th></tr>";
foreach ($features as $feature => $enabled) {
    $status = $enabled ? "✓ Yes" : "✗ No";
    $status_class = $enabled ? "success" : "error";
    echo "<tr><td>$feature</td><td><span class='$status_class'>$status</span></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 6: URLs Configuration
echo "<div class='section'>";
echo "<h2>6. URLs Configuration</h2>";
$urls = Config::getUrls();
echo "<table>";
echo "<tr><th>Page</th><th>URL</th></tr>";
foreach ($urls as $page => $url) {
    echo "<tr><td>$page</td><td><a href='$url' target='_blank'>$url</a></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 7: Debug URLs
echo "<div class='section'>";
echo "<h2>7. Debug URLs</h2>";
$debug_urls = Config::getDebugUrls();
echo "<table>";
echo "<tr><th>Tool</th><th>URL</th></tr>";
foreach ($debug_urls as $tool => $url) {
    echo "<tr><td>$tool</td><td><a href='$url' target='_blank'>$url</a></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 8: Validation Rules
echo "<div class='section'>";
echo "<h2>8. Validation Rules</h2>";
$validation = Config::getValidation();
echo "<table>";
echo "<tr><th>Rule</th><th>Value</th></tr>";
foreach ($validation as $rule => $value) {
    $display_value = is_bool($value) ? ($value ? "Yes" : "No") : $value;
    echo "<tr><td>$rule</td><td>$display_value</td></tr>";
}
echo "</table>";
echo "</div>";

// Test 9: Security Settings
echo "<div class='section'>";
echo "<h2>9. Security Settings</h2>";
$security = Config::getSecurity();
echo "<table>";
echo "<tr><th>Setting</th><th>Enabled</th></tr>";
foreach ($security as $setting => $enabled) {
    $status = $enabled ? "✓ Yes" : "✗ No";
    $status_class = $enabled ? "success" : "error";
    echo "<tr><td>$setting</td><td><span class='$status_class'>$status</span></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 10: Configuration Validation
echo "<div class='section'>";
echo "<h2>10. Configuration Validation</h2>";
$errors = Config::validate();
if (empty($errors)) {
    echo "<p><span class='success'>✓ Configuration validation passed</span></p>";
} else {
    echo "<p><span class='error'>✗ Configuration validation failed:</span></p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li><span class='error'>$error</span></li>";
    }
    echo "</ul>";
}
echo "</div>";

// Test 11: Directory Structure
echo "<div class='section'>";
echo "<h2>11. Directory Structure</h2>";
$paths = Config::getPaths();
echo "<table>";
echo "<tr><th>Directory</th><th>Path</th><th>Status</th></tr>";
foreach ($paths as $dir => $path) {
    $exists = is_dir($path) ? "✓ Exists" : "✗ Missing";
    $writable = is_dir($path) && is_writable($path) ? "✓ Writable" : "✗ Not Writable";
    $status_class = is_dir($path) ? "success" : "error";
    echo "<tr><td>$dir</td><td>$path</td><td><span class='$status_class'>$exists, $writable</span></td></tr>";
}
echo "</table>";
echo "</div>";

// Test 12: Embassy Information
echo "<div class='section'>";
echo "<h2>12. Embassy Information</h2>";
$embassy = Config::getEmbassy();
echo "<table>";
echo "<tr><th>Field</th><th>Value</th></tr>";
foreach ($embassy as $field => $value) {
    echo "<tr><td>$field</td><td>$value</td></tr>";
}
echo "</table>";
echo "</div>";

// Quick Actions
echo "<div class='section'>";
echo "<h2>Quick Actions</h2>";
echo "<p>";
echo "<a href='debug_php_path.php' class='btn'>Debug PHP Path</a> ";
echo "<a href='fix_php_path.php' class='btn'>Fix PHP Path</a> ";
echo "<a href='setup_database.php' class='btn'>Setup Database</a> ";
echo "<a href='landing.php' class='btn'>Go to Landing Page</a>";
echo "</p>";
echo "</div>";

// Configuration Summary
echo "<div class='section'>";
echo "<h2>Configuration Summary</h2>";
echo "<div class='code'>";
echo "Project: " . Config::get('project.name') . "\n";
echo "Version: " . Config::get('project.version') . "\n";
echo "Environment: " . Config::get('deployment.environment') . "\n";
echo "Debug Mode: " . (Config::isDebugMode() ? "Enabled" : "Disabled") . "\n";
echo "Base URL: " . Config::getBaseUrl() . "\n";
echo "Upload Directory: " . Config::getUploadDir() . "\n";
echo "Session Timeout: " . Config::getSessionTimeout() . " seconds\n";
echo "Max File Size: " . Config::getMaxFileSize() . "\n";
echo "Allowed File Types: " . implode(', ', Config::getAllowedFileTypes()) . "\n";
echo "</div>";
echo "</div>";

echo "</div></body></html>";
?> 