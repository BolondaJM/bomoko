<?php
echo "<h2>Adding Status Column to Visa Applications</h2>";

try {
    require_once 'db_connect.php';
    
    // Check if status column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM visa_applications LIKE 'status'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        echo "✗ Status column does not exist. Adding it...<br>";
        $sql = "ALTER TABLE visa_applications ADD COLUMN status VARCHAR(20) DEFAULT 'pending' AFTER reason";
        $pdo->exec($sql);
        echo "✓ Status column added successfully<br>";
    } else {
        echo "✓ Status column already exists<br>";
    }
    
    // Check if created_at column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM visa_applications LIKE 'created_at'");
    $createdAtExists = $stmt->rowCount() > 0;
    
    if (!$createdAtExists) {
        echo "✗ created_at column does not exist. Adding it...<br>";
        $sql = "ALTER TABLE visa_applications ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $pdo->exec($sql);
        echo "✓ created_at column added successfully<br>";
    } else {
        echo "✓ created_at column already exists<br>";
    }
    
    // Update existing records to have 'pending' status if they don't have one
    $stmt = $pdo->query("UPDATE visa_applications SET status = 'pending' WHERE status IS NULL");
    $affected = $stmt->rowCount();
    if ($affected > 0) {
        echo "✓ Updated $affected existing records with 'pending' status<br>";
    }
    
    echo "<br><strong>✓ Database update completed successfully!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a>";
    
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
    echo "Please ensure MySQL is running in XAMPP Control Panel.<br>";
}
?>
