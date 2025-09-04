<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';

// Check if user is admin (same method as admin.php)
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || empty($user['is_admin'])) {
    header('Location: admin.php?error=access_denied');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? 'approve';
    
    if (!$id) {
        header('Location: admin.php?error=invalid_id');
        exit();
    }
    
    // Validate action
    if (!in_array($action, ['approve', 'reject'])) {
        header('Location: admin.php?error=invalid_action');
        exit();
    }
    
    try {
        // First check if the application exists
        $stmt = $pdo->prepare('SELECT id, full_name, email FROM visa_applications WHERE id = ?');
        $stmt->execute([$id]);
        $application = $stmt->fetch();
        
        if (!$application) {
            header('Location: admin.php?error=application_not_found');
            exit();
        }
        
        // Update the visa application status
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $stmt = $pdo->prepare('UPDATE visa_applications SET status = ? WHERE id = ?');
        $result = $stmt->execute([$status, $id]);
        
        if ($result) {
            // Redirect with success message
            $success_message = ($action === 'approve') ? 'application_approved' : 'application_rejected';
            header('Location: admin.php?success=' . $success_message . '&applicant=' . urlencode($application['full_name']));
        } else {
            header('Location: admin.php?error=database_error');
        }
        
    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Visa validation error: " . $e->getMessage());
        header('Location: admin.php?error=database_error');
    }
} else {
    header('Location: admin.php');
}
exit(); 