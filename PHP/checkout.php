<?php
// Database connection
$servername = "localhost";
$username = "root";   // default for XAMPP
$password = "";       // leave empty if no password
$dbname = "green_harvest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$postal_code = $_POST['postal_code'];
$country = $_POST['country'];
$payment_method = $_POST['payment_method'];
$total_amount = $_POST['total_amount'];

// Insert into orders table
$order_sql = "INSERT INTO orders 
  (first_name, last_name, email, phone, address, city, state, postal_code, country, payment_method, total_amount)
  VALUES 
  ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$state', '$postal_code', '$country', '$payment_method', '$total_amount')";

if ($conn->query($order_sql) === TRUE) {
    $order_id = $conn->insert_id;

    // Get order items from JSON (sent by JS)
    $order_items = json_decode($_POST['order_items'], true);

    foreach ($order_items as $item) {
        $product_name = $conn->real_escape_string($item['name']);
        $price = $item['price'];
        $qty = $item['qty'];

        $conn->query("INSERT INTO order_items (order_id, product_name, price, quantity)
                      VALUES ('$order_id', '$product_name', '$price', '$qty')");
    }

    echo "success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
