<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$orderId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// Verify the order belongs to this user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found or access denied.";
    exit();
}

// Get order items
$stmtItems = $pdo->prepare("
    SELECT oi.*, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmtItems->execute([$orderId]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container { max-width: 900px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        img { width: 60px; height: auto; border-radius: 6px; }
        .back-link { margin-top: 20px; display: inline-block; color: #3498db; text-decoration: none; }
        h2 { margin-bottom: 10px; }
        .info { margin-top: 10px; background: #f1f1f1; padding: 10px; border-radius: 6px; }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container">
    <h2>Order #<?= $order['id'] ?> Details</h2>

    <div class="info">
        <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($order['contact']) ?></p>
        <p><strong>Total:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><img src="<?= htmlspecialchars($item['image']) ?>" alt=""></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a class="back-link" href="orders.php">&larr; Back to Orders</a>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
