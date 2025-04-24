<?php
session_start();
require 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!-- Keep your PHP code at the top (unchanged) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - MyShop</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
       

.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
    padding: 80px 0px;
}

.login-card {
    background-color: #fff;
    border-radius: 16px;
    padding: 40px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.login-title {
    font-size: 28px;
    margin-bottom: 10px;
    color: #222;
}

.login-subtitle {
    color: #666;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

input[type="email"]:focus,
input[type="password"]:focus {
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

.error-message {
    background-color: #ffe6e6;
    color: #c00;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.register-text {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #333;
}

.register-text a {
    color: #4caf50;
    text-decoration: none;
    font-weight: 600;
}

.register-text a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Welcome Back</h2>
        <p class="login-subtitle">Login to your account</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="register-text">
            Don't have an account? <a href="register.php">Register here</a>.
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

