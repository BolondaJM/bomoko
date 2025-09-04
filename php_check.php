<?php
echo "<h2>PHP Configuration Check</h2>";
echo "<h3>PHP Version and Location</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP Executable: " . PHP_BINARY . "<br>";
echo "PHP SAPI: " . php_sapi_name() . "<br>";

echo "<h3>Required Extensions</h3>";
$required_extensions = ['pdo', 'pdo_mysql', 'session'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? "✓ Loaded" : "✗ Not Loaded";
    echo "$ext: $status<br>";
}

echo "<h3>Database Connection Test</h3>";
try {
    $host = 'localhost';
    $db   = 'bomoko_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✓ Database connection successful!<br>";
    
    // Test if tables exist
    $tables = ['users', 'visa_applications', 'incident_reports', 'visa_documents'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        $status = $exists ? "✓ Exists" : "✗ Missing";
        echo "Table '$table': $status<br>";
    }
    
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "<br>";
}

echo "<h3>File Permissions</h3>";
$upload_dir = 'uploads/';
if (is_dir($upload_dir)) {
    echo "✓ Uploads directory exists<br>";
    echo "Uploads directory writable: " . (is_writable($upload_dir) ? "✓ Yes" : "✗ No") . "<br>";
} else {
    echo "✗ Uploads directory missing<br>";
}

echo "<h3>XAMPP Configuration</h3>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

echo "<h3>PHP Configuration</h3>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
?> 