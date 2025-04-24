<?php
session_start();
require 'config/db.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($rating >= 1 && $rating <= 5 && $comment !== '') {
        $insert = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        $insert->execute([$user_id, $product_id, $rating, $comment]);
        header("Location: review.php?product_id=$product_id");
        exit();
    }
}

// Fetch all reviews for this product
$review_stmt = $pdo->prepare("
    SELECT r.*, u.email AS username
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$review_stmt->execute([$product_id]);
$reviews = $review_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews - <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }

        .review-form {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f4f4f4;
        }

        .review-form input[type="number"],
        .review-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .review-form button {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .review-form button:hover {
            background-color: #27ae60;
        }

        .review-box {
            background: #f0f0f0;
            border-left: 5px solid #3498db;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .review-header {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .review-rating {
            color: #f1c40f;
            font-size: 1.2rem;
        }

        .review-box small {
            display: block;
            margin-top: 8px;
            color: #888;
        }

        .login-note {
            text-align: center;
            margin-top: 30px;
        }

        .login-note a {
            color: #3498db;
            text-decoration: none;
        }

        .login-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Reviews for <?= htmlspecialchars($product['name']) ?></h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="review-form">
            <form method="POST">
                <label for="rating">Rating (1-5):</label>
                <input type="number" id="rating" name="rating" min="1" max="5" required>

                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" placeholder="Share your experience..." required></textarea>

                <button type="submit">Submit Review</button>
            </form>
        </div>
    <?php else: ?>
        <div class="login-note">
            <p><a href="login.php">Login</a> to submit a review.</p>
        </div>
    <?php endif; ?>

    <hr>

    <h3>Customer Reviews</h3>
    <?php if (count($reviews) > 0): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-box">
                <div class="review-header">
                    <?= htmlspecialchars($review['username']) ?> —
                    <span class="review-rating"><?= str_repeat("★", $review['rating']) ?></span>
                </div>
                <div><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                <small><?= date("F j, Y", strtotime($review['created_at'])) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet for this product.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>