<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';

// Check if user is admin
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || empty($user['is_admin'])) {
    echo '<div style="margin:2rem auto;max-width:400px;padding:2rem;background:#fff3f3;border:1px solid #e00;color:#a00;text-align:center;border-radius:8px;">Access denied: Only admins can access this page.<br><a href="admin.php">Back to Admin Dashboard</a></div>';
    exit();
}

$user_name = $user ? $user['first_name'] : '';

// Get recipient email from URL parameter
$to_email = $_GET['email'] ?? '';
$incident_id = $_GET['incident_id'] ?? '';

// Get incident details if incident_id is provided
$incident = null;
if ($incident_id) {
    $stmt = $pdo->prepare('SELECT * FROM incident_reports WHERE id = ?');
    $stmt->execute([$incident_id]);
    $incident = $stmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_email = $_POST['to_email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($to_email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO messages (from_email, to_email, subject, message) VALUES (?, ?, ?, ?)');
            $result = $stmt->execute([$email, $to_email, $subject, $message]);
            
            if ($result) {
                $success = 'Message sent successfully!';
                
                // Clear form
                $to_email = '';
                $subject = '';
                $message = '';
            } else {
                $error = 'Failed to send message. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Database error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .message-header {
            background: #003366;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .message-form {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 200px;
            resize: vertical;
        }
        .incident-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .incident-info h3 {
            margin-top: 0;
            color: #003366;
        }
        .btn-send {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-send:hover {
            background: #218838;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 15px;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
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
        <div class="message-container">
            <div class="message-header">
                <h1>📧 Send Message</h1>
                <p>Send a message to the user regarding their incident report</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="message-form">
                <?php if ($incident): ?>
                    <div class="incident-info">
                        <h3>📋 Incident Report Details</h3>
                        <p><strong>Reporter:</strong> <?= htmlspecialchars($incident['full_name']) ?></p>
                        <p><strong>Incident Type:</strong> <?= htmlspecialchars($incident['incident_type']) ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars($incident['incident_date']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($incident['description']) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="to_email">To (Email Address):</label>
                        <input type="email" id="to_email" name="to_email" value="<?= htmlspecialchars($to_email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required><?= htmlspecialchars($message) ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-send">📤 Send Message</button>
                        <a href="admin.php" class="btn-secondary">🏠 Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <script src="assets/chatbot.js"></script>
</body>
</html>
