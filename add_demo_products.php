<?php
require 'config/db.php'; // your PDO connection

$demoProducts = [
    ['Barbecue Chips', 'Smoky barbecue flavored chips', 2.99, 'assets/barbecue.jpg'],
    ['Cheese Chips', 'Cheesy and delicious', 2.49, 'assets/cheese.jpg'],
    ['Sour Cream Chips', 'Tangy sour cream flavor', 2.79, 'assets/sourcream.jpg'],
];

$stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");

foreach ($demoProducts as $p) {
    $stmt->execute($p);
}

echo "Demo products inserted successfully!";
