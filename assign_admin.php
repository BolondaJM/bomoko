<?php
session_start();
require_once 'db_connect.php';

// Check if current user is admin
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$current = $stmt->fetch();
if (!$current || empty($current['is_admin'])) {
    echo '<div style="margin:2rem auto;max-width:400px;padding:2rem;background:#fff3f3;border:1px solid #e00;color:#a00;text-align:center;border-radius:8px;">Access denied: Only admins can assign admin rights.<br><a href="index.php">Back to Home</a></div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $is_admin = $_POST['is_admin'] == '1' ? 1 : 0;
    
    // Prevent admin from removing their own admin privileges
    $stmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $target_user = $stmt->fetch();
    
    if ($target_user && $target_user['email'] === $email && $is_admin == 0) {
        header('Location: admin.php?error=self_removal');
        exit();
    }
    
    $stmt = $pdo->prepare('UPDATE users SET is_admin=? WHERE id=?');
    $stmt->execute([$is_admin, $id]);
    
    // Redirect with appropriate success message
    if ($is_admin == 1) {
        header('Location: admin.php?success=admin_assigned');
    } else {
        header('Location: admin.php?success=admin_removed');
    }
    exit();
}
header('Location: admin.php');
exit(); 