<?php
session_start();
require 'config/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch average ratings
$rating_stmt = $pdo->query("SELECT product_id, AVG(rating) as avg_rating FROM reviews GROUP BY product_id");
$ratings = $rating_stmt->fetchAll(PDO::FETCH_KEY_PAIR); // returns [product_id => avg_rating]

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        $_SESSION['cart'][$productId] += $quantity;
    }

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Products</h2>
    <div class="products">
        
    <?php foreach ($products as $product): ?>
    <div class="product">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p><?= htmlspecialchars($product['description']) ?></p>

        <!-- Show average rating as stars -->
        <div class="rating" style="color: gold;">
            <?php
            $avg = $ratings[$product['id']] ?? 0;
            $fullStars = floor($avg);
            for ($i = 0; $i < 5; $i++) {
                echo $i < $fullStars ? "★" : "☆";
            }
            ?>
        </div>

        <div class="price">$<?= number_format($product['price'], 2) ?></div>
        <!-- Changed form action to post back to index.php -->
        <form method="POST" action="index.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" />
            <button type="submit">Add to Cart</button>
        </form>
        <a href="review.php?product_id=<?= $product['id'] ?>" style="display:inline-block;margin-top:10px;color:#3498db;">
            View Reviews
        </a>
    </div>
    <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
