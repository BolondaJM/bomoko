<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Complete - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Registration Complete</h1>
        
    </header>
    <main>
        <section class="intro">
            <h2>Thank you for registering!</h2>
            <p>Your account has been created successfully. You can now <a href="login.php">login</a> to your account.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="visa.php">Visa Application</a></li>
            <li><a href="incident.php">Incident Report</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>
    <script>
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('success') === '1') {
            const msg = document.createElement('div');
            msg.textContent = 'Registration successful! You can now log in.';
            msg.style.background = '#d4edda';
            msg.style.color = '#155724';
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