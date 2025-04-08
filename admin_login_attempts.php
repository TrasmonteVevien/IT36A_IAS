<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['attempt_id'])) {
    $attempt_id = $_POST['attempt_id'];
    $action = $_POST['action'];

    // Fetch login attempt with associated user info
    $stmt = $pdo->prepare("
        SELECT la.*, u.id AS user_id, u.phone_number AS real_phone, u.username
        FROM login_attempts la
        LEFT JOIN users u ON la.username = u.username
        WHERE la.id = ?
    ");
    $stmt->execute([$attempt_id]);
    $attempt = $stmt->fetch();

    if (!$attempt) {
        $_SESSION['error_message'] = "Login attempt not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Check phone match
    if (empty($attempt['real_phone']) || $attempt['phone_number'] !== $attempt['real_phone']) {
        $pdo->prepare("DELETE FROM login_attempts WHERE id = ?")->execute([$attempt_id]);
        $_SESSION['error_message'] = "Phone number does not match. Attempt removed. Please try again later.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Action handling
    if ($action === 'grant') {
        $newPassword = bin2hex(random_bytes(4)); // 8-char password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashedPassword, $attempt['user_id']]);
        $pdo->prepare("UPDATE login_attempts SET status = 'granted' WHERE id = ?")->execute([$attempt_id]);

        $_SESSION['success_message'] = "Access granted for '{$attempt['username']}'. New password: <strong>$newPassword</strong>";
    } elseif ($action === 'suggest_reset') {
        $pdo->prepare("UPDATE login_attempts SET status = 'suggest_reset' WHERE id = ?")->execute([$attempt_id]);
        $_SESSION['success_message'] = "Reset password suggested for '{$attempt['username']}'.";
    } elseif ($action === 'remove') {
        $pdo->prepare("UPDATE login_attempts SET status = 'removed' WHERE id = ?")->execute([$attempt_id]);
        $_SESSION['success_message'] = "Login attempt removed.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
