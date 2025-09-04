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
    <title>Contact Us - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Contact Us</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="visa.php">Visa Application</a></li>
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
        <section class="intro">
            <h2>DRC Embassy in Nairobi, Kenya</h2>
            <p><strong>Address:</strong> Riverside Drive, Nairobi, Kenya</p>
            <p><strong>Email:</strong> <a href="mailto:info@drcembassy.co.ke">info@drcembassy.co.ke</a></p>
            <p><strong>Phone:</strong> <a href="tel:+254712345678">+254 712 345 678</a></p>
        </section>
        <section>
            <h3>Find Us on the Map</h3>
            <div style="width:100%;max-width:600px;margin:1rem auto;">
                <iframe src="https://www.google.com/maps?q=Riverside+Drive,+Nairobi,+Kenya&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
        <section>
            <h3>Send Us a Message</h3>
            <form>
                <textarea placeholder="Your message..." required style="min-height:100px;"></textarea>
                <button type="submit">Send Message</button>
            </form>
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