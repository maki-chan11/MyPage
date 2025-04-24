<?php
session_start();
require 'config/db.php';

// Simple admin check (optional, for demo)
// Replace with your real authentication logic if you have one
$isAdmin = true; 
if (!$isAdmin) {
    die('Access denied');
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $orderId = intval($_GET['delete']);

    // Delete order items first
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);

    // Delete order
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);

    header('Location: admin_orders.php');
    exit();
}

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin - Manage Orders</title>
<link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container" style="max-width:900px; margin:40px auto;">
    <h1>Manage Orders</h1>

    <?php if (empty($orders)): ?>
        <p>No orders found.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ccc;">
                    <th style="padding:8px; text-align:left;">Order ID</th>
                    <th style="padding:8px;">Customer Name</th>
                    <th style="padding:8px;">Email</th>
                    <th style="padding:8px;">Address</th>
                    <th style="padding:8px;">Total</th>
                    <th style="padding:8px;">Date</th>
                    <th style="padding:8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr style="border-bottom:1px solid #ddd;">
                    <td style="padding:8px;"><?= $order['id'] ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($order['customer_email']) ?></td>
                    <td style="padding:8px;"><?= nl2br(htmlspecialchars($order['customer_address'])) ?></td>
                    <td style="padding:8px;">$<?= number_format($order['total'], 2) ?></td>
                    <td style="padding:8px;"><?= $order['created_at'] ?></td>
                    <td style="padding:8px;">
                        <a href="admin_orders.php?delete=<?= $order['id'] ?>" 
                           onclick="return confirm('Are you sure you want to delete this order?');"
                           style="color: red; text-decoration:none;">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
