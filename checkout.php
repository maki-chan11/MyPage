<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

// Fetch cart product details
$products = [];
$total = 0;
foreach ($cart as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $product['quantity'] = $quantity;
        $product['subtotal'] = $product['price'] * $quantity;
        $products[] = $product;
        $total += $product['subtotal'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

    if ($name && $address && $contact) {
        try {
            $pdo->beginTransaction();

            // Insert order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, address, contact, total_amount, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $name, $address, $contact, $total]);
            $order_id = $pdo->lastInsertId();

            // Insert order items
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($products as $item) {
                $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }

            $pdo->commit();
            $_SESSION['cart'] = []; // Clear cart

            // Redirect to order details
            header("Location: order_details.php?id=$order_id");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Order failed: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container { max-width: 800px; margin: 30px auto; padding: 20px; background: #f9f9f9; border-radius: 10px; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 15px; }
        form button { background-color: #27ae60; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
        .error { color: red; margin-bottom: 10px; }
        .summary { background: #ecf0f1; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Checkout</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="summary">
        <h4>Order Summary</h4>
        <ul>
            <?php foreach ($products as $item): ?>
                <li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> = $<?= number_format($item['subtotal'], 2) ?></li>
            <?php endforeach; ?>
        </ul>
        <strong>Total: $<?= number_format($total, 2) ?></strong>
    </div>

    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Address:</label>
        <textarea name="address" required></textarea>

        <label>Contact Number:</label>
        <input type="text" name="contact" required>

        <button type="submit">Place Order</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
