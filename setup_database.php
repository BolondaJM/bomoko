<?php
echo "<h2>Database Setup</h2>";

try {
    // First connect without database to create it if needed
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS bomoko_db");
    echo "✓ Database 'bomoko_db' created/verified<br>";
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=bomoko_db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100),
        middle_name VARCHAR(100),
        last_name VARCHAR(100),
        gender VARCHAR(20),
        dob DATE,
        place_of_birth VARCHAR(100),
        passport_number VARCHAR(50),
        visa_type VARCHAR(50),
        phone VARCHAR(30),
        email VARCHAR(100) UNIQUE,
        residential_address VARCHAR(255),
        county VARCHAR(50),
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_admin TINYINT(1) DEFAULT 0
    )";
    $pdo->exec($sql);
    echo "✓ Table 'users' created/verified<br>";
    
    $sql = "
    CREATE TABLE IF NOT EXISTS visa_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100),
        email VARCHAR(100),
        gender VARCHAR(20),
        dob DATE,
        country VARCHAR(100),
        passport_number VARCHAR(50),
        visa_type VARCHAR(50),
        duration VARCHAR(50),
        travel_date DATE,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✓ Table 'visa_applications' created/verified<br>";
    
    $sql = "
    CREATE TABLE IF NOT EXISTS incident_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100),
        email VARCHAR(100),
        phone VARCHAR(30),
        incident_type VARCHAR(50),
        description TEXT,
        incident_date DATE,
        incident_address VARCHAR(255),
        reported_police ENUM('yes','no'),
        ob_number VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✓ Table 'incident_reports' created/verified<br>";
    
    $sql = "
    CREATE TABLE IF NOT EXISTS visa_documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100),
        passport_pdf VARCHAR(255),
        supporting_pdf VARCHAR(255),
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✓ Table 'visa_documents' created/verified<br>";
    
    echo "<br><strong>✓ Database setup completed successfully!</strong><br>";
    echo "<a href='index.php'>Go to Homepage</a> | <a href='php_check.php'>Check Configuration</a>";
    
} catch (PDOException $e) {
    echo "✗ Database setup failed: " . $e->getMessage() . "<br>";
    echo "Please ensure MySQL is running in XAMPP Control Panel.<br>";
}
?> 