<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
$user_name = $user ? $user['first_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application - Upload Documents</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Visa Application</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        <div style="margin-left:auto;position:relative;">
            <div id="userDropdown" style="cursor:pointer;position:relative;">
                <span><?php echo htmlspecialchars($user_name); ?></span>
                <span style="font-size:1.2em;vertical-align:middle;">&#9662;</span>
                <div id="dropdownMenu" style="display:none;position:absolute;right:0;top:100%;background:#fff;border:1px solid #ccc;border-radius:4px;min-width:120px;z-index:1000;">
                    <a href="logout.php" style="display:block;padding:0.7rem 1rem;color:#222;text-decoration:none;">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section>
            <h2>Upload Required Documents</h2>
            <form method="POST" action="visa_upload_submit.php" enctype="multipart/form-data">
                <label>Upload Passport Copy (PDF):</label>
                <input type="file" name="passport_pdf" accept="application/pdf" required>
                <label>Upload Supporting Documents (PDF):</label>
                <input type="file" name="supporting_pdf" accept="application/pdf" required>
                <input type="hidden" name="email" value=""><!-- Set this value dynamically from previous step/session -->
                <label style="display:flex;align-items:center;margin-top:1rem;">
                    <input type="checkbox" name="agree" required style="margin-right:0.5rem;">
                    I agree to the <a href="#" style="color:#003366;text-decoration:underline;margin-left:0.3rem;">terms and conditions</a>.
                </label>
                <button type="submit">Apply</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi</p>
    </footer>
    <script>
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('success') === '1') {
            const msg = document.createElement('div');
            msg.textContent = 'Visa application submitted successfully! Please upload your documents.';
            msg.style.background = '#d4edda';
            msg.style.color = '#155724';
            msg.style.padding = '1rem';
            msg.style.margin = '1rem auto';
            msg.style.textAlign = 'center';
            msg.style.borderRadius = '5px';
            msg.style.maxWidth = '400px';
            document.body.insertBefore(msg, document.body.firstChild);
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