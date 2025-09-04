<?php
echo "<h2>Setting up Messages System</h2>";
echo "<p>Creating the messages table in the database...</p>";

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
    echo "<p style='color: green; font-weight: bold;'>✅ Messages table created successfully!</p>";
    
    // Verify the table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Table verification successful</p>";
        
        // Show table structure
        echo "<h3>Table Structure:</h3>";
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
        
        echo "<p style='color: green; font-weight: bold;'>🎉 Messages system is now ready!</p>";
        echo "<p><a href='messages.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Messages</a></p>";
        echo "<p><a href='admin.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Dashboard</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Table creation failed</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure:</p>";
    echo "<ul>";
    echo "<li>MySQL is running in XAMPP Control Panel</li>";
    echo "<li>Database 'bomoko_db' exists</li>";
    echo "<li>Database connection is properly configured</li>";
    echo "</ul>";
}
?>
