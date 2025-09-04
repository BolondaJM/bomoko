<?php
echo "<h2>Database Structure Test</h2>";

try {
    require_once 'db_connect.php';
    
    // Check table structure
    echo "<h3>Visa Applications Table Structure:</h3>";
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
    
    // Check if status column exists
    $statusExists = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'status') {
            $statusExists = true;
            break;
        }
    }
    
    if (!$statusExists) {
        echo "<p style='color: red;'><strong>❌ Status column does not exist!</strong></p>";
        echo "<p><a href='add_status_column.php'>Click here to add the status column</a></p>";
    } else {
        echo "<p style='color: green;'><strong>✅ Status column exists</strong></p>";
    }
    
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
        
        // Test update query
        if ($statusExists && count($applications) > 0) {
            echo "<h3>Testing Update Query:</h3>";
            $testId = $applications[0]['id'];
            $stmt = $pdo->prepare('UPDATE visa_applications SET status = ? WHERE id = ?');
            $result = $stmt->execute(['test_status', $testId]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Update query works successfully</p>";
                
                // Revert the test
                $stmt = $pdo->prepare('UPDATE visa_applications SET status = ? WHERE id = ?');
                $stmt->execute([$applications[0]['status'] ?? 'pending', $testId]);
                echo "<p>✅ Test reverted back to original status</p>";
            } else {
                echo "<p style='color: red;'>❌ Update query failed</p>";
            }
        }
    } else {
        echo "<p>No visa applications found in database</p>";
    }
    
    echo "<br><a href='admin.php'>Go to Admin Dashboard</a>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running in XAMPP Control Panel.</p>";
}
?>
