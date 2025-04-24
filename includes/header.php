<?php

function getCartItemCount() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}
?>
<header style="padding: 15px 30px; background:#2c3e50; text-align: center;">
    <a href="home.php">
        <img src="assets/lettuce.png" alt="MyShop Logo" style="height: 60px; width: auto;">
    </a>
  <nav>
  <a href="home.php">Home</a>
  <a href="about.php">About</a>
    <a href="index.php">Products</a>
    <a href="cart.php">Cart (<?= getCartItemCount() ?>)</a>
    <a href="orders.php">My Orders</a>
    <?php if(isset($_SESSION['user_name'])): ?>
      <a href="logout.php">Logout (<?=htmlspecialchars($_SESSION['user_name'])?>)</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>
