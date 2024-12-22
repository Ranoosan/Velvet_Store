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
    $discount = $_POST['discount'];
    $start_date = $_POST['start_date'];
    $status = $_POST['status'];

    // Handle the image upload
    if (isset($_FILES['promotion_image']) && $_FILES['promotion_image']['error'] == 0) {
        $image_name = $_FILES['promotion_image']['name'];
        $image_tmp_name = $_FILES['promotion_image']['tmp_name'];
        $image_size = $_FILES['promotion_image']['size'];
        $image_type = $_FILES['promotion_image']['type'];

        // Define allowed image types and max size (e.g., 2MB)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (in_array($image_type, $allowed_types) && $image_size <= $max_size) {
            $upload_dir = 'uploads/';
            $image_path = $upload_dir . basename($image_name);
            
            // Check if the file already exists
            if (!file_exists($image_path)) {
                if (move_uploaded_file($image_tmp_name, $image_path)) {
                    echo "Image uploaded successfully!<br>";
                } else {
                    echo "Error uploading image!<br>";
                }
            } else {
                echo "Image already exists!<br>";
            }
        } else {
            echo "Invalid file type or file size exceeds limit!<br>";
        }
    } else {
        echo "No image uploaded!<br>";
        $image_path = ''; // Set an empty string if no image is uploaded
    }

    // Insert the promotion into the database, including image path
    $sql = "INSERT INTO promotions (name, description, discount, start_date, status, image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsds", $name, $description, $discount, $start_date, $status, $image_path);

    if ($stmt->execute()) {
        echo "Promotion added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Promotion</title>
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to your main CSS file -->
</head>

<body>
    <!-- Admin Sidebar (Include separately) -->
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <h1>Add New Promotion</h1>
        <form action="add_promotion.php" method="POST" enctype="multipart/form-data">
            <label for="name">Promotion Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="description">Promotion Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>

            <label for="discount">Discount Percentage:</label>
            <input type="number" id="discount" name="discount" step="0.01" min="0" max="100" required><br><br>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br><br>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select><br><br>

            <!-- Image Upload -->
            <label for="promotion_image">Upload Promotion Image:</label>
            <input type="file" id="promotion_image" name="promotion_image" accept="image/*"><br><br>

            <button type="submit">Add Promotion</button>
        </form>
    </div>
</body>

</html>
