<?php
echo "<h2>Debugging Messages System</h2>";

try {
    require_once 'db_connect.php';
    
    // Check if messages table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p style='color: red;'><strong>❌ Messages table does not exist!</strong></p>";
        echo "<p><a href='setup_messages.php'>Click here to create the messages table</a></p>";
        exit();
    }
    
    echo "<p style='color: green;'><strong>✅ Messages table exists</strong></p>";
    
    // Show all messages in the database
    echo "<h3>All Messages in Database:</h3>";
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $allMessages = $stmt->fetchAll();
    
    if (empty($allMessages)) {
        echo "<p style='color: orange;'>📭 No messages found in database</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
        echo "<tr><th>ID</th><th>From</th><th>To</th><th>Subject</th><th>Message</th><th>Read</th><th>Created</th></tr>";
        foreach ($allMessages as $msg) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($msg['id']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['from_email']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['to_email']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['subject']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($msg['message'], 0, 50)) . "...</td>";
            echo "<td>" . ($msg['is_read'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . htmlspecialchars($msg['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
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
        
        // Get the inserted message
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? AND subject = ? ORDER BY created_at DESC LIMIT 1');
        $stmt->execute([$testTo, $testSubject]);
        $message = $stmt->fetch();
        
        if ($message) {
            echo "<h4>Test Message Details:</h4>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($message['id']) . "</p>";
            echo "<p><strong>From:</strong> " . htmlspecialchars($message['from_email']) . "</p>";
            echo "<p><strong>To:</strong> " . htmlspecialchars($message['to_email']) . "</p>";
            echo "<p><strong>Subject:</strong> " . htmlspecialchars($message['subject']) . "</p>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($message['message']) . "</p>";
            echo "<p><strong>Created:</strong> " . htmlspecialchars($message['created_at']) . "</p>";
            echo "<p><strong>Read:</strong> " . ($message['is_read'] ? 'Yes' : 'No') . "</p>";
        }
        
        // Test retrieving messages for a specific user
        echo "<h3>Testing Message Retrieval for User:</h3>";
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
    
    echo "<br><strong>✓ Messages system debug completed!</strong><br>";
    echo "<a href='admin.php'>Go to Admin Dashboard</a> | <a href='messages.php'>Go to Messages</a>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running in XAMPP Control Panel.</p>";
}
?>
