<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "green_harvest";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>
                alert('Please fill all required fields.');
                window.location.href='contact.html';
              </script>";
        exit();
    }

    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Your message has been sent successfully!');
                window.location.href='contact.html';
              </script>";
    } else {
        echo "<script>
                alert('❌ Error sending message. Please try again.');
                window.location.href='contact.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
