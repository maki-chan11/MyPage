<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['image']
    ]);
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Product</title></head>
<body>
<h2>Add New Product</h2>
<form method="POST">
    Name: <input type="text" name="name"><br>
    Description: <textarea name="description"></textarea><br>
    Price: <input type="number" step="0.01" name="price"><br>
    Image URL: <input type="text" name="image"><br>
    <button type="submit">Add</button>
</form>
</body>
</html>
