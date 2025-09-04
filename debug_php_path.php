<?php
echo "<!DOCTYPE html>";
echo "<html><head><title>PHP Path Debug</title>";
echo "<style>";
echo "body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".container{max-width:900px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
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
echo "<h1>PHP Executable Path Debug</h1>";

// 1. Current PHP Information
echo "<div class='section'>";
echo "<h2>1. Current PHP Information</h2>";
echo "<p><span class='success'>✓ PHP Version:</span> " . phpversion() . "</p>";
echo "<p><span class='success'>✓ PHP Binary:</span> " . PHP_BINARY . "</p>";
echo "<p><span class='success'>✓ PHP SAPI:</span> " . php_sapi_name() . "</p>";
echo "<p><span class='success'>✓ PHP Extension Dir:</span> " . ini_get('extension_dir') . "</p>";
echo "</div>";

// 2. XAMPP PHP Paths
echo "<div class='section'>";
echo "<h2>2. XAMPP PHP Paths</h2>";
$xampp_paths = [
    'C:\\xampp\\php\\php.exe',
    'C:\\xampp\\php\\php-cgi.exe',
    'C:\\xampp\\php\\php-win.exe'
];

foreach ($xampp_paths as $path) {
    if (file_exists($path)) {
        echo "<p><span class='success'>✓ Found:</span> $path</p>";
    } else {
        echo "<p><span class='error'>✗ Missing:</span> $path</p>";
    }
}

// Check XAMPP installation
$xampp_base = 'C:\\xampp';
if (is_dir($xampp_base)) {
    echo "<p><span class='success'>✓ XAMPP base directory exists:</span> $xampp_base</p>";
} else {
    echo "<p><span class='error'>✗ XAMPP base directory missing:</span> $xampp_base</p>";
}
echo "</div>";

// 3. System PATH Check
echo "<div class='section'>";
echo "<h2>3. System PATH Check</h2>";
$path = getenv('PATH');
$path_dirs = explode(';', $path);
$xampp_in_path = false;

foreach ($path_dirs as $dir) {
    if (strpos($dir, 'xampp') !== false || strpos($dir, 'XAMPP') !== false) {
        echo "<p><span class='success'>✓ XAMPP in PATH:</span> $dir</p>";
        $xampp_in_path = true;
    }
}

if (!$xampp_in_path) {
    echo "<p><span class='warning'>⚠ XAMPP not found in system PATH</span></p>";
    echo "<div class='code'>";
    echo "To add XAMPP to PATH, add this to your system environment variables:<br>";
    echo "C:\\xampp\\php<br>";
    echo "C:\\xampp\\apache\\bin";
    echo "</div>";
}
echo "</div>";

// 4. Apache Configuration
echo "<div class='section'>";
echo "<h2>4. Apache Configuration</h2>";
$httpd_conf = 'C:\\xampp\\apache\\conf\\httpd.conf';
if (file_exists($httpd_conf)) {
    echo "<p><span class='success'>✓ Apache config exists:</span> $httpd_conf</p>";
    
    // Check for PHP module configuration
    $config_content = file_get_contents($httpd_conf);
    if (strpos($config_content, 'php_module') !== false) {
        echo "<p><span class='success'>✓ PHP module configured in Apache</span></p>";
    } else {
        echo "<p><span class='error'>✗ PHP module not found in Apache config</span></p>";
    }
} else {
    echo "<p><span class='error'>✗ Apache config missing:</span> $httpd_conf</p>";
}
echo "</div>";

// 5. PHP Configuration Files
echo "<div class='section'>";
echo "<h2>5. PHP Configuration Files</h2>";
$php_ini_paths = [
    'C:\\xampp\\php\\php.ini',
    'C:\\xampp\\php\\php.ini-development',
    'C:\\xampp\\php\\php.ini-production'
];

foreach ($php_ini_paths as $ini_path) {
    if (file_exists($ini_path)) {
        echo "<p><span class='success'>✓ Found:</span> $ini_path</p>";
        
        // Check if it's the active php.ini
        if ($ini_path === 'C:\\xampp\\php\\php.ini') {
            $active_ini = php_ini_loaded_file();
            if ($active_ini) {
                echo "<p><span class='success'>✓ Active php.ini:</span> $active_ini</p>";
            } else {
                echo "<p><span class='warning'>⚠ Could not determine active php.ini</span></p>";
            }
        }
    } else {
        echo "<p><span class='error'>✗ Missing:</span> $ini_path</p>";
    }
}
echo "</div>";

// 6. Required Extensions Check
echo "<div class='section'>";
echo "<h2>6. Required Extensions Check</h2>";
$required_extensions = [
    'pdo' => 'Database connectivity',
    'pdo_mysql' => 'MySQL database support',
    'session' => 'Session management',
    'fileinfo' => 'File upload handling',
    'openssl' => 'Security functions',
    'mbstring' => 'String handling'
];

foreach ($required_extensions as $ext => $description) {
    if (extension_loaded($ext)) {
        echo "<p><span class='success'>✓ $ext:</span> $description</p>";
    } else {
        echo "<p><span class='error'>✗ $ext:</span> $description - <span class='warning'>MISSING!</span></p>";
    }
}
echo "</div>";

// 7. XAMPP Services Status
echo "<div class='section'>";
echo "<h2>7. XAMPP Services Status</h2>";
echo "<p><span class='info'>ℹ Manual Check Required:</span></p>";
echo "<ul>";
echo "<li>Open XAMPP Control Panel (C:\\xampp\\xampp-control.exe)</li>";
echo "<li>Check if Apache shows green status</li>";
echo "<li>Check if MySQL shows green status</li>";
echo "<li>If services are red, click 'Start'</li>";
echo "</ul>";

// Check if we can detect running services
$apache_running = false;
$mysql_running = false;

// Try to connect to Apache
$fp = @fsockopen('localhost', 80, $errno, $errstr, 5);
if ($fp) {
    echo "<p><span class='success'>✓ Apache is running on port 80</span></p>";
    $apache_running = true;
    fclose($fp);
} else {
    echo "<p><span class='error'>✗ Apache not responding on port 80</span></p>";
}

// Try to connect to MySQL
$fp = @fsockopen('localhost', 3306, $errno, $errstr, 5);
if ($fp) {
    echo "<p><span class='success'>✓ MySQL is running on port 3306</span></p>";
    $mysql_running = true;
    fclose($fp);
} else {
    echo "<p><span class='error'>✗ MySQL not responding on port 3306</span></p>";
}
echo "</div>";

// 8. Fixes and Recommendations
echo "<div class='section'>";
echo "<h2>8. Fixes and Recommendations</h2>";

if (!$apache_running || !$mysql_running) {
    echo "<p><span class='error'>⚠ XAMPP Services Issue Detected</span></p>";
    echo "<div class='code'>";
    echo "Steps to fix:<br>";
    echo "1. Open XAMPP Control Panel as Administrator<br>";
    echo "2. Stop all services<br>";
    echo "3. Start Apache first<br>";
    echo "4. Start MySQL second<br>";
    echo "5. Check for error messages in the logs";
    echo "</div>";
}

echo "<h3>Common PHP Path Issues:</h3>";
echo "<ul>";
echo "<li><strong>Issue:</strong> 'php' is not recognized as an internal or external command</li>";
echo "<li><strong>Fix:</strong> Add C:\\xampp\\php to your system PATH</li>";
echo "<li><strong>Issue:</strong> Apache can't find PHP module</li>";
echo "<li><strong>Fix:</strong> Ensure LoadModule php_module exists in httpd.conf</li>";
echo "<li><strong>Issue:</strong> PHP extensions not loading</li>";
echo "<li><strong>Fix:</strong> Check php.ini and uncomment required extensions</li>";
echo "</ul>";
echo "</div>";

// 9. Quick Fix Scripts
echo "<div class='section'>";
echo "<h2>9. Quick Fix Scripts</h2>";
echo "<p><a href='fix_php_path.php' class='btn'>Run PHP Path Fix</a> ";
echo "<a href='setup_database.php' class='btn'>Setup Database</a> ";
echo "<a href='test_system.php' class='btn'>Test System</a></p>";
echo "</div>";

// 10. Environment Variables
echo "<div class='section'>";
echo "<h2>10. Environment Variables</h2>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>SERVER_SOFTWARE:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>PHP_SELF:</strong> " . $_SERVER['PHP_SELF'] . "</p>";
echo "</div>";

echo "</div></body></html>";
?> 