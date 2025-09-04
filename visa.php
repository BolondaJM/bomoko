<?php
session_start();
$user_name = '';
if (isset($_SESSION['user_email'])) {
    require_once 'db_connect.php';
    $email = $_SESSION['user_email'];
    $stmt = $pdo->prepare('SELECT first_name FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    $user_name = $user ? $user['first_name'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Visa Application</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="messages.php">📧 Messages</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        <?php if ($user_name): ?>
        <div style="margin-left:auto;display:flex;align-items:center;gap:1rem;">
            <span style="font-weight:600;"><?= htmlspecialchars($user_name) ?></span>
            <a href="logout.php" style="color:#fff;text-decoration:underline;">Logout</a>
        </div>
        <?php endif; ?>
    </header>
    <main>
        <div class="form-container">
            <div class="form-header">
                <h2>Visa Application Form</h2>
                <p>Complete this form to apply for your visa to the Democratic Republic of Congo</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <?php 
                    $error = $_GET['error'];
                    switch($error) {
                        case 'missing_fields':
                            echo 'Please fill in all required fields.';
                            break;
                        case 'database_error':
                            echo 'An error occurred while submitting your application. Please try again.';
                            break;
                        default:
                            echo 'An error occurred. Please try again.';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <form id="visaForm" method="POST" action="visa_submit.php">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="country">Country of Origin *</label>
                            <input type="text" id="country" name="country" placeholder="e.g., Kenya, Uganda, Tanzania" required>
                        </div>
                        <div class="form-group">
                            <label for="passport_number">Passport Number *</label>
                            <input type="text" id="passport_number" name="passport_number" placeholder="Enter your passport number" required>
                        </div>
                    </div>
                </div>

                <!-- Visa Details Section -->
                <div class="form-section">
                    <h3>Visa Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="visa_type">Visa Type Requesting *</label>
                            <select id="visa_type" name="visa_type" required>
                                <option value="">Select Visa Type</option>
                                <option value="student">Student Visa</option>
                                <option value="work_permit">Work Permit</option>
                                <option value="permanent_residence">Permanent Residence</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration of Stay *</label>
                            <input type="text" id="duration" name="duration" placeholder="e.g., 3 months, 1 year" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="travel_date">Intended Travel Date *</label>
                            <input type="date" id="travel_date" name="travel_date" required>
                        </div>
                    </div>
                    <div class="form-row full-width">
                        <div class="form-group">
                            <label for="reason">Reason for Travel *</label>
                            <textarea id="reason" name="reason" placeholder="Please provide a detailed explanation of your purpose of travel..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Submit Application</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <script>
    function validateTravelDate(event) {
        const travelInput = document.querySelector('input[name="travel_date"]');
        if (travelInput) {
            const travelDate = new Date(travelInput.value);
            const today = new Date();
            today.setHours(0,0,0,0);
            if (!travelInput.value || travelDate <= today) {
                alert('Travel date must be after today.');
                event.preventDefault();
                return false;
            }
        }
        return true;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('visaForm');
        if (form) {
            form.addEventListener('submit', validateTravelDate);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
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