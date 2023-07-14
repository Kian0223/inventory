<?php
// Create connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
    $model = isset($_POST['model']) ? $_POST['model'] : '';
    $size = isset($_POST['size']) ? $_POST['size'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $sku = isset($_POST['sku']) ? $_POST['sku'] : '';
    $destination = isset($_POST['destination']) ? $_POST['destination'] : '';

    if (!empty($brand) && !empty($model) && !empty($size) && !empty($quantity) && !empty($sku) && !empty($destination)) {
        // Check if the SKU already exists in the database
        $existingProduct = $conn->prepare("SELECT * FROM products WHERE sku = ?");
        $existingProduct->bind_param("s", $sku);
        $existingProduct->execute();
        $existingProductResult = $existingProduct->get_result();

        if ($existingProductResult->num_rows > 0) {
            // SKU already exists, update the quantity of the existing product
            $existingProductData = $existingProductResult->fetch_assoc();
            $existingProductId = $existingProductData['id'];
            $newQuantity = $existingProductData['quantity'] + $quantity;

            $updateQuantity = $conn->prepare("UPDATE products SET quantity = ?, $destination = $destination + ? WHERE id = ?");
            $updateQuantity->bind_param("iii", $newQuantity, $quantity, $existingProductId);
            $updateQuantity->execute();

            if ($updateQuantity->affected_rows > 0) {
                // Success, redirect back to the product management page
                header("Location: Inventory.php");
                exit();
            } else {
                // Error, redirect back to the product management page with an error message
                header("Location: Inventory.php?error=Failed to add the product. Please try again.");
                exit();
            }
        } else {
            // SKU does not exist, insert the new product
            $insertProduct = $conn->prepare("INSERT INTO products (brand, model, size, quantity, sku, $destination) VALUES (?, ?, ?, ?, ?, ?)");
            $insertProduct->bind_param("sssssi", $brand, $model, $size, $quantity, $sku, $quantity);
            $insertProduct->execute();

            if ($insertProduct->affected_rows > 0) {
                // Success, redirect back to the product management page
                header("Location: Inventory.php");
                exit();
            } else {
                // Error, redirect back to the product management page with an error message
                header("Location: Inventory.php?error=Failed to add the product. Please try again.");
                exit();
            }
        }

        $existingProduct->close();
        $updateQuantity->close();
        $insertProduct->close();
    } else {
        // Form inputs are not set, redirect back to the product management page with an error message
        header("Location: Inventory.php?error=Invalid form inputs. Please try again.");
        exit();
    }
}

$conn->close();
?>
