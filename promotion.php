<?php
// Start the session
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cloths_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all promotions (active or inactive) from the database
$sql = "SELECT * FROM promotions";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Promotions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .promotion-container {
            padding: 50px 20px;
            text-align: center;
        }

        .promotion-container h1 {
            font-size: 3rem;
            color: #333;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .promotion-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .promotion-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .promotion-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .promotion-item h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
        }

        .promotion-item p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
        }

        .promotion-item .discount {
            font-size: 1.5rem;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .promotion-item img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 15px;
        }

        .promotion-item .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .promotion-item .btn:hover {
            background-color: #0056b3;
        }
    </style>
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to your main CSS file -->
</head>

<body>
    <!-- Include navbar (if any) -->
    <?php include('navbar.php'); ?>

    <div class="promotion-container">
        <h1>All Promotions</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="promotion-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="promotion-item">
                        <h2><?php echo $row['name']; ?></h2>
                        <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                        <p class="discount"><strong>Discount:</strong> <?php echo $row['discount']; ?>%</p>
                        <p><strong>Start Date:</strong> <?php echo $row['start_date']; ?></p>
                        
                        <!-- Check if image exists, if yes, display it -->
                        <?php if ($row['image']): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Promotion Image">

                        <?php else: ?>
                            <p>No image available for this promotion.</p>
                        <?php endif; ?>
                        
                        <a href="shop.php" class="btn">Shop Now</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No promotions available at the moment.</p>
        <?php endif; ?>
    </div>
</body>

</html>
