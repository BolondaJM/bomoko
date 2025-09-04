<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (!isset($_FILES['passport_pdf']) || !isset($_FILES['supporting_pdf'])) {
        header('Location: visa_upload.php?error=1');
        exit();
    }
    $passport_pdf = $_FILES['passport_pdf'];
    $supporting_pdf = $_FILES['supporting_pdf'];
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $passport_path = $upload_dir . uniqid('passport_') . '.pdf';
    $supporting_path = $upload_dir . uniqid('support_') . '.pdf';
    move_uploaded_file($passport_pdf['tmp_name'], $passport_path);
    move_uploaded_file($supporting_pdf['tmp_name'], $supporting_path);
    // Save file info to DB
    $stmt = $pdo->prepare('INSERT INTO visa_documents (email, passport_pdf, supporting_pdf) VALUES (?, ?, ?)');
    $stmt->execute([$email, $passport_path, $supporting_path]);
    header('Location: visa_complete.php');
    exit();
} else {
    header('Location: visa_upload.php');
    exit();
} 