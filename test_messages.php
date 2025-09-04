<?php
echo "<h2>Testing Messages System</h2>";

try {
    require_once 'db_connect.php';
    
    // Check if messages table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p style='color: red;'><strong>❌ Messages table does not exist!</strong></p>";
        echo "<p>Creating messages table...</p>";
        
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
        echo "<p style='color: green;'>✅ Messages table created successfully!</p>";
    } else {
        echo "<p style='color: green;'>✅ Messages table exists</p>";
    }
    
    // Test inserting a message
    echo "<h3>Testing Message Insertion:</h3>";
    $testFrom = 'admin@drc-embassy.com';
    $testTo = 'test@example.com';
    $testSubject = 'Test Message - ' . date('Y-m-d H:i:s');
    $testMessage = 'This is a test message from the DRC Embassy.';
    
    $stmt = $pdo->prepare('INSERT INTO messages (from_email, to_email, subject, message) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$testFrom, $testTo, $testSubject, $testMessage]);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Test message inserted successfully</p>";
        
        // Test retrieving messages for the test user
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? ORDER BY created_at DESC');
        $stmt->execute([$testTo]);
        $userMessages = $stmt->fetchAll();
        
        echo "<p><strong>Messages for $testTo:</strong> " . count($userMessages) . " found</p>";
        
        if (!empty($userMessages)) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Subject</th><th>From</th><th>Read</th><th>Created</th></tr>";
            foreach ($userMessages as $msg) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($msg['id']) . "</td>";
                echo "<td>" . htmlspecialchars($msg['subject']) . "</td>";
                echo "<td>" . htmlspecialchars($msg['from_email']) . "</td>";
                echo "<td>" . ($msg['is_read'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . htmlspecialchars($msg['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Clean up test message
        $stmt = $pdo->prepare('DELETE FROM messages WHERE to_email = ? AND subject = ?');
        $stmt->execute([$testTo, $testSubject]);
        echo "<p style='color: blue;'>🧹 Test message cleaned up</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to insert test message</p>";
    }
    
    echo "<br><strong>✓ Messages system test completed!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a> | <a href='messages.php'>Go to Messages</a>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running in XAMPP Control Panel.</p>";
}
?>
