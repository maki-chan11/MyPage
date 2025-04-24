<?php
session_start();
require 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register - MyShop</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        /* Shared styles from login.css */
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
    padding: 40px 20px;
}

.register-card {
    background-color: #fff;
    border-radius: 16px;
    padding: 40px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.register-title {
    font-size: 28px;
    margin-bottom: 10px;
    color: #222;
}

.register-subtitle {
    color: #666;
    margin-bottom: 30px;
}

.register-form .form-group {
    margin-bottom: 20px;
}

.register-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.register-form input[type="text"],
.register-form input[type="email"],
.register-form input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.register-form input:focus {
    outline: none;
    border-color: #4caf50;
}

.btn-primary {
    width: 100%;
    padding: 12px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #45a049;
}

.login-text {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #333;
}

.login-text a {
    color: #4caf50;
    text-decoration: none;
    font-weight: 600;
}

.login-text a:hover {
    text-decoration: underline;
}

.error-message {
    background-color: #ffe6e6;
    color: #c00;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="register-container">
    <div class="register-card">
        <h2 class="register-title">Create Account</h2>
        <p class="register-subtitle">Sign up to start shopping</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="register-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required />
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required />
            </div>

            <button type="submit" class="btn-primary">Register</button>
        </form>

        <p class="login-text">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
