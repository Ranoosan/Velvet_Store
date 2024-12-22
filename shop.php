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

// Fetch products based on filters
$where_clauses = [];
$params = [];
$types = "";

if (!empty($_GET['category'])) {
    $where_clauses[] = "category = ?";
    $params[] = $_GET['category'];
    $types .= "s";
}
if (!empty($_GET['gender'])) {
    $where_clauses[] = "gender = ?";
    $params[] = $_GET['gender'];
    $types .= "s";
}
if (!empty($_GET['clothing_type'])) {
    $where_clauses[] = "clothing_type = ?";
    $params[] = $_GET['clothing_type'];
    $types .= "s";
}
if (!empty($_GET['age_group'])) {
    $where_clauses[] = "age_group = ?";
    $params[] = $_GET['age_group'];
    $types .= "s";
}
if (!empty($_GET['size'])) {
    $where_clauses[] = "size = ?";
    $params[] = $_GET['size'];
    $types .= "s";
}
if (!empty($_GET['color'])) {
    $where_clauses[] = "color = ?";
    $params[] = $_GET['color'];
    $types .= "s";
}
if (!empty($_GET['brand'])) {
    $where_clauses[] = "brand = ?";
    $params[] = $_GET['brand'];
    $types .= "s";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
$sql = "SELECT * FROM products $where_sql";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('navbar.php'); ?>
    <title>Shop</title>

    <script>
        // JavaScript function to alert the user
        function requireLogin() {
            const login = confirm("You must be logged in to purchase products. Click OK to log in or Cancel to go back to the home page.");
            if (login) {
                window.location.href = "login.php"; // Redirect to login page
            } else {
                window.location.href = "index.php"; // Redirect to home page
            }
        }
    </script>
</head>

<style>
        /* General styles for the body and layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2,
        h3 {
            font-size: 1.5rem;
            color: #333;
            text-align: center;
        }

        h3 {
            font-size: 1.2rem;
        }

        /* Main container for the entire page */
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

/* Filter section styles */
.filter-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px;
}

.filter-section h2 {
    flex-basis: 100%;
    font-size: 1.6rem;
    color: #4CAF50;
    margin-bottom: 10px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
    min-width: 150px;
}

.filter-section label {
    font-size: 0.9rem;
    color: #555;
}

.filter-section select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    background-color: #f9f9f9;
}

.filter-section button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    flex-shrink: 0;
}

.filter-section button:hover {
    background-color: #45a049;
}


        /* Product section styles */
        .products-section {
            padding: 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-card h3 {
            font-size: 1.2rem;
            margin-top: 15px;
            color: #333;
        }

        .product-card p {
            font-size: 1rem;
            color: #777;
            margin: 10px 0;
        }

        .product-card .add-to-cart-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .product-card .add-to-cart-btn:hover {
            background-color: #45a049;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .filter-section {
                padding: 15px;
            }

            .products-grid {
                grid-template-columns: 1fr 1fr;
            }

            .product-card {
                padding: 10px;
            }

            .product-card img {
                height: 180px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .filter-section {
                padding: 10px;
            }

            .product-card {
                padding: 8px;
            }
        }
    </style>
<body>
<div class="filter-section">
    <h2>Filter Products</h2>
    <div class="filter-group">
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">All</option>
            <option value="casualwear">Casualwear</option>
            <option value="formalwear">Formalwear</option>
            <option value="activewear">Activewear</option>
            <option value="outerwear">Outerwear</option>
            <option value="sleepwear">Sleepwear & Loungewear</option>
            <option value="swimwear">Swimwear</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="">All</option>
            <option value="men">Men</option>
            <option value="women">Women</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="clothing_type">Clothing Type:</label>
        <select id="clothing_type" name="clothing_type">
            <option value="">All</option>
            <option value="casualwear">Casual Wear</option>
            <option value="formalwear">Formal Wear</option>
            <option value="activewear">Activewear</option>
            <option value="outerwear">Outerwear</option>
            <option value="sleepwear">Sleepwear & Loungewear</option>
            <option value="swimwear">Swimwear</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="age_group">Age Group:</label>
        <select id="age_group" name="age_group">
            <option value="">All</option>
            <option value="adults">Adults</option>
            <option value="teens">Teens</option>
            <option value="kids">Kids</option>
            <option value="plus_size">Plus Size</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="size">Size:</label>
        <select id="size" name="size">
            <option value="">All</option>
            <option value="xs">XS</option>
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            <option value="xxl">XXL and Above</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="color">Color:</label>
        <select id="color" name="color">
            <option value="">All</option>
            <option value="black">Black</option>
            <option value="blue">Blue</option>
            <option value="red">Red</option>
            <option value="green">Green</option>
            <option value="yellow">Yellow</option>
            <option value="white">White</option>
            <option value="pink">Pink</option>
        </select>
    </div>
    <div class="filter-group">
        <label for="brand">Brand:</label>
        <select id="brand" name="brand">
            <option value="">All</option>
            <option value="nike">Nike</option>
            <option value="adidas">Adidas</option>
            <option value="puma">Puma</option>
            <option value="uniqlo">Uniqlo</option>
            <option value="h&m">H&M</option>
        </select>
    </div>
    <button type="submit">Apply Filters</button>
</div>


    <div class="products-section">
        <h3>Our Products</h3>
        <div class="products-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <form action="billing.php" method="GET">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    <?php } else { ?>
                        <button onclick="requireLogin()" class="add-to-cart-btn">Add to Cart</button>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>

<?php
// Close connection
$conn->close();
?>
