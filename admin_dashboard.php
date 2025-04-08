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
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <?php if (!empty($loginMessage)): ?>
            <p class="notifier"> <?= htmlspecialchars($loginMessage) ?> </p>
        <?php endif; ?>
        <a href="admin_login_attempts.php" class="button">View Login Attempts</a>
        <a href="logout.php" class="button" style="background-color: #dc3545;">Logout</a>
        
        <h3>Recent Login Attempts</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Phone Number</th>
        <th>Time</th>
        <th>Status</th>
    </tr>
    <?php foreach ($loginAttempts as $attempt): ?>
        <tr>
            <td><?= htmlspecialchars($attempt['id']) ?></td>
            <td><?= htmlspecialchars($attempt['username']) ?></td>
            <td><?= htmlspecialchars($attempt['phone_number']) ?></td>
            <td><?= htmlspecialchars($attempt['attempt_time']) ?></td>
            <td><?= htmlspecialchars($attempt['status']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
