<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, last_name, email, phone FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
$user_name = $user ? $user['first_name'] : '';
$full_name = $user ? trim($user['first_name'] . ' ' . $user['last_name']) : '';
$user_email = $user ? $user['email'] : '';
$user_phone = $user ? $user['phone'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        input[readonly] {
            background-color: #f5f5f5;
            color: #666;
            cursor: not-allowed;
            border: 1px solid #ddd;
        }
        input[readonly]:focus {
            outline: none;
            border-color: #ddd;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Incident Report</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
                <li><a href="messages.php">📧 Messages</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        <div style="margin-left:auto;display:flex;align-items:center;gap:1rem;">
            <span style="font-weight:600;"><?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php" style="color:#fff;text-decoration:underline;">Logout</a>
        </div>
    </header>
    <main>
        <div class="form-container">
            <div class="form-header">
                <h2>Report an Incident</h2>
                <p>Please provide detailed information about the incident you experienced</p>
                <div style="background-color: #e3f2fd; color: #1976d2; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bbdefb; font-size: 14px;">
                    <strong>Note:</strong> Your personal information (name, email, and phone) has been automatically filled from your account details and cannot be modified.
                </div>
            </div>
            
            <form method="POST" action="incident_submit.php">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($full_name); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="your.email@example.com" required value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" placeholder="+254 712 345 678" required value="<?php echo htmlspecialchars($user_phone); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="incident_type">Incident Type *</label>
                            <select id="incident_type" name="incident_type" required>
                                <option value="">Select Incident Type</option>
                                <option value="lost_passport">Lost Passport</option>
                                <option value="legal_issue">Legal Issue</option>
                                <option value="medical">Medical Emergency</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Incident Details Section -->
                <div class="form-section">
                    <h3>Incident Details</h3>
                    <div class="form-row full-width">
                        <div class="form-group">
                            <label for="description">Description of Incident *</label>
                            <textarea id="description" name="description" placeholder="Please provide a detailed description of what happened..." required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="incident_date">Date of Incident *</label>
                            <input type="date" id="incident_date" name="incident_date" required>
                        </div>
                        <div class="form-group">
                            <label for="incident_address">Incident Location *</label>
                            <input type="text" id="incident_address" name="incident_address" placeholder="Street, City, County" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="reported_police">Reported to Police? *</label>
                            <select id="reported_police" name="reported_police" required>
                                <option value="">Select Yes/No</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group" id="ob_number_group" style="display: none;">
                            <label for="ob_number">OB Number *</label>
                            <input type="text" id="ob_number" name="ob_number" placeholder="Enter the OB Number from police report">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Submit Report</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi</p>
    </footer>
    <script>
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('success') === '1') {
            const msg = document.createElement('div');
            msg.className = 'message success';
            msg.textContent = 'Incident report submitted successfully!';
            document.querySelector('.form-container').insertBefore(msg, document.querySelector('.form-header'));
        }
        
        // Handle dynamic OB Number field display
        const reportedPoliceSelect = document.getElementById('reported_police');
        const obNumberGroup = document.getElementById('ob_number_group');
        const obNumberInput = document.getElementById('ob_number');
        
        if (reportedPoliceSelect) {
            reportedPoliceSelect.addEventListener('change', function() {
                if (this.value === 'yes') {
                    obNumberGroup.style.display = 'block';
                    obNumberInput.required = true;
                } else {
                    obNumberGroup.style.display = 'none';
                    obNumberInput.required = false;
                    obNumberInput.value = ''; // Clear the value when hiding
                }
            });
        }
        
        var userDropdown = document.getElementById('userDropdown');
        var dropdownMenu = document.getElementById('dropdownMenu');
        if (userDropdown) {
            userDropdown.addEventListener('click', function(e) {
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
                e.stopPropagation();
            });
            document.addEventListener('click', function() {
                dropdownMenu.style.display = 'none';
            });
        }
    });
    </script>
    <script src="assets/chatbot.js"></script>
</body>
</html> 