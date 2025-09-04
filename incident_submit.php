<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $incident_type = $_POST['incident_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $incident_date = $_POST['incident_date'] ?? '';
    $incident_address = $_POST['incident_address'] ?? '';
    $reported_police = $_POST['reported_police'] ?? '';
    $ob_number = $_POST['ob_number'] ?? '';

    $stmt = $pdo->prepare('INSERT INTO incident_reports (full_name, email, phone, incident_type, description, incident_date, incident_address, reported_police, ob_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$full_name, $email, $phone, $incident_type, $description, $incident_date, $incident_address, $reported_police, $ob_number]);
    header('Location: incident.php?success=1');
    exit();
} else {
    header('Location: incident.php');
    exit();
} 