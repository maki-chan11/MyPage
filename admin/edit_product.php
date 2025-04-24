<?php
session_start();
require '../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->execute([
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['image'],
        $id
    ]);
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<body>
<h2>Edit Product</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"><br>
    Description: <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br>
    Price: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"><br>
    Image URL: <input type="text" name="image" value="<?= $product['image'] ?>"><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
