<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the index page
        header("Location: Inventory.php");
        exit();
    } else {
        echo "Error deleting product.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    $conn->close();
    exit();
}
?>
