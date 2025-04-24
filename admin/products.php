<?php
session_start();
require '../config/db.php';

// Basic access control (change email to match your admin)
if (!isset($_SESSION['user_id']) || $_SESSION['user_name'] != 'admin@example.com') {
    die("Access denied.");
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: products.php");
    exit();
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Products</title>
</head>
<body>
<h2>Admin - Products</h2>

<a href="product_add.php">Add New Product</a><br><br>

<table border="1" cellpadding="5">
<tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td>$<?= $p['price'] ?></td>
    <td>
        <a href="product_edit.php?id=<?= $p['id'] ?>">Edit</a> | 
        <a href="products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
