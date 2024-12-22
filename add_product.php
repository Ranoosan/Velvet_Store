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

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $gender = $_POST['gender'];
    $clothing_type = $_POST['clothing_type'];
    $age_group = $_POST['age_group'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate the image type (only allow certain types of images)
    $allowed_types = ["jpg", "png", "jpeg"];
    if (!in_array($image_file_type, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        exit();
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert the product into the database
        $sql = "INSERT INTO products (name, description, price, category, gender, clothing_type, age_group, size, color, brand, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsdssssss", $name, $description, $price, $category, $gender, $clothing_type, $age_group, $size, $color, $brand, $target_file);

        if ($stmt->execute()) {
            echo "New product added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to your main CSS file -->
</head>

<body>
    <!-- Admin Sidebar (Include separately) -->
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <h1>Add New Product</h1>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="description">Product Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>

            <label for="price">Product Price:</label>
            <input type="number" id="price" name="price" step="0.01" required><br><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="casualwear">Casualwear</option>
                <option value="formalwear">Formalwear</option>
                <option value="activewear">Activewear</option>
                <option value="outerwear">Outerwear</option>
                <option value="sleepwear">Sleepwear & Loungewear</option>
                <option value="swimwear">Swimwear</option>
            </select><br><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="1">Men</option>
                <option value="2">Women</option>
            </select><br><br>



            <label for="clothing_type">Clothing Type:</label>
            <select id="clothing_type" name="clothing_type" required>
                <option value="casualwear">Casual Wear</option>
                <option value="formalwear">Formal Wear</option>
                <option value="activewear">Activewear</option>
                <option value="outerwear">Outerwear</option>
                <option value="sleepwear">Sleepwear & Loungewear</option>
                <option value="swimwear">Swimwear</option>
            </select><br><br>

            <label for="age_group">Age Group:</label>
            <select id="age_group" name="age_group" required>
                <option value="adults">Adults</option>
                <option value="teens">Teens</option>
                <option value="kids">Kids</option>
                <option value="plus_size">Plus Size</option>
            </select><br><br>

            <label for="size">Size:</label>
            <select id="size" name="size" required>
                <option value="xs">XS</option>
                <option value="s">S</option>
                <option value="m">M</option>
                <option value="l">L</option>
                <option value="xl">XL</option>
                <option value="xxl">XXL and Above</option>
                <option value="petite">Petite</option>
                <option value="tall">Tall</option>
                <option value="plus_size">Plus Size</option>
            </select><br><br>

            <label for="color">Color:</label>
            <select id="color" name="color" required>
                <option value="black">Black</option>
                <option value="white">White</option>
                <option value="red">Red</option>
                <option value="blue">Blue</option>
                <option value="green">Green</option>
                <option value="neutral">Neutral Tones</option>
            </select><br><br>

            <label for="brand">Brand:</label>
            <select id="brand" name="brand" required>
                <option value="brand1">Brand 1</option>
                <option value="brand2">Brand 2</option>
                <option value="brand3">Brand 3</option>
            </select><br><br>

            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>

</html>