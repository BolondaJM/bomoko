<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';

$user_email = $_SESSION['user_email'];

// Get user details
$stmt = $pdo->prepare('SELECT first_name, last_name FROM users WHERE email = ?');
$stmt->execute([$user_email]);
$user = $stmt->fetch();
$user_name = $user ? $user['first_name'] . ' ' . $user['last_name'] : '';

// Mark message as read if message_id is provided
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $message_id = $_GET['read'];
    $stmt = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = ? AND to_email = ?');
    $stmt->execute([$message_id, $user_email]);
}

// Check if messages table exists
$stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
$tableExists = $stmt->rowCount() > 0;

if (!$tableExists) {
    $messages = [];
    $unread_count = 0;
    $table_error = true;
} else {
    // Get all messages for this user
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE to_email = ? ORDER BY created_at DESC');
    $stmt->execute([$user_email]);
    $messages = $stmt->fetchAll();

    // Debug: Show user email and message count
    $debug_info = "User Email: $user_email, Messages Found: " . count($messages);

    // Count unread messages
    $stmt = $pdo->prepare('SELECT COUNT(*) as unread_count FROM messages WHERE to_email = ? AND is_read = 0');
    $stmt->execute([$user_email]);
    $unread_count = $stmt->fetch()['unread_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .messages-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .messages-header {
            background: #003366;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .messages-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .messages-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .unread-badge {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .message-item {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #003366;
            transition: all 0.3s ease;
        }
        .message-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .message-item.unread {
            border-left-color: #dc3545;
            background: #fff8f8;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .message-subject {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .message-meta {
            text-align: right;
            color: #666;
            font-size: 14px;
        }
        .message-from {
            color: #003366;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message-date {
            color: #666;
        }
        .message-content {
            color: #333;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .message-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .btn-mark-read {
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-mark-read:hover {
            background: #218838;
        }
        .no-messages {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }
        .no-messages h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .message-preview {
            color: #666;
            margin-top: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .message-expanded {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #003366;
        }
        .btn-expand {
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }
        .btn-expand:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">DRC Embassy Nairobi</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
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
        <div class="messages-container">
            <div class="messages-header">
                <h1>📧 Messages</h1>
                <p>Messages from the DRC Embassy regarding your applications and reports</p>
                <?php if ($unread_count > 0): ?>
                    <span class="unread-badge"><?= $unread_count ?> unread</span>
                <?php endif; ?>
            </div>

            <?php if (isset($table_error)): ?>
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 20px; margin-bottom: 20px; color: #856404;">
                    <h3 style="margin: 0 0 10px 0; color: #856404;">⚠️ Messages System Not Set Up</h3>
                    <p>The messages system needs to be initialized. Please run the setup script first.</p>
                    <a href="setup_messages.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">Setup Messages System</a>
                </div>
            <?php endif; ?>

            <?php if (isset($debug_info)): ?>
                <div style="background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 5px; padding: 15px; margin-bottom: 20px; color: #1976d2; font-size: 14px;">
                    <strong>Debug Info:</strong> <?= htmlspecialchars($debug_info) ?>
                    <br><a href="test_messages.php" style="color: #1976d2; text-decoration: underline;">Test Messages System</a>
                </div>
            <?php endif; ?>

            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    <h3>📭 No Messages</h3>
                    <p>You don't have any messages from the embassy yet.</p>
                    <p>Messages will appear here when the embassy contacts you regarding your applications or incident reports.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message-item <?= $message['is_read'] ? '' : 'unread' ?>" id="message-<?= $message['id'] ?>">
                        <div class="message-header">
                            <div>
                                <h3 class="message-subject"><?= htmlspecialchars($message['subject']) ?></h3>
                                <div class="message-from">From: DRC Embassy</div>
                            </div>
                            <div class="message-meta">
                                <div class="message-date"><?= date("F j, Y \a\t g:i A", strtotime($message['created_at'])) ?></div>
                                <?php if (!$message['is_read']): ?>
                                    <span class="unread-badge">New</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="message-preview">
                            <?= htmlspecialchars(substr($message['message'], 0, 150)) ?><?= strlen($message['message']) > 150 ? '...' : '' ?>
                        </div>
                        
                        <div class="message-expanded" id="expanded-<?= $message['id'] ?>">
                            <div class="message-content"><?= nl2br(htmlspecialchars($message['message'])) ?></div>
                        </div>
                        
                        <div class="message-actions">
                            <button class="btn-expand" onclick="toggleMessage(<?= $message['id'] ?>)">
                                <?= $message['is_read'] ? '📖 View Message' : '📖 Read Message' ?>
                            </button>
                            <?php if (!$message['is_read']): ?>
                                <a href="?read=<?= $message['id'] ?>" class="btn-mark-read">✓ Mark as Read</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>

    <script>
        function toggleMessage(messageId) {
            const expanded = document.getElementById('expanded-' + messageId);
            const button = event.target;
            
            if (expanded.style.display === 'none' || expanded.style.display === '') {
                expanded.style.display = 'block';
                button.textContent = '📖 Hide Message';
            } else {
                expanded.style.display = 'none';
                button.textContent = '📖 View Message';
            }
        }
    </script>
    <script src="assets/chatbot.js"></script>
</body>
</html>
