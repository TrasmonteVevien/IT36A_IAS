<?php
include 'config.php';
session_start();

if (!isset($_SESSION['failed_attempts'])) $_SESSION['failed_attempts'] = 0;
if (!isset($_SESSION['lockout_time'])) $_SESSION['lockout_time'] = 0;

$max_attempts = 2;
$lockout_duration = 10; // 10 seconds
$error_message = '';
$username = $_SESSION['last_username'] ?? '';
$show_verification = false;

// Check lockout status
if (time() < $_SESSION['lockout_time']) {
    $remaining = $_SESSION['lockout_time'] - time();
    $error_message = "Too many failed attempts. Try again in {$remaining} seconds.";
    $show_verification = true;
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $ip = $_SERVER['REMOTE_ADDR'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lockout_time'] = 0;
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['failed_attempts']++;
            $pdo->prepare("INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (?, ?, NOW())")
                ->execute([$username, $ip]);

            if ($_SESSION['failed_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time() + $lockout_duration;
                $error_message = "Too many failed attempts. Try again in {$lockout_duration} seconds.";
                $show_verification = true;
            } else {
                $error_message = "Invalid credentials. Attempt {$_SESSION['failed_attempts']} of $max_attempts.";
            }
        }
        $username = '';
    } elseif (isset($_POST['verify_phone'])) {
        $phone_input = $_POST['verify_phone'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone_input]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['verify_user_id'] = $user['id'];
            header("Location: reset_password.php");
            exit();
        } else {
            $error_message = "Phone number not recognized.";
            $show_verification = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 10px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error_message): ?>
            <div class="message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if (!$show_verification): ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        <?php else: ?>
            <form method="post">
                <input type="text" name="verify_phone" placeholder="Enter Registered Phone Number" required>
                <button type="submit">Verify Phone</button>
            </form>
        <?php endif; ?>

        <p style="margin-top: 10px;">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
