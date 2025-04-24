    <?php
    session_start();?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>About Us - Lettuce Chips</title>
        <link rel="stylesheet" href="css/styles.css" />
        <style>

.about-container {
    flex: 1;
    padding: 40px;
    max-width: 1200px;
    margin: 0 auto;
}

.about-header {
    text-align: center;
    margin-bottom: 25px;
    padding: 0 10px;
}

.about-header h1 {
    font-size: 2.4rem;        /* slightly smaller for better fit */
    color: #27ae60;
    margin: 0 0 12px;
    line-height: 1.2;
}

.about-header p {
    font-size: 1.1rem;
    color: #444;
    margin: 0 auto;
    max-width: 650px;          /* constrain width for easier reading */
    line-height: 1.5;
}

.about-content {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
}

.about-text {
    flex: 1 1 400px;
    min-width: 280px;
    padding: 0 10px;
    max-width: 480px;           /* limit width so lines don’t stretch too far */
}

.about-text p {
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 20px;
    color: #333;
}

.about-img {
    flex: 1 1 300px;
    min-width: 280px;
    text-align: center;
    padding: 0 10px;
    max-width: 400px;           /* constrain image container */
}

.about-img img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

.about-img img:hover {
    transform: scale(1.03);
}

/* Responsive for tablets and smaller desktops */
@media (max-width: 768px) {
    .about-container {
        max-width: 700px;
        padding: 25px 20px;
    }

    .about-header h1 {
        font-size: 2rem;
    }

    .about-header p {
        font-size: 1rem;
        max-width: 100%;
    }

    .about-text {
        max-width: 100%;
        padding: 0 5px;
    }

    .about-text p {
        font-size: 1rem;
    }

    .about-img {
        max-width: 350px;
    }
}

/* Responsive for mobile phones */
@media (max-width: 480px) {
    .about-container {
        margin: 50px 10px;
        padding: 15px 10px;
        border-radius: 8px;
    }

    .about-content {
        flex-direction: column;
        gap: 15px;
    }

    .about-text,
    .about-img {
        flex: 1 1 100%;
        min-width: unset;
        padding: 0;
        max-width: 100%;
    }

    .about-header h1 {
        font-size: 1.1rem;   /* smaller */
        margin-bottom: 8px;
    }

    .about-header p {
        font-size: 0.7rem;   /* smaller */
        line-height: 1.2;
    }

    .about-text p {
        font-size: 0.1rem;   /* smaller */
        line-height: 1.0;
    }

    .about-img img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
}


        </style>
    </head>
    <body>
    
    <?php include 'includes/header.php'; ?>
    <div class="about-container">
        <div class="about-header">
            <h1>Welcome to Our Crunchy Paradise! 🌿</h1>
            <p>Redefining the snack game with crispy and healthy lettuce chips.</p>
        </div>

        <div class="about-content">
            <div class="about-text">
                <p>Indulge in the satisfying crunch of perfectly seasoned lettuce chips, crafted to perfection for your enjoyment.
                    Say goodbye to greasy, calorie-laden snacks and embrace the goodness of our light and flavorful lettuce chips.</p>
                <p>Packed with essential vitamins and nutrients, our chips offer a guilt-free way to satisfy your cravings—whether
                    you're a health-conscious foodie, a snack lover, or just looking for a tasty alternative.</p>
                <p>Join our growing community of lettuce chip enthusiasts and discover mouthwatering recipes, creative serving ideas,
                    and simple tips for making your own delicious chips at home.</p>
                <p>Let’s crunch into something extraordinary together! 🥬✨</p>
            </div>
            <div class="about-img">
                <img src="assets/green.png" alt="Lettuce Chips">
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    </body>
    </html>
