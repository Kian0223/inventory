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
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];

// Check if user already exists
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User already exists, redirect to sign-in page
    header("Location: index.php?signin=true&error=User already exists. Please sign in.");
    exit();
} else {
    // User does not exist, insert new user into database
    $sql = "INSERT INTO users (first_name, last_name, email, phone, username, password)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // User successfully registered, redirect to inventory page
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>