<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch login attempts
$loginAttemptsStmt = $pdo->query("SELECT * FROM login_attempts ORDER BY attempt_time DESC");
$loginAttempts = $loginAttemptsStmt->fetchAll();

// Show login notifier message
$loginMessage = "";
if (isset($_SESSION['login_notifier'])) {
    $loginMessage = $_SESSION['login_notifier'];
    unset($_SESSION['login_notifier']);
}

// Handle phone verification and granting access
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_phone'])) {
    $phone_input = trim($_POST['phone_input']);
    $attempt_id = $_POST['attempt_id'];

    // Sanitize input to prevent SQL injection and malicious input
    $phone_input = filter_var($phone_input, FILTER_SANITIZE_STRING);

    // Fetch the corresponding login attempt
    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE id = ?");
    $stmt->execute([$attempt_id]);
    $attempt = $stmt->fetch();

    if ($attempt) {
        // Check if the phone matches
        if ($attempt['phone_number'] == $phone_input) {
            // Update login attempt status to "Verified" and grant access
            $pdo->prepare("UPDATE login_attempts SET status = 'Verified' WHERE id = ?")
                ->execute([$attempt_id]);
            $_SESSION['login_notifier'] = "Phone number verified. User can reset password.";
        } else {
            // Update login attempt status to "Denied"
            $pdo->prepare("UPDATE login_attempts SET status = 'Denied' WHERE id = ?")
                ->execute([$attempt_id]);
            $_SESSION['login_notifier'] = "Phone number does not match. Please try again later.";
        }
    }
    header("Location: admin_dashboard.php"); // Reload the page to see changes
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        .notifier {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #007bff;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #218838;
        }
        .deny-button {
            background-color: #dc3545;
        }
        .deny-button:hover {
            background-color: #c82333;
        }
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            justify-content: center;
            align-items: center;
        }
        .confirmation-modal-content {
            background-color: #333;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .confirmation-modal button {
            margin: 5px;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .confirm-btn {
            background-color: #28a745;
        }
        .cancel-btn {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <?php if (!empty($loginMessage)): ?>
            <p class="notifier"><?= htmlspecialchars($loginMessage) ?></p>
        <?php endif; ?>

        <a href="logout.php" class="button" style="background-color: #dc3545;">Logout</a>

        <h3>Recent Login Attempts</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Phone Number</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($loginAttempts as $attempt): ?>
                <tr>
                    <td><?= htmlspecialchars($attempt['id']) ?></td>
                    <td><?= htmlspecialchars($attempt['username']) ?></td>
                    <td><?= htmlspecialchars($attempt['phone_number']) ?></td>
                    <td><?= htmlspecialchars($attempt['attempt_time']) ?></td>
                    <td><?= htmlspecialchars($attempt['status']) ?></td>
                    <td>
                        <?php if ($attempt['status'] == 'Pending'): ?>
                            <form method="post" style="display:inline;">
                                <input type="text" name="phone_input" placeholder="Enter Phone Number" required>
                                <input type="hidden" name="attempt_id" value="<?= $attempt['id'] ?>">
                                <button type="submit" name="verify_phone" class="button">Verify Phone</button>
                            </form>
                            <a href="reset_password.php?user_id=<?= $attempt['id'] ?>" class="button">Grant Access</a>
                            <button class="button deny-button" onclick="showModal(<?= $attempt['id'] ?>)">Deny Access</button>
                        <?php else: ?>
                            <span>Verified / Denied</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <h3>Are you sure you want to deny access?</h3>
            <button id="confirmBtn" class="confirm-btn">Yes</button>
            <button id="cancelBtn" class="cancel-btn">No</button>
        </div>
    </div>

    <script>
        function showModal(attemptId) {
            // Show the modal
            document.getElementById('confirmationModal').style.display = 'flex';

            // Add event listener to confirm button
            document.getElementById('confirmBtn').onclick = function() {
                // Redirect to deny access page with the attempt ID
                window.location.href = "deny_access.php?user_id=" + attemptId;
            };

            // Add event listener to cancel button
            document.getElementById('cancelBtn').onclick = function() {
                // Close the modal without taking action
                document.getElementById('confirmationModal').style.display = 'none';
            };
        }
    </script>
</body>
</html>
