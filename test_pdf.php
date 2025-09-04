<?php
require_once 'generate_visa_pdf.php';

// Test data
$testData = [
    'full_name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'gender' => 'male',
    'dob' => '1990-05-15',
    'country' => 'Kenya',
    'passport_number' => 'A12345678',
    'visa_type' => 'student',
    'duration' => '2 years',
    'travel_date' => '2024-09-01',
    'reason' => 'I am applying for a student visa to study Computer Science at the University of Kinshasa. This program will help me gain valuable skills and contribute to the development of technology in the DRC.'
];

// Handle download request
if (isset($_GET['download'])) {
    downloadVisaPDF($testData);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Generation Test - DRC Embassy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #003366;
            text-align: center;
        }
        .test-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .test-info h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .btn-test {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-test:hover {
            background: #218838;
        }
        .test-data {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .test-data h3 {
            margin-top: 0;
            color: #333;
        }
        .test-data ul {
            margin: 0;
            padding-left: 20px;
        }
        .test-data li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>PDF Generation Test</h1>
        
        <div class="test-info">
            <h3>Test Instructions:</h3>
            <p>This page tests the PDF generation functionality for visa applications. Click the button below to download a sample visa application summary.</p>
        </div>
        
        <div class="test-data">
            <h3>Test Data:</h3>
            <ul>
                <li><strong>Name:</strong> John Doe</li>
                <li><strong>Email:</strong> john.doe@example.com</li>
                <li><strong>Gender:</strong> Male</li>
                <li><strong>Date of Birth:</strong> 1990-05-15</li>
                <li><strong>Country:</strong> Kenya</li>
                <li><strong>Passport:</strong> A12345678</li>
                <li><strong>Visa Type:</strong> Student Visa</li>
                <li><strong>Duration:</strong> 2 years</li>
                <li><strong>Travel Date:</strong> 2024-09-01</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="?download=1" class="btn-test">📄 Download Test PDF</a>
            <a href="visa.php" class="btn-test">📝 Go to Visa Form</a>
            <a href="index.php" class="btn-test">🏠 Back to Home</a>
        </div>
        
        <div class="test-info">
            <h3>Expected Result:</h3>
            <p>When you click "Download Test PDF", you should:</p>
            <ol>
                <li>Get a file download prompt for an HTML file</li>
                <li>The file will contain a formatted visa application summary</li>
                <li>You can open the file in a browser and print it as PDF</li>
                <li>The summary will include all the test data above</li>
            </ol>
        </div>
    </div>
</body>
</html>
