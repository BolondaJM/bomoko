<?php
echo "<h2>Testing Messaging System</h2>";

try {
    require_once 'db_connect.php';
    
    // Check if messages table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p style='color: red;'><strong>❌ Messages table does not exist!</strong></p>";
        echo "<p><a href='create_messages_table.php'>Click here to create the messages table</a></p>";
    } else {
        echo "<p style='color: green;'><strong>✅ Messages table exists</strong></p>";
        
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
        
        // Test inserting a message
        echo "<h3>Testing Message Insertion:</h3>";
        $testFrom = 'admin@drc-embassy.com';
        $testTo = 'test@example.com';
        $testSubject = 'Test Message';
        $testMessage = 'This is a test message from the DRC Embassy.';
        
        $stmt = $pdo->prepare('INSERT INTO messages (from_email, to_email, subject, message) VALUES (?, ?, ?, ?)');
        $result = $stmt->execute([$testFrom, $testTo, $testSubject, $testMessage]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Test message inserted successfully</p>";
            
            // Get the inserted message
            $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? ORDER BY created_at DESC LIMIT 1');
            $stmt->execute([$testTo]);
            $message = $stmt->fetch();
            
            if ($message) {
                echo "<h4>Test Message Details:</h4>";
                echo "<p><strong>From:</strong> " . htmlspecialchars($message['from_email']) . "</p>";
                echo "<p><strong>To:</strong> " . htmlspecialchars($message['to_email']) . "</p>";
                echo "<p><strong>Subject:</strong> " . htmlspecialchars($message['subject']) . "</p>";
                echo "<p><strong>Message:</strong> " . htmlspecialchars($message['message']) . "</p>";
                echo "<p><strong>Created:</strong> " . htmlspecialchars($message['created_at']) . "</p>";
                echo "<p><strong>Read:</strong> " . ($message['is_read'] ? 'Yes' : 'No') . "</p>";
            }
            
            // Clean up test message
            $stmt = $pdo->prepare('DELETE FROM messages WHERE to_email = ? AND subject = ?');
            $stmt->execute([$testTo, $testSubject]);
            echo "<p style='color: blue;'>🧹 Test message cleaned up</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to insert test message</p>";
        }
    }
    
    echo "<br><strong>✓ Messaging system test completed!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a> | <a href='messages.php'>Go to Messages</a>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running in XAMPP Control Panel.</p>";
}
?>
