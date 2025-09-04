<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $place_of_birth = $_POST['place_of_birth'] ?? '';
    $passport_number = $_POST['passport_number'] ?? '';
    $visa_type = $_POST['visa_type'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $residential_address = $_POST['residential_address'] ?? '';
    $county = $_POST['county'] ?? '';
    $password = $_POST['password_hash'] ?? '';

    // Server-side validation for names - only letters and spaces allowed
    $name_pattern = '/^[A-Za-z\s]+$/';
    
    if (!preg_match($name_pattern, trim($first_name))) {
        header('Location: register.php?error=invalid_first_name');
        exit();
    }
    
    if (!preg_match($name_pattern, trim($last_name))) {
        header('Location: register.php?error=invalid_last_name');
        exit();
    }
    
    // Validate middle name if provided (optional field)
    if (!empty(trim($middle_name)) && !preg_match($name_pattern, trim($middle_name))) {
        header('Location: register.php?error=invalid_middle_name');
        exit();
    }

    $stmt = $pdo->prepare('INSERT INTO users (first_name, middle_name, last_name, gender, dob, place_of_birth, passport_number, visa_type, phone, email, residential_address, county, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$first_name, $middle_name, $last_name, $gender, $dob, $place_of_birth, $passport_number, $visa_type, $phone, $email, $residential_address, $county, $password]);
    header('Location: registration_complete.php?success=1');
    exit();
} else {
    header('Location: register.php');
    exit();
} 