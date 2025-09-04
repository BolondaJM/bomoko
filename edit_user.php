<?php
session_start();
require_once 'db_connect.php';

// Check if current user is admin
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
$current_email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE email = ?');
$stmt->execute([$current_email]);
$current_user = $stmt->fetch();
if (!$current_user || empty($current_user['is_admin'])) {
    echo '<div style="margin:2rem auto;max-width:400px;padding:2rem;background:#fff3f3;border:1px solid #e00;color:#a00;text-align:center;border-radius:8px;">Access denied: Only admins can edit users.<br><a href="index.php">Back to Home</a></div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $place_of_birth = $_POST['place_of_birth'];
    $passport_number = $_POST['passport_number'];
    $visa_type = $_POST['visa_type'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $residential_address = $_POST['residential_address'];
    $county = $_POST['county'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Prevent admin from removing their own admin privileges
    $stmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $target_user = $stmt->fetch();
    
    if ($target_user && $target_user['email'] === $current_email && $is_admin == 0) {
        header('Location: admin.php?error=self_removal');
        exit();
    }
    
    // Validate password if provided
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            header('Location: edit_user.php?id=' . $id . '&error=password_length');
            exit();
        }
        if (!preg_match('/[A-Za-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            header('Location: edit_user.php?id=' . $id . '&error=password_complexity');
            exit();
        }
        if ($new_password !== $confirm_password) {
            header('Location: edit_user.php?id=' . $id . '&error=password_mismatch');
            exit();
        }
        // Hash the new password
        $hashed_password = hash('sha256', $new_password);
        
        // Update with new password
        $stmt = $pdo->prepare('UPDATE users SET first_name=?, middle_name=?, last_name=?, gender=?, dob=?, place_of_birth=?, passport_number=?, visa_type=?, phone=?, email=?, residential_address=?, county=?, is_admin=?, password=? WHERE id=?');
        $stmt->execute([$first_name, $middle_name, $last_name, $gender, $dob, $place_of_birth, $passport_number, $visa_type, $phone, $email, $residential_address, $county, $is_admin, $hashed_password, $id]);
    } else {
        // Update without changing password
        $stmt = $pdo->prepare('UPDATE users SET first_name=?, middle_name=?, last_name=?, gender=?, dob=?, place_of_birth=?, passport_number=?, visa_type=?, phone=?, email=?, residential_address=?, county=?, is_admin=? WHERE id=?');
        $stmt->execute([$first_name, $middle_name, $last_name, $gender, $dob, $place_of_birth, $passport_number, $visa_type, $phone, $email, $residential_address, $county, $is_admin, $id]);
    }
    
    header('Location: admin.php?success=user_updated');
    exit();
} else {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) { echo 'User not found.'; exit(); }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .admin-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            margin: 1rem 0;
        }
        .admin-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .admin-checkbox input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        .btn {
            background: #007cba;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 1rem;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Edit User</h1>
    </header>
    <main>
        <div class="form-container">
            <h2>Edit User Information</h2>
            
            <?php
            // Display error messages if any
            if (isset($_GET['error'])) {
                $error_message = '';
                switch ($_GET['error']) {
                    case 'password_length':
                        $error_message = 'Password must be at least 8 characters long.';
                        break;
                    case 'password_complexity':
                        $error_message = 'Password must contain both letters and numbers.';
                        break;
                    case 'password_mismatch':
                        $error_message = 'Password and confirm password do not match.';
                        break;
                    default:
                        $error_message = 'An error occurred during the update.';
                }
                if ($error_message) {
                    echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb;">';
                    echo htmlspecialchars($error_message);
                    echo '</div>';
                }
            }
            ?>
            
            <form method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?= $user['gender']==='male'?'selected':'' ?>>Male</option>
                            <option value="female" <?= $user['gender']==='female'?'selected':'' ?>>Female</option>
                            <option value="other" <?= $user['gender']==='other'?'selected':'' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="place_of_birth">Place of Birth *</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" value="<?= htmlspecialchars($user['place_of_birth']) ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="passport_number">Passport Number *</label>
                        <input type="text" id="passport_number" name="passport_number" value="<?= htmlspecialchars($user['passport_number']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="visa_type">Visa Type *</label>
                        <select id="visa_type" name="visa_type" required>
                            <option value="">Select Visa Type</option>
                            <option value="tourist" <?= $user['visa_type']==='tourist'?'selected':'' ?>>Tourist</option>
                            <option value="student" <?= $user['visa_type']==='student'?'selected':'' ?>>Student</option>
                            <option value="work_permit" <?= $user['visa_type']==='work_permit'?'selected':'' ?>>Work Permit</option>
                            <option value="permanent_residence" <?= $user['visa_type']==='permanent_residence'?'selected':'' ?>>Permanent Residence</option>
                            <option value="other" <?= $user['visa_type']==='other'?'selected':'' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="residential_address">Residential Address *</label>
                    <input type="text" id="residential_address" name="residential_address" value="<?= htmlspecialchars($user['residential_address']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="county">County *</label>
                    <input type="text" id="county" name="county" value="<?= htmlspecialchars($user['county']) ?>" required>
                </div>
                
                <div class="admin-section">
                    <h3>Password Management</h3>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">
                        Leave password fields empty if you don't want to change the user's password.
                    </p>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password (min 8 chars, letters + numbers)">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter new password">
                        </div>
                    </div>
                    <div style="background: #e3f2fd; padding: 0.75rem; border-radius: 4px; margin-top: 0.5rem;">
                        <p style="font-size: 0.85rem; color: #1976d2; margin: 0;">
                            <strong>Password Requirements:</strong> Minimum 8 characters, must contain both letters and numbers.
                        </p>
                    </div>
                </div>
                
                <div class="admin-section">
                    <h3>Admin Privileges</h3>
                    <div class="admin-checkbox">
                        <input type="checkbox" name="is_admin" id="is_admin" value="1" <?= $user['is_admin'] ? 'checked' : '' ?>>
                        <label for="is_admin">Grant Admin Privileges</label>
                    </div>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">
                        Check this box to give this user admin access to the system. Admins can manage users, view reports, and access the admin dashboard.
                    </p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">Update User</button>
                    <a href="admin.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi</p>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const form = document.querySelector('form');
        
        // Password validation function
        function validatePassword(password) {
            if (password.length < 8) {
                return 'Password must be at least 8 characters long.';
            }
            if (!/[A-Za-z]/.test(password)) {
                return 'Password must contain at least one letter.';
            }
            if (!/[0-9]/.test(password)) {
                return 'Password must contain at least one number.';
            }
            return null;
        }
        
        // Real-time password validation
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const error = validatePassword(password);
            
            // Remove existing error styling
            this.style.borderColor = '#ddd';
            const existingError = this.parentNode.querySelector('.password-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Add error styling and message if validation fails
            if (error && password.length > 0) {
                this.style.borderColor = '#dc3545';
                const errorDiv = document.createElement('div');
                errorDiv.className = 'password-error';
                errorDiv.style.color = '#dc3545';
                errorDiv.style.fontSize = '0.8rem';
                errorDiv.style.marginTop = '0.25rem';
                errorDiv.textContent = error;
                this.parentNode.appendChild(errorDiv);
            }
        });
        
        // Confirm password validation
        confirmPasswordInput.addEventListener('input', function() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = this.value;
            
            // Remove existing error styling
            this.style.borderColor = '#ddd';
            const existingError = this.parentNode.querySelector('.password-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Add error styling and message if passwords don't match
            if (confirmPassword.length > 0 && newPassword !== confirmPassword) {
                this.style.borderColor = '#dc3545';
                const errorDiv = document.createElement('div');
                errorDiv.className = 'password-error';
                errorDiv.style.color = '#dc3545';
                errorDiv.style.fontSize = '0.8rem';
                errorDiv.style.marginTop = '0.25rem';
                errorDiv.textContent = 'Passwords do not match.';
                this.parentNode.appendChild(errorDiv);
            }
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // If password fields are filled, validate them
            if (newPassword.length > 0 || confirmPassword.length > 0) {
                const passwordError = validatePassword(newPassword);
                if (passwordError) {
                    e.preventDefault();
                    alert('Password Error: ' + passwordError);
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Password Error: Passwords do not match.');
                    return;
                }
            }
        });
    });
    </script>
    <script src="assets/chatbot.js"></script>
</body>
</html> 