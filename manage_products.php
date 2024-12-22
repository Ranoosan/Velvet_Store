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

// Handle the delete product functionality
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="main_styles.css"> <!-- Link to your main CSS file -->
</head>
<style>
    
</style>
<body>
    <!-- Admin Sidebar (Include separately) -->
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <h1>Manage Products</h1>

        <table border="2">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Gender</th>
                    <th>Clothing Type</th>
                    <th>Age Group</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Brand</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each product
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['product_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . ($row['gender'] == 1 ? 'Men' : 'Women') . "</td>";
                        echo "<td>" . $row['clothing_type'] . "</td>";
                        echo "<td>" . $row['age_group'] . "</td>";
                        echo "<td>" . $row['size'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['brand'] . "</td>";
                        echo "<td><img src='" . $row['image'] . "' alt='product image' width='100'></td>";
                        echo "<td>
                                <a href='edit_product.php?id=" . $row['product_id'] . "'>Edit</a> |
                                <a href='manage_products.php?delete_id=" . $row['product_id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a> |
                                <a href='copy_product.php?id=" . $row['product_id'] . "'>Copy</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>
