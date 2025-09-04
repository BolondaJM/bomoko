<?php
echo "<h2>Fixing Visa Applications Table Structure</h2>";

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
    
    // Show current table structure
    echo "<h3>Current Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE visa_applications");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test the update query
    echo "<h3>Testing Update Query:</h3>";
    $stmt = $pdo->prepare('UPDATE visa_applications SET status = ? WHERE id = ?');
    echo "✓ Update query prepared successfully<br>";
    
    // Show sample data
    echo "<h3>Sample Visa Applications:</h3>";
    $stmt = $pdo->query("SELECT id, full_name, email, status FROM visa_applications LIMIT 5");
    $applications = $stmt->fetchAll();
    
    if (count($applications) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th></tr>";
        foreach ($applications as $app) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($app['id']) . "</td>";
            echo "<td>" . htmlspecialchars($app['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($app['email']) . "</td>";
            echo "<td>" . htmlspecialchars($app['status'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No visa applications found in database<br>";
    }
    
    echo "<br><strong>✓ Database structure check completed!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a>";
    
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
    echo "Please ensure MySQL is running in XAMPP Control Panel.<br>";
}
?>
