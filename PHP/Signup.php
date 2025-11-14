<?php
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
    $confirm_pass = trim($_POST['confirm_password']);

    if ($pass !== $confirm_pass) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    $check = $conn->prepare("SELECT id FROM signup_users WHERE username = ?");
    $check->bind_param("s", $user);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Username already exists.'); window.history.back();</script>";
        exit();
    }

    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO signup_users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $hashed);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error occurred during registration.');</script>";
    }

    $stmt->close();
    $conn->close();
}
