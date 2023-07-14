<?php
$servername = "localhost";
$username = "root"; // Replace with your phpMyAdmin username
$password = ""; // Replace with your phpMyAdmin password
$dbname = "inventory"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$username = $_POST['username'];
$password = $_POST['password'];

// Verify user credentials
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User credentials are correct, redirect to inventory page
    header("Location: inventory.php");
    exit();
} else {
    // User credentials are incorrect, redirect back to sign-in page with an error message
    header("Location: index.php?error=Invalid username or password. Please try again.");
    exit();
}

$conn->close();
?>