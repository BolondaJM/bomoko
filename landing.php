<?php
session_start();
// If user is already logged in, redirect to main page
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRC Embassy Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="landing.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">BOMOKO - Kenya</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome to DRC Embassy Management System</h2>
                <p>Manage DRC citizens in Kenya, process visa applications, and handle incident reports efficiently.</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">Register as DRC Citizen</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                </div>
            </div>
        </section>
        
        <section class="features">
            <h2>System Features</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>Citizen Registration</h3>
                    <p>DRC citizens can register with detailed information including passport details and contact information.</p>
                </div>
                <div class="feature-card">
                    <h3>Visa Applications</h3>
                    <p>Kenyans can apply for visas to the DRC with document upload and status tracking.</p>
                </div>
                <div class="feature-card">
                    <h3>Incident Reports</h3>
                    <p>Report incidents such as lost passports, legal issues, or medical emergencies.</p>
                </div>
                <div class="feature-card">
                    <h3>Admin Dashboard</h3>
                    <p>Embassy staff can manage citizens, review visa applications, and handle incident reports.</p>
                </div>
            </div>
        </section>
        
        <section class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="register.php" class="action-btn">Register as DRC Citizen</a>
                <a href="visa.php" class="action-btn">Apply for Visa</a>
                <a href="incident.php" class="action-btn">Report Incident</a>
                <a href="contact.php" class="action-btn">Contact Embassy</a>
            </div>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi | Riverside Drive, Nairobi, Kenya</p>
    </footer>
</body>
</html> 