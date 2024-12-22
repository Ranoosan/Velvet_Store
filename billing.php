
<?php
// Include the navbar
include('navbar.php');
?>
<?php
// Ensure that product ID is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

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

    // Prepare SQL query to get product details based on product ID
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if product exists
    if ($result->num_rows > 0) {
        // Fetch the product details
        $row = $result->fetch_assoc();

        // Extract product details
        $product_name = $row['name'];
        $product_description = $row['description'];
        $product_price = $row['price'];

        // HTML and CSS for the billing UI
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Billing</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f9;
                }
                .billing-container {
                    max-width: 600px;
                    margin: 50px auto;
                    background: #ffffff;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                    border-radius: 8px;
                    overflow: hidden;
                }
                .product-header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 20px;
                    text-align: center;
                    font-size: 1.5rem;
                }
                .product-details {
                    padding: 20px;
                }
                .product-details p {
                    margin: 10px 0;
                    font-size: 1.1rem;
                    color: #555;
                }
                .product-details .price {
                    font-size: 1.3rem;
                    color: #000;
                }
                .form-section {
                    padding: 20px;
                    border-top: 1px solid #ddd;
                    text-align: center;
                }
                .form-section input[type="number"] {
                    padding: 10px;
                    width: 80%;
                    margin-bottom: 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    font-size: 1rem;
                }
                .form-section input[type="submit"], .form-section .payment-button {
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    font-size: 1rem;
                    border-radius: 4px;
                    cursor: pointer;
                    margin: 10px;
                }
                .form-section input[type="submit"]:hover, .form-section .payment-button:hover {
                    background-color: #45a049;
                }
                .total-bill {
                    padding: 20px;
                    font-size: 1.3rem;
                    color: #333;
                    text-align: center;
                    background-color: #f9f9f9;
                }
            </style>
        </head>
        <body>
            <div class="billing-container">
                <div class="product-header">
                    Billing Details
                </div>
                <div class="product-details">
                    <p><strong>Product Name:</strong> ' . htmlspecialchars($product_name) . '</p>
                    <p><strong>Description:</strong> ' . htmlspecialchars($product_description) . '</p>
                    <p class="price"><strong>Price:</strong> ₹' . number_format($product_price, 2) . '</p>
                </div>
                <div class="form-section">
                    <form method="POST" action="">
                        <label for="quantity"><strong>Enter Quantity:</strong></label><br>
                        <input type="number" name="quantity" id="quantity" min="1" required><br>
                        <input type="submit" name="calculate" value="Calculate Bill">
                    </form>
                </div>';

        // If quantity is submitted, calculate the final bill
        if (isset($_POST['calculate'])) {
            $quantity = $_POST['quantity'];
            $total_bill = $product_price * $quantity;

            // Display the final bill amount and payment button
            echo '<div class="total-bill">
                    <strong>Total Bill Amount: ₹' . number_format($total_bill, 2) . '</strong>
                  </div>
                  <div class="form-section">
                      <form method="GET" action="payment.php">
                          <input type="hidden" name="product_id" value="' . $product_id . '">
                          <input type="hidden" name="product_name" value="' . htmlspecialchars($product_name) . '">
                          <input type="hidden" name="total_bill" value="' . $total_bill . '">
                          <button type="submit" class="payment-button">Proceed to Payment</button>
                      </form>
                  </div>';
        }

        echo '</div>
        </body>
        </html>';
    } else {
        echo "No product found with ID: " . $product_id;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Product ID is missing.";
}
?>
