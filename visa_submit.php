<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $country = $_POST['country'] ?? '';
    $passport_number = $_POST['passport_number'] ?? '';
    $visa_type = $_POST['visa_type'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $reason = $_POST['reason'] ?? '';

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($gender) || empty($dob) || 
        empty($country) || empty($passport_number) || empty($visa_type) || 
        empty($duration) || empty($travel_date) || empty($reason)) {
        header('Location: visa.php?error=missing_fields');
        exit();
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO visa_applications (full_name, email, gender, dob, country, passport_number, visa_type, duration, travel_date, reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$full_name, $email, $gender, $dob, $country, $passport_number, $visa_type, $duration, $travel_date, $reason]);
        
        // Redirect to success page
        header('Location: visa_success.php');
        exit();
        
    } catch (PDOException $e) {
        header('Location: visa.php?error=database_error');
        exit();
    }
} else {
    header('Location: visa.php');
    exit();
} 