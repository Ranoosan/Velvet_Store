
<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, get user data from session
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    // If the user is not logged in, set default values
    $username = null;
    $email = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
// Include the navbar
include('navbar.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to CSS -->

    <title>Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            animation: fadeIn 1.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 3rem;
            color: #343a40;
            text-transform: uppercase;
            letter-spacing: 5px;
            animation: slideDown 1s ease-in-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        p {
            text-align: center;
            font-size: 1.2rem;
            color: #495057;
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
            text-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px auto;
            font-size: 1rem;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-image {
            background: url('cloths_shop_banner.jpg') center/cover no-repeat;
            height: 50vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-text {
            color: #fff;
            text-align: center;
            z-index: 1;
            animation: popUp 1.2s ease;
        }

        @keyframes popUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .hero-text h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin: 10px 0 20px;
        }

        .promotions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px;
            padding: 10px;
        }

        .promo-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .promo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .promo-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .promo-content {
            padding: 20px;
            text-align: center;
        }

        .promo-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #343a40;
        }

        .promo-description {
            font-size: 1rem;
            color: #495057;
            margin-bottom: 15px;
        }

        .promo-btn {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .promo-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="hero-image">
        <div class="hero-text">
            <h1>Welcome to Velvet Vogue</h1>
            <p>Where style meets elegance and comfort !</p>
        </div>
    </div>

    <?php if ($username): ?>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p><a href="logout.php" class="btn">Logout</a></p>
    <?php else: ?>
        <p>You are not logged in.</p>
        <p><a href="login.php" class="btn">Login</a> or <a href="register.php" class="btn">Register</a> to access your account.</p>
    <?php endif; ?>

    <section class="promotions">
        <div class="promo-card">
            <div class="promo-image" style="background-image: url('1.avif');"></div>
            <div class="promo-content">
                <h3 class="promo-title">Winter Sale</h3>
                <p class="promo-description">Get up to 50% off on winter collections!</p>
                <button class="promo-btn">Shop Now</button>
            </div>
        </div>

        <div class="promo-card">
            <div class="promo-image" style="background-image: url('3.avif');"></div>
            <div class="promo-content">
                <h3 class="promo-title">New Arrivals</h3>
                <p class="promo-description">Explore our latest trendy styles!</p>
                <button class="promo-btn">Explore</button>
            </div>
        </div>

        <div class="promo-card">
            <div class="promo-image" style="background-image: url('3.jpg');"></div>
            <div class="promo-content">
                <h3 class="promo-title">Exclusive Deals</h3>
                <p class="promo-description">Limited-time offers you don't want to miss!</p>
                <button class="promo-btn">View Deals</button>
            </div>
        </div>
    </section>
</body>
</html>
