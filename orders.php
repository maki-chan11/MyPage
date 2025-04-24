<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Orders</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
      
        h2 {
            text-align: center;
            color: #333;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            background-color: #f9f9f9;
            padding: 16px 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
            border-left: 6px solid #3498db;
        }

        li:hover {
            background-color: #f0f8ff;
        }

        .order-info {
            font-size: 1rem;
            color: #444;
        }

        .order-info span {
            display: block;
        }

        .view-link {
            background-color: #3498db;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        .view-link:hover {
            background-color: #2980b9;
        }

        p {
            text-align: center;
            color: #777;
            font-size: 1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Your Orders</h2>

    <?php if (!empty($orders)): ?>
        <ul>
            <?php foreach ($orders as $order): ?>
                <li>
                    <div class="order-info">
                        <span><strong>Order #<?= $order['id'] ?></strong></span>
                        <span>Total: $<?= number_format($order['total_amount'], 2) ?></span>
                        <span>Placed on: <?= $order['created_at'] ?></span>
                    </div>
                    <a href="order_details.php?id=<?= $order['id'] ?>" class="view-link">View Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
