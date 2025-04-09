<?php
session_start();
include 'config.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the user ID is passed via GET
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Update the login attempt status to 'Denied'
    $stmt = $pdo->prepare("UPDATE login_attempts SET status = 'Denied' WHERE id = ?");
    $stmt->execute([$user_id]);

    // Set a session message to notify the admin
    $_SESSION['login_notifier'] = "Access has been denied for the login attempt. Please ask the user to try again later.";

    // Redirect back to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
} else {
    // If user ID is not provided, redirect to dashboard with an error
    $_SESSION['login_notifier'] = "No user ID provided to deny access.";
    header("Location: admin_dashboard.php");
    exit();
}
