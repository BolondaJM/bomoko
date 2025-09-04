<?php
echo "<h2>Creating Messages Table</h2>";

try {
    require_once 'db_connect.php';
    
    // Create messages table
    $sql = "
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_email VARCHAR(100),
        to_email VARCHAR(100),
        subject VARCHAR(255),
        message TEXT,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_to_email (to_email),
        INDEX idx_from_email (from_email),
        INDEX idx_created_at (created_at)
    )";
    
    $pdo->exec($sql);
    echo "✓ Messages table created successfully<br>";
    
    // Show table structure
    echo "<h3>Messages Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE messages");
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
    
    echo "<br><strong>✓ Messages system setup completed!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a>";
    
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
    echo "Please ensure MySQL is running in XAMPP Control Panel.<br>";
}
?>
