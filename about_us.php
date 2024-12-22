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

    <title>About Us - Velvet Vogue</title>
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

        .about-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 40px;
            gap: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transform: scale(1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 24px;
            color: #333;
        }

        .card-text {
            font-size: 16px;
            color: #777;
        }

        .section-title {
            font-size: 30px;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="hero-image">
        <div class="hero-text">
            <h1>About Velvet Vogue</h1>
            <p>Where style meets elegance and comfort</p>
        </div>
    </div>

    <section class="about-container">
        <div class="section-title">
            <h2>Our Story</h2>
        </div>
        <div class="card" data-aos="fade-up" data-aos-duration="1000">
            <img src="4.avif" alt="Our Story">
            <div class="card-body">
                <h3 class="card-title">Who We Are</h3>
                <p class="card-text">At Velvet Vogue, we are passionate about curating the perfect blend of fashion, comfort, and affordability. Our goal is to offer high-quality, trendy clothing for young adults who want to express their unique identity through their personal style.</p>
            </div>
        </div>

        <div class="section-title">
            <h2>Our Vision</h2>
        </div>
        <div class="card" data-aos="fade-up" data-aos-duration="1000">
            <img src="fashion-aesthetics-nm.png" alt="Vision">
            <div class="card-body">
                <h3 class="card-title">What Drives Us</h3>
                <p class="card-text">Our vision is to revolutionize the way young adults shop for clothing by offering a wide variety of stylish options that cater to every personality and preference. We aim to provide the best online shopping experience with seamless transactions and customer support.</p>
            </div>
        </div>
    </section>

    <?php if ($username): ?>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p><a href="logout.php" class="btn">Logout</a></p>
    <?php else: ?>
        <p>You are not logged in.</p>
        <p><a href="login.php" class="btn">Login</a> or <a href="register.php" class="btn">Register</a> to access your account.</p>
    <?php endif; ?>

    <footer>
        <p>&copy; 2024 Velvet Vogue. All rights reserved.</p>
    </footer>

    <script>
        AOS.init();
    </script>
</body>
</html>
