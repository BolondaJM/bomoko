<?php
echo "<h2>🔧 Fixing Messages System</h2>";

try {
    require_once 'db_connect.php';
    
    // Step 1: Create messages table if it doesn't exist
    echo "<h3>Step 1: Creating Messages Table</h3>";
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
    echo "<p style='color: green;'>✅ Messages table created/verified</p>";
    
    // Step 2: Check if there are any existing messages
    echo "<h3>Step 2: Checking Existing Messages</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
    $messageCount = $stmt->fetch()['count'];
    echo "<p>Total messages in database: <strong>$messageCount</strong></p>";
    
    if ($messageCount > 0) {
        $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
        $recentMessages = $stmt->fetchAll();
        echo "<h4>Recent Messages:</h4>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>From</th><th>To</th><th>Subject</th><th>Created</th></tr>";
        foreach ($recentMessages as $msg) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($msg['id']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['from_email']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['to_email']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['subject']) . "</td>";
            echo "<td>" . htmlspecialchars($msg['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Step 3: Check incident reports to see what emails are available
    echo "<h3>Step 3: Checking Incident Reports</h3>";
    $stmt = $pdo->query("SELECT DISTINCT email FROM incident_reports WHERE email IS NOT NULL AND email != ''");
    $incidentEmails = $stmt->fetchAll();
    echo "<p>Emails from incident reports: <strong>" . count($incidentEmails) . "</strong></p>";
    
    if (!empty($incidentEmails)) {
        echo "<h4>Available Emails:</h4>";
        echo "<ul>";
        foreach ($incidentEmails as $email) {
            echo "<li>" . htmlspecialchars($email['email']) . "</li>";
        }
        echo "</ul>";
    }
    
    // Step 4: Test message insertion
    echo "<h3>Step 4: Testing Message System</h3>";
    if (!empty($incidentEmails)) {
        $testEmail = $incidentEmails[0]['email'];
        $testFrom = 'admin@drc-embassy.com';
        $testSubject = 'Test Message - ' . date('Y-m-d H:i:s');
        $testMessage = 'This is a test message to verify the messaging system is working correctly.';
        
        $stmt = $pdo->prepare('INSERT INTO messages (from_email, to_email, subject, message) VALUES (?, ?, ?, ?)');
        $result = $stmt->execute([$testFrom, $testEmail, $testSubject, $testMessage]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Test message sent to: <strong>$testEmail</strong></p>";
            
            // Verify the message was inserted
            $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? AND subject = ? ORDER BY created_at DESC LIMIT 1');
            $stmt->execute([$testEmail, $testSubject]);
            $insertedMessage = $stmt->fetch();
            
            if ($insertedMessage) {
                echo "<p style='color: green;'>✅ Message verified in database (ID: " . $insertedMessage['id'] . ")</p>";
                
                // Test retrieving messages for this user
                $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? ORDER BY created_at DESC');
                $stmt->execute([$testEmail]);
                $userMessages = $stmt->fetchAll();
                echo "<p style='color: green;'>✅ User has <strong>" . count($userMessages) . "</strong> messages</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Failed to insert test message</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ No incident reports found to test with</p>";
    }
    
    echo "<br><strong>🎉 Messages system setup completed!</strong><br>";
    echo "<p><a href='admin.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px;'>Go to Admin Dashboard</a></p>";
    echo "<p><a href='messages.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px;'>Go to Messages</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running in XAMPP Control Panel.</p>";
}
?>
