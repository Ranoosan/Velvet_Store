<?php
// Include the navbar
include('navbar.php');
?>
<?php
// Ensure that product ID and other details are passed in the URL
if (isset($_GET['product_id'], $_GET['product_name'], $_GET['total_bill'])) {
    $product_id = $_GET['product_id'];
    $product_name = $_GET['product_name'];
    $total_bill = $_GET['total_bill'];

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

    // Fetch the product details (optional, if needed for any other validation or display)
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product exists
    if ($result->num_rows > 0) {
        // Fetch the product details (for confirmation or display purposes)
        $row = $result->fetch_assoc();
        $product_description = $row['description'];
        $product_price = $row['price'];
    } else {
        echo "Product not found.";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get payment method and additional details
        $payment_method = $_POST['payment_method'];

        // Prepare values for insertion
        $card_holder_name = $payment_method == 'online' ? $_POST['card_holder_name'] : NULL;
        $card_number = $payment_method == 'online' ? $_POST['card_number'] : NULL;
        $expiry_date = $payment_method == 'online' ? $_POST['expiry_date'] : NULL;
        $cvv = $payment_method == 'online' ? $_POST['cvv'] : NULL;
        $contact_number = $payment_method == 'cod' ? $_POST['contact_number'] : NULL;
        $delivery_address = $payment_method == 'cod' ? $_POST['delivery_address'] : NULL;

        // Insert order into the database
        $insert_sql = "INSERT INTO orders (product_id, product_name, total_bill, payment_method, card_holder_name, card_number, expiry_date, cvv, contact_number, delivery_address) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssssssss", $product_id, $product_name, $total_bill, $payment_method, $card_holder_name, $card_number, $expiry_date, $cvv, $contact_number, $delivery_address);
        $stmt->execute();

        // Get the inserted order ID
        $order_id = $stmt->insert_id;

        // Show order summary
        echo "<div class='order-summary'>
                <h3>Order Summary</h3>
                <p><strong>Order ID:</strong> $order_id</p>
                <p><strong>Product Name:</strong> $product_name</p>
                <p><strong>Total Bill:</strong> ₹" . number_format($total_bill, 2) . "</p>
                <p><strong>Payment Method:</strong> $payment_method</p>";

        if ($payment_method == 'online') {
            echo "<p><strong>Card Holder Name:</strong> $card_holder_name</p>
                  <p><strong>Card Number:</strong> $card_number</p>
                  <p><strong>Expiry Date:</strong> $expiry_date</p>
                  <p><strong>CVV:</strong> $cvv</p>";
        } else {
            echo "<p><strong>Contact Number:</strong> $contact_number</p>
                  <p><strong>Delivery Address:</strong> $delivery_address</p>";
        }

        echo "</div>";
        exit; // Stop further execution of the page after displaying order summary
    }

    // HTML and CSS for the payment UI
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f9;
            }
            .payment-container {
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
            .form-section input[type="text"], .form-section input[type="number"], .form-section input[type="tel"], .form-section select {
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
        <div class="payment-container">
            <div class="product-header">
                Payment Details
            </div>
            <div class="product-details">
                <p><strong>Product Name:</strong> ' . htmlspecialchars($product_name) . '</p>
                <p><strong>Description:</strong> ' . htmlspecialchars($product_description) . '</p>
                <p class="price"><strong>Total Bill:</strong> ₹' . number_format($total_bill, 2) . '</p>
            </div>
            <div class="form-section">
                <form method="POST" action="">
                    <label for="payment_method"><strong>Payment Method:</strong></label><br>
                    <label><input type="radio" name="payment_method" value="online" required> Online Payment</label>
                    <label><input type="radio" name="payment_method" value="cod"> Cash on Delivery</label><br><br>

                    <!-- Online Payment Fields -->
                    <div id="online-fields" style="display: none;">
                        <label for="card_holder_name"><strong>Card Holder Name:</strong></label><br>
                        <input type="text" name="card_holder_name" id="card_holder_name" required><br>
                        <label for="card_number"><strong>Card Number:</strong></label><br>
                        <input type="number" name="card_number" id="card_number" required><br>
                        <label for="expiry_date"><strong>Expiry Date (MM/YYYY):</strong></label><br>
                        <input type="text" name="expiry_date" id="expiry_date" required><br>
                        <label for="cvv"><strong>CVV:</strong></label><br>
                        <input type="number" name="cvv" id="cvv" required><br>
                    </div>

                    <!-- Cash on Delivery Fields -->
                    <div id="cod-fields" style="display: none;">
                        <label for="contact_number"><strong>Contact Number:</strong></label><br>
                        <input type="tel" name="contact_number" id="contact_number" required><br>
                        <label for="delivery_address"><strong>Delivery Address:</strong></label><br>
                        <textarea name="delivery_address" id="delivery_address" required></textarea><br>
                    </div>

                    <input type="submit" value="Proceed with Payment">
                </form>
            </div>
        </div>
    </body>
    </html>';
} else {
    echo "Required details are missing.";
}
?>
<script>
    // Show/hide payment fields based on selected payment method
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // If 'Online Payment' is selected, show the online payment fields
            if (this.value === 'online') {
                document.getElementById('online-fields').style.display = 'block';
                document.getElementById('cod-fields').style.display = 'none';
            }
            // If 'Cash on Delivery' is selected, show the COD fields
            else if (this.value === 'cod') {
                document.getElementById('cod-fields').style.display = 'block';
                document.getElementById('online-fields').style.display = 'none';
            }
        });
    });
    
    // Initially hide both sets of fields
    document.getElementById('online-fields').style.display = 'none';
    document.getElementById('cod-fields').style.display = 'none';
</script>
