<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
require_once 'db_connect.php';
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
$user_name = $user ? $user['first_name'] : '';
if (!$user || empty($user['is_admin'])) {
    echo '<div style="margin:2rem auto;max-width:400px;padding:2rem;background:#fff3f3;border:1px solid #e00;color:#a00;text-align:center;border-radius:8px;">Access denied: Only admins can access this page.<br><a href="index.php">Back to Home</a></div>';
    exit();
}

// Fetch all users
$users = $pdo->query('SELECT * FROM users')->fetchAll();
// Fetch all incident reports
$incidents = $pdo->query('SELECT * FROM incident_reports')->fetchAll();
// Fetch all visa applications
$visas = $pdo->query('SELECT * FROM visa_applications')->fetchAll();
?>
<!-- NOTE: Backend PHP integration required for full admin functionality (view, add, edit, delete, etc.) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DRC Embassy</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
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
        .status-validated {
            background: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-link"><img src="assets/drc logo.png" alt="DRC Logo" class="logo" style="height:48px;vertical-align:middle;"></a>
        <h1 style="display:inline-block;vertical-align:middle;margin-left:1rem;">Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="visa.php">Visa Application</a></li>
                <li><a href="incident.php">Incident Report</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        <div style="margin-left:auto;display:flex;align-items:center;gap:1rem;">
            <span style="font-weight:600;"><?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php" style="color:#fff;text-decoration:underline;">Logout</a>
        </div>
    </header>
    <main style="width:100vw;min-height:calc(100vh - 80px - 60px);background:#fff;box-sizing:border-box;padding-bottom:3rem;">
        <?php
        // Display success messages if any
        if (isset($_GET['success'])) {
            $success_message = '';
            $applicant = $_GET['applicant'] ?? '';
            switch ($_GET['success']) {
                case 'admin_assigned':
                    $success_message = 'Admin privileges have been successfully assigned.';
                    break;
                case 'admin_removed':
                    $success_message = 'Admin privileges have been successfully removed.';
                    break;
                case 'user_updated':
                    $success_message = 'User information has been successfully updated.';
                    break;
                case 'application_approved':
                    $success_message = 'Visa application for <strong>' . htmlspecialchars($applicant) . '</strong> has been approved successfully.';
                    break;
                case 'application_rejected':
                    $success_message = 'Visa application for <strong>' . htmlspecialchars($applicant) . '</strong> has been rejected.';
                    break;
                default:
                    $success_message = 'Operation completed successfully.';
            }
            if ($success_message) {
                echo '<div style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px; border-radius: 4px; border: 1px solid #c3e6cb; text-align: center;">';
                echo $success_message;
                echo '</div>';
            }
        }
        
        // Display error messages if any
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'self_removal':
                    $error_message = 'You cannot remove your own admin privileges.';
                    break;
                case 'invalid_id':
                    $error_message = 'Invalid application ID provided.';
                    break;
                case 'invalid_action':
                    $error_message = 'Invalid action requested.';
                    break;
                case 'application_not_found':
                    $error_message = 'Application not found.';
                    break;
                case 'database_error':
                    $error_message = 'Database error occurred. Please try again.';
                    break;
                case 'access_denied':
                    $error_message = 'Access denied. Only admins can perform this action.';
                    break;
                default:
                    $error_message = 'An error occurred during the operation.';
            }
            if ($error_message) {
                echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border-radius: 4px; border: 1px solid #f5c6cb; text-align: center;">';
                echo htmlspecialchars($error_message);
                echo '</div>';
            }
        }
        ?>
        <section>
            <h2>Registered Citizens</h2>
            <button>Add Citizen</button>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Place of Birth</th>
                        <th>Passport Number</th>
                        <th>Visa Type</th>
                        <th>Phone Number</th>
                        <th>Email Address</th>
                        <th>Residential Address</th>
                        <th>County</th>
                        <th>Admin Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['first_name']) ?></td>
                        <td><?= htmlspecialchars($u['middle_name']) ?></td>
                        <td><?= htmlspecialchars($u['last_name']) ?></td>
                        <td><?= htmlspecialchars($u['gender']) ?></td>
                        <td><?= htmlspecialchars($u['dob']) ?></td>
                        <td><?= htmlspecialchars($u['place_of_birth']) ?></td>
                        <td><?= htmlspecialchars($u['passport_number']) ?></td>
                        <td><?= htmlspecialchars($u['visa_type']) ?></td>
                        <td><?= htmlspecialchars($u['phone']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['residential_address']) ?></td>
                        <td><?= htmlspecialchars($u['county']) ?></td>
                        <td>
                            <?php if (!empty($u['is_admin'])): ?>
                                <span style="background:#007a00;color:#fff;padding:2px 8px;border-radius:3px;font-size:12px;">Admin</span>
                            <?php else: ?>
                                <span style="background:#666;color:#fff;padding:2px 8px;border-radius:3px;font-size:12px;">User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="GET" action="edit_user.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" style="background:#007cba;color:#fff;border:none;padding:4px 8px;border-radius:3px;cursor:pointer;font-size:12px;">Edit</button>
                            </form>
                            <form method="POST" action="delete_user.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" style="background:#dc3545;color:#fff;border:none;padding:4px 8px;border-radius:3px;cursor:pointer;font-size:12px;">Delete</button>
                            </form>
                            <form method="POST" action="assign_admin.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to change the admin status for this user?');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <?php if (!empty($u['is_admin'])): ?>
                                    <input type="hidden" name="is_admin" value="0">
                                    <button type="submit" style="background:#a00;color:#fff;border:none;padding:4px 8px;border-radius:3px;cursor:pointer;font-size:12px;">Remove Admin</button>
                                <?php else: ?>
                                    <input type="hidden" name="is_admin" value="1">
                                    <button type="submit" style="background:#007a00;color:#fff;border:none;padding:4px 8px;border-radius:3px;cursor:pointer;font-size:12px;">Make Admin</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </section>
        <section>
            <h2>Incident Reports</h2>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Address</th>
                        <th>Police Reported</th>
                        <th>OB Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($incidents as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['full_name']) ?></td>
                        <td><?= htmlspecialchars($i['email']) ?></td>
                        <td><?= htmlspecialchars($i['phone']) ?></td>
                        <td><?= htmlspecialchars($i['incident_type']) ?></td>
                        <td><?= htmlspecialchars($i['incident_date']) ?></td>
                        <td><?= htmlspecialchars($i['description']) ?></td>
                        <td><?= htmlspecialchars($i['incident_address']) ?></td>
                        <td><?= htmlspecialchars($i['reported_police']) ?></td>
                        <td><?= htmlspecialchars($i['ob_number'] ?? 'N/A') ?></td>
                                                 <td>
                             <a href="send_message.php?email=<?= urlencode($i['email']) ?>&incident_id=<?= $i['id'] ?>" style="background: #007bff; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px;">📧 Message</a>
                         </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </section>
        <section>
            <h2>Visa Applications</h2>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Visa Type</th>
                        <th>Country</th>
                        <th>Travel Date</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($visas as $v): ?>
                    <?php
                    // Check if documents are uploaded
                    $stmt = $pdo->prepare('SELECT COUNT(*) as doc_count FROM visa_documents WHERE email = ?');
                    $stmt->execute([$v['email']]);
                    $docCount = $stmt->fetch()['doc_count'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($v['full_name']) ?></td>
                        <td><?= htmlspecialchars($v['email']) ?></td>
                        <td><?= htmlspecialchars(ucwords(str_replace("_", " ", $v['visa_type']))) ?></td>
                        <td><?= htmlspecialchars($v['country']) ?></td>
                        <td><?= htmlspecialchars($v['travel_date']) ?></td>
                        <td>
                            <?php
                                                         $status = isset($v['status']) ? $v['status'] : 'pending';
                            $statusClass = 'status-' . $status;
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <?php if ($docCount > 0): ?>
                                <span style="color: #28a745; font-weight: bold;">✓ Uploaded</span>
                            <?php else: ?>
                                <span style="color: #dc3545; font-weight: bold;">✗ Missing</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="GET" action="review_visa.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <button type="submit" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">📋 Review</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 DRC Embassy Nairobi</p>
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