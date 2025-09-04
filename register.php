<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Register</h1>
    </header>
    <main>
        <div class="form-container">
            <div class="form-header">
                <h2>Create Your Account</h2>
                <p>Join the DRC Embassy Management System to access our services</p>
            </div>
            
            <?php
            // Display error messages if any
            if (isset($_GET['error'])) {
                $error_message = '';
                switch ($_GET['error']) {
                    case 'invalid_first_name':
                        $error_message = 'First name can only contain letters and spaces.';
                        break;
                    case 'invalid_last_name':
                        $error_message = 'Last name can only contain letters and spaces.';
                        break;
                    case 'invalid_middle_name':
                        $error_message = 'Middle name can only contain letters and spaces.';
                        break;
                    default:
                        $error_message = 'An error occurred during registration.';
                }
                if ($error_message) {
                    echo '<div class="error-message" style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffcdd2;">';
                    echo htmlspecialchars($error_message);
                    echo '</div>';
                }
            }
            ?>
            
            <form id="registrationForm" method="POST" action="register_submit.php">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" placeholder="Enter your middle name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="place_of_birth">Place of Birth *</label>
                            <input type="text" id="place_of_birth" name="place_of_birth" placeholder="City, Country" required>
                        </div>
                    </div>
                </div>

                <!-- Passport Information Section -->
                <div class="form-section">
                    <h3>Passport & Visa Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="passport_number">Passport Number *</label>
                            <input type="text" id="passport_number" name="passport_number" placeholder="OP123456789" required>
                        </div>
                        <div class="form-group">
                            <label for="visa_type">Visa Type *</label>
                            <select id="visa_type" name="visa_type" required>
                                <option value="">Select Visa Type</option>
                                <option value="tourist">Tourist</option>
                                <option value="student">Student</option>
                                <option value="work_permit">Work Permit</option>
                                <option value="permanent_residence">Permanent Residence</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" placeholder="+254 712 345 678" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                        </div>
                    </div>
                    <div class="form-row full-width">
                        <div class="form-group">
                            <label for="residential_address">Residential Address *</label>
                            <input type="text" id="residential_address" name="residential_address" placeholder="Street, City, Postal Code" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="county">County *</label>
                            <select id="county" name="county" required>
                                <option value="">Select County</option>
                                <option value="Baringo">Baringo</option>
                                <option value="Bomet">Bomet</option>
                                <option value="Bungoma">Bungoma</option>
                                <option value="Busia">Busia</option>
                                <option value="Elgeyo Marakwet">Elgeyo Marakwet</option>
                                <option value="Embu">Embu</option>
                                <option value="Garissa">Garissa</option>
                                <option value="Homa Bay">Homa Bay</option>
                                <option value="Isiolo">Isiolo</option>
                                <option value="Kajiado">Kajiado</option>
                                <option value="Kakamega">Kakamega</option>
                                <option value="Kericho">Kericho</option>
                                <option value="Kiambu">Kiambu</option>
                                <option value="Kilifi">Kilifi</option>
                                <option value="Kirinyaga">Kirinyaga</option>
                                <option value="Kisii">Kisii</option>
                                <option value="Kisumu">Kisumu</option>
                                <option value="Kitui">Kitui</option>
                                <option value="Kwale">Kwale</option>
                                <option value="Laikipia">Laikipia</option>
                                <option value="Lamu">Lamu</option>
                                <option value="Machakos">Machakos</option>
                                <option value="Makueni">Makueni</option>
                                <option value="Mandera">Mandera</option>
                                <option value="Marsabit">Marsabit</option>
                                <option value="Meru">Meru</option>
                                <option value="Migori">Migori</option>
                                <option value="Mombasa">Mombasa</option>
                                <option value="Murang'a">Murang'a</option>
                                <option value="Nairobi City">Nairobi City</option>
                                <option value="Nakuru">Nakuru</option>
                                <option value="Nandi">Nandi</option>
                                <option value="Narok">Narok</option>
                                <option value="Nyamira">Nyamira</option>
                                <option value="Nyandarua">Nyandarua</option>
                                <option value="Nyeri">Nyeri</option>
                                <option value="Samburu">Samburu</option>
                                <option value="Siaya">Siaya</option>
                                <option value="Taita Taveta">Taita Taveta</option>
                                <option value="Tana River">Tana River</option>
                                <option value="Tharaka Nithi">Tharaka Nithi</option>
                                <option value="Trans Nzoia">Trans Nzoia</option>
                                <option value="Turkana">Turkana</option>
                                <option value="Uasin Gishu">Uasin Gishu</option>
                                <option value="Vihiga">Vihiga</option>
                                <option value="Wajir">Wajir</option>
                                <option value="West Pokot">West Pokot</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="form-section">
                    <h3>Account Security</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password *</label>
                            <input type="password" id="confirm_password" placeholder="Re-enter your password" required>
                        </div>
                    </div>
                    <input type="hidden" id="hashedPassword" name="password_hash">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Create Account</button>
                    <a href="login.php" class="btn btn-secondary">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <script>
    function validateDOBAndPassport(event) {
        const dobInput = document.querySelector('input[name="dob"]');
        const passportInput = document.querySelector('input[name="passport_number"]');
        let valid = true;
        if (dobInput) {
            const dob = new Date(dobInput.value);
            const today = new Date();
            today.setHours(0,0,0,0);
            if (!dobInput.value || dob >= today) {
                alert('Date of birth must be before today.');
                event.preventDefault();
                valid = false;
            }
        }
        if (passportInput) {
            const passport = passportInput.value.trim();
            if (!passport.startsWith('OP')) {
                alert('Passport number must start with OP.');
                event.preventDefault();
                valid = false;
            }
        }
        return valid;
    }

    function validateNames(event) {
        const firstNameInput = document.querySelector('input[name="first_name"]');
        const lastNameInput = document.querySelector('input[name="last_name"]');
        const middleNameInput = document.querySelector('input[name="middle_name"]');
        let valid = true;

        // Validate first name - only letters and spaces allowed
        if (firstNameInput && firstNameInput.value.trim()) {
            const firstName = firstNameInput.value.trim();
            if (!/^[A-Za-z\s]+$/.test(firstName)) {
                alert('First name can only contain letters and spaces.');
                firstNameInput.focus();
                event.preventDefault();
                valid = false;
            }
        }

        // Validate last name - only letters and spaces allowed
        if (lastNameInput && lastNameInput.value.trim()) {
            const lastName = lastNameInput.value.trim();
            if (!/^[A-Za-z\s]+$/.test(lastName)) {
                alert('Last name can only contain letters and spaces.');
                lastNameInput.focus();
                event.preventDefault();
                valid = false;
            }
        }

        // Validate middle name - only letters and spaces allowed (optional field)
        if (middleNameInput && middleNameInput.value.trim()) {
            const middleName = middleNameInput.value.trim();
            if (!/^[A-Za-z\s]+$/.test(middleName)) {
                alert('Middle name can only contain letters and spaces.');
                middleNameInput.focus();
                event.preventDefault();
                valid = false;
            }
        }

        return valid;
    }

    function hashPassword(password) {
        const encoder = new TextEncoder();
        const data = encoder.encode(password);
        return window.crypto.subtle.digest('SHA-256', data).then(hashBuffer => {
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        });
    }
    function isValidPassword(password) {
        return password.length >= 8 && /[A-Za-z]/.test(password) && /[0-9]/.test(password);
    }
    async function handleRegisterSubmit(event) {
        event.preventDefault();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (!password || !confirmPassword) {
            alert('Both password fields are required.');
            return;
        }
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        if (!isValidPassword(password)) {
            alert('Password must be at least 8 characters and contain both letters and numbers.');
            return;
        }
        const hashedPassword = await hashPassword(password);
        document.getElementById('hashedPassword').value = hashedPassword;
        document.getElementById('password').value = '';
        document.getElementById('confirm_password').value = '';
        event.target.submit();
    }
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registrationForm');
        if (form) {
            form.addEventListener('submit', validateDOBAndPassport);
            form.addEventListener('submit', validateNames);
            form.addEventListener('submit', handleRegisterSubmit);
        }
    });
    </script>
    <script src="assets/chatbot.js"></script>
</body>
</html> 