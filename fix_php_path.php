<?php
echo "<!DOCTYPE html>";
echo "<html><head><title>PHP Path Fix</title>";
echo "<style>";
echo "body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".container{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo ".success{color:#28a745;font-weight:bold;}";
echo ".error{color:#dc3545;font-weight:bold;}";
echo ".warning{color:#ffc107;font-weight:bold;}";
echo ".info{color:#17a2b8;font-weight:bold;}";
echo ".section{margin:20px 0;padding:15px;border-left:4px solid #007bff;background:#f8f9fa;}";
echo ".code{background:#f1f1f1;padding:10px;border-radius:5px;font-family:monospace;margin:10px 0;}";
echo ".btn{display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:5px;}";
echo ".btn:hover{background:#0056b3;}";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>PHP Path Fix Tool</h1>";

// Check if we can write to system files (we can't, but we can provide instructions)
echo "<div class='section'>";
echo "<h2>PHP Path Issues Detection</h2>";

$issues_found = false;

// Check 1: XAMPP PHP executable
$xampp_php = 'C:\\xampp\\php\\php.exe';
if (!file_exists($xampp_php)) {
    echo "<p><span class='error'>✗ Issue Found:</span> XAMPP PHP executable missing at $xampp_php</p>";
    $issues_found = true;
} else {
    echo "<p><span class='success'>✓ XAMPP PHP executable found</span></p>";
}

// Check 2: System PATH
$path = getenv('PATH');
$xampp_in_path = (strpos($path, 'xampp') !== false || strpos($path, 'XAMPP') !== false);
if (!$xampp_in_path) {
    echo "<p><span class='error'>✗ Issue Found:</span> XAMPP not in system PATH</p>";
    $issues_found = true;
} else {
    echo "<p><span class='success'>✓ XAMPP found in system PATH</span></p>";
}

// Check 3: Apache configuration
$httpd_conf = 'C:\\xampp\\apache\\conf\\httpd.conf';
if (file_exists($httpd_conf)) {
    $config_content = file_get_contents($httpd_conf);
    if (strpos($config_content, 'php_module') === false) {
        echo "<p><span class='error'>✗ Issue Found:</span> PHP module not configured in Apache</p>";
        $issues_found = true;
    } else {
        echo "<p><span class='success'>✓ PHP module configured in Apache</span></p>";
    }
} else {
    echo "<p><span class='error'>✗ Issue Found:</span> Apache configuration file missing</p>";
    $issues_found = true;
}

if (!$issues_found) {
    echo "<p><span class='success'>✓ No PHP path issues detected!</span></p>";
}
echo "</div>";

// Provide fixes
echo "<div class='section'>";
echo "<h2>Manual Fix Instructions</h2>";

if (!$issues_found) {
    echo "<p><span class='success'>✓ Your PHP path appears to be correctly configured!</span></p>";
    echo "<p>If you're still experiencing issues, try the following:</p>";
} else {
    echo "<p><span class='warning'>⚠ Issues detected. Follow these steps to fix:</span></p>";
}

echo "<h3>Step 1: Add XAMPP to System PATH</h3>";
echo "<div class='code'>";
echo "1. Right-click on 'This PC' or 'My Computer'<br>";
echo "2. Select 'Properties'<br>";
echo "3. Click 'Advanced system settings'<br>";
echo "4. Click 'Environment Variables'<br>";
echo "5. Under 'System variables', find 'Path' and click 'Edit'<br>";
echo "6. Click 'New' and add: C:\\xampp\\php<br>";
echo "7. Click 'New' and add: C:\\xampp\\apache\\bin<br>";
echo "8. Click 'OK' on all dialogs<br>";
echo "9. Restart your command prompt/terminal";
echo "</div>";

echo "<h3>Step 2: Verify XAMPP Installation</h3>";
echo "<div class='code'>";
echo "1. Navigate to C:\\xampp<br>";
echo "2. Ensure these folders exist:<br>";
echo "   - C:\\xampp\\php<br>";
echo "   - C:\\xampp\\apache<br>";
echo "   - C:\\xampp\\mysql<br>";
echo "3. Check that php.exe exists in C:\\xampp\\php\\";
echo "</div>";

echo "<h3>Step 3: Configure Apache for PHP</h3>";
echo "<div class='code'>";
echo "1. Open C:\\xampp\\apache\\conf\\httpd.conf<br>";
echo "2. Ensure these lines are uncommented (no # at start):<br>";
echo "   LoadModule php_module modules/libphp.so<br>";
echo "   AddHandler application/x-httpd-php .php<br>";
echo "   PHPIniDir \"C:/xampp/php\"<br>";
echo "3. Save the file and restart Apache";
echo "</div>";

echo "<h3>Step 4: Check PHP Configuration</h3>";
echo "<div class='code'>";
echo "1. Open C:\\xampp\\php\\php.ini<br>";
echo "2. Ensure these extensions are uncommented:<br>";
echo "   extension=pdo<br>";
echo "   extension=pdo_mysql<br>";
echo "   extension=mysqli<br>";
echo "   extension=openssl<br>";
echo "   extension=mbstring<br>";
echo "3. Save the file and restart Apache";
echo "</div>";
echo "</div>";

// Command line test
echo "<div class='section'>";
echo "<h2>Command Line Test</h2>";
echo "<p>After making the above changes, test PHP from command line:</p>";
echo "<div class='code'>";
echo "1. Open Command Prompt as Administrator<br>";
echo "2. Type: php -v<br>";
echo "3. If successful, you should see PHP version info<br>";
echo "4. If not, check your PATH settings again";
echo "</div>";
echo "</div>";

// XAMPP Control Panel instructions
echo "<div class='section'>";
echo "<h2>XAMPP Control Panel Instructions</h2>";
echo "<div class='code'>";
echo "1. Open C:\\xampp\\xampp-control.exe as Administrator<br>";
echo "2. Stop all services if running<br>";
echo "3. Click 'Config' button for Apache<br>";
echo "4. Select 'httpd.conf'<br>";
echo "5. Verify PHP module configuration<br>";
echo "6. Save and close<br>";
echo "7. Start Apache and MySQL<br>";
echo "8. Check for error messages in the logs";
echo "</div>";
echo "</div>";

// Quick test links
echo "<div class='section'>";
echo "<h2>Test Your Fix</h2>";
echo "<p>After making changes, test these URLs:</p>";
echo "<ul>";
echo "<li><a href='debug_php_path.php' class='btn'>Debug PHP Path</a> - Comprehensive path check</li>";
echo "<li><a href='test_system.php' class='btn'>Test System</a> - Quick system test</li>";
echo "<li><a href='php_check.php' class='btn'>PHP Check</a> - Basic PHP configuration</li>";
echo "<li><a href='landing.php' class='btn'>Landing Page</a> - Main system entry</li>";
echo "</ul>";
echo "</div>";

// Troubleshooting
echo "<div class='section'>";
echo "<h2>Common Error Messages & Solutions</h2>";
echo "<table style='width:100%;border-collapse:collapse;'>";
echo "<tr style='background:#f8f9fa;'><th style='padding:10px;border:1px solid #ddd;'>Error</th><th style='padding:10px;border:1px solid #ddd;'>Solution</th></tr>";
echo "<tr><td style='padding:10px;border:1px solid #ddd;'>'php' is not recognized</td><td style='padding:10px;border:1px solid #ddd;'>Add C:\\xampp\\php to PATH</td></tr>";
echo "<tr><td style='padding:10px;border:1px solid #ddd;'>Apache won't start</td><td style='padding:10px;border:1px solid #ddd;'>Check port 80, stop IIS/Skype</td></tr>";
echo "<tr><td style='padding:10px;border:1px solid #ddd;'>MySQL won't start</td><td style='padding:10px;border:1px solid #ddd;'>Check port 3306, check error logs</td></tr>";
echo "<tr><td style='padding:10px;border:1px solid #ddd;'>PHP extensions missing</td><td style='padding:10px;border:1px solid #ddd;'>Uncomment in php.ini</td></tr>";
echo "<tr><td style='padding:10px;border:1px solid #ddd;'>Database connection failed</td><td style='padding:10px;border:1px solid #ddd;'>Start MySQL, check credentials</td></tr>";
echo "</table>";
echo "</div>";

echo "</div></body></html>";
?> 