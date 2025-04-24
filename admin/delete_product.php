<?php
session_start();
require 'config/db.php';

// Simple admin check (optional, replace with your auth logic)
$isAdmin = true;
if (!$isAdmin) {
    die('Access denied');
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Optional: Delete the image file from server if you want
    $stmtImg = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmtImg->execute([$productId]);
    $product = $stmtImg->fetch(PDO::FETCH_ASSOC);

    if ($product && !empty($product['image']) && file_exists($product['image'])) {
        unlink($product['image']);
    }

    // Delete the product from DB
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    // Redirect back to products list (change the location as needed)
    header('Location: products.php?msg=Product+deleted');
    exit();
} else {
    // Invalid or missing id
    header('Location: products.php?error=Invalid+product+ID');
    exit();
}
