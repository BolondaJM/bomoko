<?php
session_start();
require_once 'db_connect.php';

echo "<h2>Authentication Debug Information</h2>";

// Check session
echo "<h3>Session Information:</h3>";
if (isset($_SESSION['user_email'])) {
    echo "✓ User email in session: " . htmlspecialchars($_SESSION['user_email']) . "<br>";
} else {
    echo "✗ No user email in session<br>";
}

if (isset($_SESSION['is_admin'])) {
    echo "✓ is_admin in session: " . ($_SESSION['is_admin'] ? 'true' : 'false') . "<br>";
} else {
    echo "✗ No is_admin in session<br>";
}

// Check database
echo "<h3>Database Information:</h3>";
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $stmt = $pdo->prepare('SELECT first_name, is_admin FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✓ User found in database<br>";
        echo "Name: " . htmlspecialchars($user['first_name']) . "<br>";
        echo "Admin status: " . ($user['is_admin'] ? 'Admin' : 'User') . "<br>";
        
        if ($user['is_admin']) {
            echo "✓ User has admin privileges<br>";
        } else {
            echo "✗ User does not have admin privileges<br>";
        }
    } else {
        echo "✗ User not found in database<br>";
    }
} else {
    echo "✗ Cannot check database - no email in session<br>";
}

echo "<h3>Actions:</h3>";
echo "<a href='admin.php'>Go to Admin Dashboard</a><br>";
echo "<a href='login.php'>Go to Login</a><br>";
echo "<a href='logout.php'>Logout</a><br>";

// Test review link
if (isset($_SESSION['user_email']) && $user && $user['is_admin']) {
    echo "<h3>Test Review Link:</h3>";
    // Get first visa application
    $stmt = $pdo->prepare('SELECT id FROM visa_applications LIMIT 1');
    $stmt->execute();
    $visa = $stmt->fetch();
    
    if ($visa) {
        echo "<a href='review_visa.php?id=" . $visa['id'] . "'>Test Review Visa Application</a><br>";
    } else {
        echo "No visa applications found to test with<br>";
    }
}
?>
