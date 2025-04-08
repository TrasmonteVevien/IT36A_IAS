<?php
include 'config.php';
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = '';

// Registration logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $passwordRaw = $_POST['password'] ?? '';

    if (!empty($username) && !empty($phone) && !empty($passwordRaw)) {
        $password = password_hash($passwordRaw, PASSWORD_BCRYPT);

        // Check if username already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $checkStmt->execute([$username]);
        $userExists = $checkStmt->fetchColumn();

        if ($userExists) {
            $message = "<p style='color: red; text-align: center;'>Username is already taken.</p>";
        } else {
            // Insert new user with phone number
            $stmt = $pdo->prepare("INSERT INTO users (username, password, phone) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$username, $password, $phone]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
                header("Location: dashboard.php"); // Redirect after successful registration
                exit();
            } catch (PDOException $e) {
                $message = "<p style='color: red; text-align: center;'>An error occurred. Please try again later.</p>";
            }
        }
    } else {
        $message = "<p style='color: red; text-align: center;'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .register-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .footer-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .footer-link a {
            color: #007bff;
            text-decoration: none;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?= $message ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="text" name="phone" placeholder="Phone Number" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <div class="footer-link">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
