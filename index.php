<?php
include 'config.php';
session_start();

// Initialize attempt count
if (!isset($_SESSION['failed_attempts'])) $_SESSION['failed_attempts'] = 0;
$username = $_SESSION['last_username'] ?? '';
$error_message = '';
$show_verification = false;

// If max attempts are reached, force phone verification
if ($_SESSION['failed_attempts'] >= 2) {
    $error_message = "⚠️ Too many failed attempts. Please verify your phone number.";
    $show_verification = true;
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $_SESSION['last_username'] = $username;
        $password = trim($_POST['password']);
        $ip = $_SERVER['REMOTE_ADDR'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['failed_attempts'] = 0;
            unset($_SESSION['last_username']);
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['failed_attempts']++;
            $pdo->prepare("INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (?, ?, NOW())")
                ->execute([$username, $ip]);

            if ($_SESSION['failed_attempts'] >= 2) {
                $error_message = "⚠️ Too many failed attempts. Please verify your phone number.";
                $show_verification = true;
            } else {
                $error_message = "⚠️ Invalid credentials. Attempt {$_SESSION['failed_attempts']} of 2.";
            }
        }
    } elseif (isset($_POST['verify_phone'])) {
        $phone_input = trim($_POST['verify_phone']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone_input]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['verify_user_id'] = $user['id'];
            $_SESSION['failed_attempts'] = 0;
            header("Location: reset_password.php");
            exit();
        } else {
            $error_message = "⚠️ Phone number not recognized.";
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
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            width: 400px;
            box-sizing: border-box;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
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
            text-align: center;
            font-weight: bold;
        }
        .message.warning {
            color: red;
            border: 1px solid red;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
        }
        p {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if ($error_message): ?>
            <div class="message warning"><?= htmlspecialchars($error_message) ?></div>
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

        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
