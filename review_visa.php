<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';

// Check if user is admin (same method as admin.php)
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || empty($user['is_admin'])) {
    echo '<div style="margin:2rem auto;max-width:400px;padding:2rem;background:#fff3f3;border:1px solid #e00;color:#a00;text-align:center;border-radius:8px;">Access denied: Only admins can access this page.<br><a href="admin.php">Back to Admin Dashboard</a></div>';
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) { 
    echo '<div style="text-align: center; padding: 50px;"><h2>No application selected.</h2><a href="admin.php">Back to Admin Dashboard</a></div>'; 
    exit(); 
}

$stmt = $pdo->prepare('SELECT * FROM visa_applications WHERE id=?');
$stmt->execute([$id]);
$visa = $stmt->fetch();

if (!$visa) { 
    echo '<div style="text-align: center; padding: 50px;"><h2>Visa application not found.</h2><a href="admin.php">Back to Admin Dashboard</a></div>'; 
    exit(); 
}

// Get uploaded documents for this application
$stmt = $pdo->prepare('SELECT * FROM visa_documents WHERE email = ? ORDER BY uploaded_at DESC LIMIT 1');
$stmt->execute([$visa['email']]);
$documents = $stmt->fetch();

$user_name = $user ? $user['first_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Visa Application - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .review-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .review-header {
            background: #003366;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .review-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .review-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .application-id {
            background: #f8f9fa;
            border: 2px solid #003366;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 18px;
            color: #003366;
        }
        .review-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .review-section h2 {
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
        .documents-section {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .documents-section h3 {
            color: #1976d2;
            margin-top: 0;
        }
        .document-item {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #2196f3;
        }
        .document-item h4 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }
        .document-item p {
            margin: 5px 0;
            color: #666;
        }
        .btn-view {
            background: #2196f3;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-view:hover {
            background: #1976d2;
        }
        .btn-download {
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download:hover {
            background: #218838;
        }
        .no-documents {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            color: #856404;
            text-align: center;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e6ef;
        }
        .btn-approve {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 15px;
        }
        .btn-approve:hover {
            background: #218838;
        }
        .btn-reject {
            background: #dc3545;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 15px;
        }
        .btn-reject:hover {
            background: #c82333;
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
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .btn-approve, .btn-reject, .btn-secondary {
                margin-right: 0;
            }
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
        <div class="review-container">
            <div class="review-header">
                <h1>📋 Visa Application Review</h1>
                <p>Comprehensive review of visa application and uploaded documents</p>
            </div>

            <div class="application-id">
                Application ID: <?= strtoupper(substr(md5($visa['email'] . $visa['passport_number']), 0, 8)) ?> | 
                Status: <span class="status-badge status-<?= isset($visa['status']) ? $visa['status'] : 'pending' ?>"><?= ucfirst(isset($visa['status']) ? $visa['status'] : 'pending') ?></span>
            </div>

            <div class="review-section">
                <h2>👤 Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?= htmlspecialchars($visa['full_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value"><?= htmlspecialchars($visa['email']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value"><?= htmlspecialchars(ucfirst($visa['gender'])) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value"><?= htmlspecialchars($visa['dob']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Country of Origin</div>
                        <div class="info-value"><?= htmlspecialchars($visa['country']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Passport Number</div>
                        <div class="info-value"><?= htmlspecialchars($visa['passport_number']) ?></div>
                    </div>
                </div>
            </div>

            <div class="review-section">
                <h2>🛂 Visa Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Visa Type</div>
                        <div class="info-value"><?= htmlspecialchars(ucwords(str_replace("_", " ", $visa['visa_type']))) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Duration of Stay</div>
                        <div class="info-value"><?= htmlspecialchars($visa['duration']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Intended Travel Date</div>
                        <div class="info-value"><?= htmlspecialchars($visa['travel_date']) ?></div>
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Reason for Travel</div>
                    <div class="info-value"><?= htmlspecialchars($visa['reason']) ?></div>
                </div>
            </div>

            <div class="review-section">
                <h2>📄 Uploaded Documents</h2>
                <?php if ($documents): ?>
                    <div class="documents-section">
                        <h3>📎 Supporting Documents</h3>
                        
                        <div class="document-item">
                            <h4>🛂 Passport Copy</h4>
                            <p><strong>File:</strong> <?= htmlspecialchars(basename($documents['passport_pdf'])) ?></p>
                            <p><strong>Uploaded:</strong> <?= date("F j, Y \a\t g:i A", strtotime($documents['uploaded_at'])) ?></p>
                            <a href="<?= htmlspecialchars($documents['passport_pdf']) ?>" target="_blank" class="btn-view">👁️ View Document</a>
                            <a href="<?= htmlspecialchars($documents['passport_pdf']) ?>" download class="btn-download">📥 Download</a>
                        </div>

                        <div class="document-item">
                            <h4>📋 Supporting Documents</h4>
                            <p><strong>File:</strong> <?= htmlspecialchars(basename($documents['supporting_pdf'])) ?></p>
                            <p><strong>Uploaded:</strong> <?= date("F j, Y \a\t g:i A", strtotime($documents['uploaded_at'])) ?></p>
                            <a href="<?= htmlspecialchars($documents['supporting_pdf']) ?>" target="_blank" class="btn-view">👁️ View Document</a>
                            <a href="<?= htmlspecialchars($documents['supporting_pdf']) ?>" download class="btn-download">📥 Download</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-documents">
                        <h3>⚠️ No Documents Uploaded</h3>
                        <p>This applicant has not uploaded any supporting documents yet.</p>
                        <p>Please contact the applicant to upload required documents before proceeding with the review.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="review-section">
                <h2>📊 Application Summary</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Application Date</div>
                        <div class="info-value"><?= date("F j, Y", strtotime($visa['created_at'])) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Current Status</div>
                        <div class="info-value">
                                                         <span class="status-badge status-<?= isset($visa['status']) ? $visa['status'] : 'pending' ?>">
                                 <?= ucfirst(isset($visa['status']) ? $visa['status'] : 'pending') ?>
                             </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <?php if (!isset($visa['status']) || ($visa['status'] !== 'approved' && $visa['status'] !== 'rejected')): ?>
                    <form method="POST" action="validate_visa.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $visa['id'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn-approve" onclick="return confirm('Are you sure you want to approve this visa application?')">
                            ✅ Approve Application
                        </button>
                    </form>
                    <form method="POST" action="validate_visa.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $visa['id'] ?>">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this visa application?')">
                            ❌ Reject Application
                        </button>
                    </form>
                <?php else: ?>
                    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <strong>Application Status:</strong> This application has been <?= isset($visa['status']) ? $visa['status'] : 'pending' ?>.
                    </div>
                <?php endif; ?>
                
                <a href="admin.php" class="btn-secondary">🏠 Back to Admin Dashboard</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
    </footer>
    <script src="assets/chatbot.js"></script>
</body>
</html> 