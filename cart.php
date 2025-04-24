<?php
session_start();
require 'config/db.php';

// Get cart from session or empty array
$cart = $_SESSION['cart'] ?? [];

// Handle remove from cart
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($cart[$id])) {
        unset($cart[$id]);
        $_SESSION['cart'] = $cart;
    }
    header("Location: cart.php");
    exit();
}

// Fetch product details for items in cart
$products_in_cart = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products_in_cart as $product) {
        $total += $product['price'] * $cart[$product['id']];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyShop - Cart</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
     .cart-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
}

.cart-title {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.cart-table th,
.cart-table td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.cart-table th {
    background-color: #f5f5f5;
    font-weight: 600;
    color: #555;
}

.product-thumb {
    max-width: 60px;
    height: auto;
    border-radius: 6px;
}

.btn-remove {
    background-color: #f44336;
    color: white;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn-remove:hover {
    background-color: #d32f2f;
}

.cart-summary {
    text-align: right;
}

.cart-summary h3 {
    font-size: 22px;
    margin-bottom: 20px;
}

.checkout-btn {
    padding: 12px 20px;
    background-color: #4caf50;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.checkout-btn:hover {
    background-color: #45a049;
}

.login-reminder {
    text-align: right;
    margin-top: 10px;
}

.login-reminder a {
    color: #4caf50;
    text-decoration: none;
    font-weight: bold;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
}

.empty-cart h2 {
    font-size: 26px;
    color: #555;
}

.empty-cart p {
    color: #777;
    margin-top: 10px;
    margin-bottom: 20px;
}

.continue-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #2196f3;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.continue-btn:hover {
    background-color: #1976d2;
}


     
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="cart-container">
    <h2 class="cart-title">Your Cart</h2>

    <?php if (!empty($products_in_cart)): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products_in_cart as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><img class="product-thumb" src="<?= htmlspecialchars($product['image']) ?>" alt=""></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= $cart[$product['id']] ?></td>
                        <td>$<?= number_format($product['price'] * $cart[$product['id']], 2) ?></td>
                        <td>
                            <a class="btn-remove" href="cart.php?action=remove&id=<?= $product['id'] ?>"
                               onclick="return confirm('Remove this item?')">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total: $<?= number_format($total, 2) ?></h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" action="checkout.php">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            <?php else: ?>
                <p class="login-reminder"><a href="login.php">Login</a> to checkout.</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="empty-cart">
            <h2>Your cart is empty 🛒</h2>
            <p>Looks like you haven't added anything yet.</p>
            <a class="continue-btn" href="index.php">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
