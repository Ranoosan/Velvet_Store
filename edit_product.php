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

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Retrieve the product details from the database
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "No product ID specified.";
    exit();
}

// Handle the form submission for updating product details
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

    // Handle file upload if a new image is uploaded
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $image_path = $product['image']; // Default to existing image

    if ($_FILES["image"]["name"]) {
        // Validate the image type (only allow certain types of images)
        $allowed_types = ["jpg", "png", "jpeg"];
        if (!in_array($image_file_type, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
            exit();
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file; // Update with new image path
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Update product in the database
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, gender = ?, clothing_type = ?, age_group = ?, size = ?, color = ?, brand = ?, image = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsdssssssi", $name, $description, $price, $category, $gender, $clothing_type, $age_group, $size, $color, $brand, $image_path, $product_id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $stmt->error;
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to your main CSS file -->
</head>

<body>
    <!-- Admin Sidebar (Include separately) -->
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <h1>Edit Product</h1>

        <form action="edit_product.php?id=<?php echo $product['product_id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br><br>

            <label for="description">Product Description:</label>
            <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br><br>

            <label for="price">Product Price:</label>
            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required><br><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="casualwear" <?php echo ($product['category'] == 'casualwear' ? 'selected' : ''); ?>>Casualwear</option>
                <option value="formalwear" <?php echo ($product['category'] == 'formalwear' ? 'selected' : ''); ?>>Formalwear</option>
                <option value="activewear" <?php echo ($product['category'] == 'activewear' ? 'selected' : ''); ?>>Activewear</option>
                <option value="outerwear" <?php echo ($product['category'] == 'outerwear' ? 'selected' : ''); ?>>Outerwear</option>
                <option value="sleepwear" <?php echo ($product['category'] == 'sleepwear' ? 'selected' : ''); ?>>Sleepwear & Loungewear</option>
                <option value="swimwear" <?php echo ($product['category'] == 'swimwear' ? 'selected' : ''); ?>>Swimwear</option>
            </select><br><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="1" <?php echo ($product['gender'] == 1 ? 'selected' : ''); ?>>Men</option>
                <option value="2" <?php echo ($product['gender'] == 2 ? 'selected' : ''); ?>>Women</option>
            </select><br><br>

            <label for="clothing_type">Clothing Type:</label>
            <select id="clothing_type" name="clothing_type" required>
                <option value="casualwear" <?php echo ($product['clothing_type'] == 'casualwear' ? 'selected' : ''); ?>>Casual Wear</option>
                <option value="formalwear" <?php echo ($product['clothing_type'] == 'formalwear' ? 'selected' : ''); ?>>Formal Wear</option>
                <option value="activewear" <?php echo ($product['clothing_type'] == 'activewear' ? 'selected' : ''); ?>>Activewear</option>
                <option value="outerwear" <?php echo ($product['clothing_type'] == 'outerwear' ? 'selected' : ''); ?>>Outerwear</option>
                <option value="sleepwear" <?php echo ($product['clothing_type'] == 'sleepwear' ? 'selected' : ''); ?>>Sleepwear & Loungewear</option>
                <option value="swimwear" <?php echo ($product['clothing_type'] == 'swimwear' ? 'selected' : ''); ?>>Swimwear</option>
            </select><br><br>

            <label for="age_group">Age Group:</label>
            <select id="age_group" name="age_group" required>
                <option value="adults" <?php echo ($product['age_group'] == 'adults' ? 'selected' : ''); ?>>Adults</option>
                <option value="teens" <?php echo ($product['age_group'] == 'teens' ? 'selected' : ''); ?>>Teens</option>
                <option value="kids" <?php echo ($product['age_group'] == 'kids' ? 'selected' : ''); ?>>Kids</option>
                <option value="plus_size" <?php echo ($product['age_group'] == 'plus_size' ? 'selected' : ''); ?>>Plus Size</option>
            </select><br><br>

            <label for="size">Size:</label>
            <select id="size" name="size" required>
                <option value="xs" <?php echo ($product['size'] == 'xs' ? 'selected' : ''); ?>>XS</option>
                <option value="s" <?php echo ($product['size'] == 's' ? 'selected' : ''); ?>>S</option>
                <option value="m" <?php echo ($product['size'] == 'm' ? 'selected' : ''); ?>>M</option>
                <option value="l" <?php echo ($product['size'] == 'l' ? 'selected' : ''); ?>>L</option>
                <option value="xl" <?php echo ($product['size'] == 'xl' ? 'selected' : ''); ?>>XL</option>
                <option value="xxl" <?php echo ($product['size'] == 'xxl' ? 'selected' : ''); ?>>XXL and Above</option>
                <option value="petite" <?php echo ($product['size'] == 'petite' ? 'selected' : ''); ?>>Petite</option>
            </select><br><br>

            <label for="color">Color:</label>
            <input type="text" id="color" name="color" value="<?php echo $product['color']; ?>" required><br><br>

            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" value="<?php echo $product['brand']; ?>" required><br><br>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image"><br><br>

            <input type="submit" value="Update Product">
        </form>
    </div>
</body>

</html>
