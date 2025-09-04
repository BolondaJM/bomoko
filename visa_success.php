<?php
session_start();
require_once 'db_connect.php';
require_once 'generate_visa_pdf.php';

// Get the latest application for the current user
$applicationData = null;
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $stmt = $pdo->prepare('SELECT * FROM visa_applications WHERE email = ? ORDER BY id DESC LIMIT 1');
    $stmt->execute([$email]);
    $applicationData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle PDF download
if (isset($_POST['download_pdf']) && $applicationData) {
    downloadVisaPDF($applicationData);
}

// If no application data, redirect to visa form
if (!$applicationData) {
    header('Location: visa.php');
    exit();
}

$user_name = '';
if (isset($_SESSION['user_email'])) {
    $stmt = $pdo->prepare('SELECT first_name FROM users WHERE email = ?');
    $stmt->execute([$_SESSION['user_email']]);
    $user = $stmt->fetch();
    $user_name = $user ? $user['first_name'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application Submitted - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .success-header {
            text-align: center;
            background: #d4edda;
            color: #155724;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #c3e6cb;
        }
        .success-header h1 {
            margin: 0 0 10px 0;
            color: #155724;
        }
        .success-header p {
            margin: 0;
            font-size: 18px;
        }
        .application-id {
            background: #003366;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 18px;
        }
        .summary-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .summary-section h2 {
            color: #003366;
            border-bottom: 2px solid #e0e6ef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .info-value {
            color: #666;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 4px solid #003366;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .btn-download {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 15px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download:hover {
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
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
            color: #856404;
        }
        .important-note h3 {
            margin: 0 0 15px 0;
            color: #856404;
        }
        .important-note ul {
            margin: 0;
            padding-left: 20px;
        }
        .important-note li {
            margin-bottom: 8px;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Visa Application</h1>
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
        <div class="success-container">
            <div class="success-header">
                <h1>✓ Visa Application Submitted Successfully!</h1>
                <p>Your application has been received and is being processed.</p>
            </div>

            <div class="application-id">
                Application ID: <?= strtoupper(substr(md5($applicationData['email'] . $applicationData['passport_number']), 0, 8)) ?>
            </div>

            <div class="important-note">
                <h3>Important Information:</h3>
                <ul>
                    <li>Please keep this summary for your records</li>
                    <li>Application processing typically takes 5-7 working days</li>
                    <li>You will be notified via email about the status of your application</li>
                    <li>Please ensure all required documents are uploaded in the next step</li>
                    <li>You can track your application status using your Application ID</li>
                </ul>
            </div>

            <div class="summary-section">
                <h2>Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['full_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['email']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value"><?= htmlspecialchars(ucfirst($applicationData['gender'])) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['dob']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Country of Origin</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['country']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Passport Number</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['passport_number']) ?></div>
                    </div>
                </div>
            </div>

            <div class="summary-section">
                <h2>Visa Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Visa Type</div>
                        <div class="info-value"><?= htmlspecialchars(ucwords(str_replace("_", " ", $applicationData['visa_type']))) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Duration of Stay</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['duration']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Intended Travel Date</div>
                        <div class="info-value"><?= htmlspecialchars($applicationData['travel_date']) ?></div>
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Reason for Travel</div>
                    <div class="info-value"><?= htmlspecialchars($applicationData['reason']) ?></div>
                </div>
            </div>

            <div class="summary-section">
                <h2>Application Status</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Application Date</div>
                        <div class="info-value"><?= date("F j, Y", strtotime($applicationData['created_at'] ?? 'now')) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value" style="color: #28a745; font-weight: bold;">Submitted</div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="download_pdf" class="btn-download">
                        📄 Download Application Summary
                    </button>
                </form>
                <a href="visa_upload.php?success=1" class="btn-secondary">
                    📎 Upload Required Documents
                </a>
                <a href="index.php" class="btn-secondary">
                    🏠 Back to Home
                </a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <script src="assets/chatbot.js"></script>
</body>
</html>
