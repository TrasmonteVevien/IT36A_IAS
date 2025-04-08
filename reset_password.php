<?php
include 'config.php';
session_start();

if (!isset($_SESSION['verify_user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $_SESSION['verify_user_id']]);
    unset($_SESSION['verify_user_id']);
    $message = "Password updated successfully. <a href='index.php'>Login now</a>.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?= $message ?>
    <form method="post">
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
