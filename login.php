<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Login</h1>
        <nav>
            <ul>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Login</h2>
            <form method="POST" action="login_submit.php" id="loginForm">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 DRC Embassy Nairobi</p>
    </footer>
    <script>
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('error') === '1') {
            const msg = document.createElement('div');
            msg.textContent = 'Invalid email or password.';
            msg.style.background = '#f8d7da';
            msg.style.color = '#721c24';
            msg.style.padding = '1rem';
            msg.style.margin = '1rem auto';
            msg.style.textAlign = 'center';
            msg.style.borderRadius = '5px';
            msg.style.maxWidth = '400px';
            document.body.insertBefore(msg, document.body.firstChild);
        }
    });
    </script>
    <script src="assets/chatbot.js"></script>
</body>
</html> 