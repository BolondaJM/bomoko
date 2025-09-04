<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: landing.php');
    exit();
}
require_once 'db_connect.php';
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, last_name, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
$user_name = $user ? $user['first_name'] : '';
$is_admin = $user && !empty($user['is_admin']);
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
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">BOMOKO - Kenya</h1>
        <nav>
            <ul>
                <!-- <li><a href="login.php">Login / Register</a></li> -->
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
                <li><a href="messages.php">📧 Messages</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        <div style="margin-left:auto;display:flex;align-items:center;gap:1rem;">
            <span style="font-weight:600;"><?php echo htmlspecialchars($user_name); ?></span>
            <?php if ($is_admin): ?>
                <a href="admin.php" style="color:#fff;text-decoration:underline;">Admin</a>
            <?php endif; ?>
            <a href="logout.php" style="color:#fff;text-decoration:underline;">Logout</a>
        </div>
    </header>
    <main>
        <section class="intro">
            <h2>Welcome</h2>
            <p>This system manages DRC citizens residing in Nairobi, visa applications for Kenyans, and incident reports for DRC residents in Kenya.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi</p>
    </footer>
    <script>
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