<?php
include 'config.php';
session_start();

// Optional: Protect this page for admin only
// if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

$stmt = $pdo->query("SELECT * FROM login_attempts ORDER BY attempt_time DESC");
$attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Attempt Monitor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f7f7f7;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .approve {
            background-color: #28a745;
            color: white;
        }
        .block {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <h2>🔐 Login Attempt Monitor</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Phone</th>
                <th>IP Address</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['ip_address']) ?></td>
                    <td><?= htmlspecialchars($row['attempt_time']) ?></td>
                    <td>
                        <button class="btn approve">Grant Access</button>
                        <button class="btn block">Block</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
