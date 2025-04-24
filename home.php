<?php session_start();
require 'config/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>MyShop - Home</title>
    <meta charset="UTF-8">
    <title>MyShop - Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
       .hero {
    color: white;
    text-align: center;
    padding: 100px 20px;
    background: rgba(40, 173, 80, 0.68);
}
.hero h1 {
    font-size: 60px;
    margin-bottom: 10px;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.3);
}
.hero p {
    font-size: 20px;
    margin-bottom: 30px;
}
.hero a {
    background-color: #3498db;
    color: white;
    padding: 14px 30px;
    font-size: 1.1rem;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s;
}
.hero a:hover {
    background-color: #2980b9;
}
.features {
    font-size: 25px;
    display: flex;
    justify-content: space-around;
    padding: 10px 100px;
    text-align: center;
    background: #f9f9f9;
}
.feature, .featuress {
    max-width: 300px;
    padding-top: 45px;
}
.feature h3 {
    margin-top: 10px;
}
.feature p {
    color: #555;
}

/* Resize feature images */
.feature img,
.featuress img {
    width: 100px;   /* bigger icons */
    height: 100px;
    object-fit: contain;
}

/* Optional: Add some margin below images */
.feature img,
.featuress img {
    margin-bottom: 15px;
}
.products{
    display: flex;
}
.products img{
    height:300px;
    padding:20px;
    width: 250px;
}
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="hero">
    <h1>Welcome to Greenscape</h1>
    <p>Your go-to destination for the tastiest snacks online.</p>
    <a href="index.php">Shop Now</a>
</div>

<div class="features">
    <div class="feature">
        <img src="assets/fast-delivery.png" alt="Fast Delivery" width="60">
        <h3>Fast Delivery</h3>
        <p>Get your snacks delivered fast and fresh to your door.</p>
    </div>
    <div class="feature">
        <img src="assets/quality.png" alt="Top Quality" width="60">
        <h3>Top Quality</h3>
        <p>Only the best ingredients go into our products.</p>
    </div>
    <div class="featuress">
        <img src="assets/support.png" alt="Customer Support" width="60">
        <h3>24/7 Support</h3>
        <p>Need help? Our team is here anytime you need us.</p>
    </div>
</div>
<div class="container">
    <h2>Products</h2>
    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <div class="price">$<?= number_format($product['price'], 2) ?></div>
                <form method="POST" action="index.php">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
