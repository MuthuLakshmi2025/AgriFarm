<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "green_harvest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM signup_users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed);
        $stmt->fetch();

        if (password_verify($pass, $hashed)) {
            $_SESSION['username'] = $user;

            // Insert login record
            $log = $conn->prepare("INSERT INTO login_records (username) VALUES (?)");
            $log->bind_param("s", $user);
            $log->execute();

            echo "<script>alert('Login successful!'); window.location.href='shop.html';</script>";
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
