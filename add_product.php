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

// Check if the form was submitted
if (isset($_POST['addProduct'])) {
    $sku = $_POST['sku'];
    $destination = $_POST['destination'];

    // Check if the SKU already exists in the database
    $existingProduct = $conn->prepare("SELECT * FROM products WHERE sku = ?");
    $existingProduct->bind_param("s", $sku);
    $existingProduct->execute();
    $existingProductResult = $existingProduct->get_result();

    if ($existingProductResult->num_rows > 0) {
        // SKU already exists, update the quantity of the selected destination
        $existingProductData = $existingProductResult->fetch_assoc();
        $existingProductId = $existingProductData['id'];
        $newQuantity = $existingProductData[$destination] + 1;

        $updateQuantity = $conn->prepare("UPDATE products SET $destination = ? WHERE id = ?");
        $updateQuantity->bind_param("ii", $newQuantity, $existingProductId);
        $updateQuantity->execute();

        if ($updateQuantity->affected_rows > 0) {
            // Success, redirect back to the product management page
            header("Location: add_product.php");
            exit();
        } else {
            // Error, redirect back to the product management page with an error message
            header("Location: add_product.php?error=Failed to add the product. Please try again.");
            exit();
        }
    } else {
        // SKU does not exist, display an error message
        echo "<p class='error'>The product with SKU $sku is not encoded yet. Please encode the product first.</p>";
    }

    $existingProduct->close();
    $updateQuantity->close();
}

$selectedDestination = isset($_POST['destination']) ? $_POST['destination'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 1100px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Styles for the Sign Out button container */
    .signout-container {
        text-align: left;
        margin-bottom: 20px;
    }

    .signout-container button {
        padding: 10px 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .signout-container button:hover {
        background-color: #45a049;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .form-group.button-group {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }

    .form-group .button-group button {
        margin-left: 0;
    }

    .form-group .error {
        color: red;
    }

    .form-group .success {
        color: green;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table tr:nth-child(odd) {
        background-color: #ddd;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>ADD PRODUCT (ADMIN)</h2>
        <div class="signout-container">
            <form action="add_product.php" method="post">
                <div class="form-group">
                    <button type="button" onclick="window.location.href='inventory.php'">Back</button>
                </div>
                <div class="form-group flex-container">
                    <label for="sku">SKU:</label>
                    <input type="text" id="sku" name="sku" required autofocus>
                </div>
                <div class="form-group flex-container">
                    <label for="destination">Destination:</label>
                    <select id="destination" name="destination">
                        <option value="k1" <?php if ($selectedDestination === 'k1') echo 'selected'; ?>>K1</option>
                        <option value="kishii" <?php if ($selectedDestination === 'kishii') echo 'selected'; ?>>Kishii</option>
                        <option value="garios" <?php if ($selectedDestination === 'garios') echo 'selected'; ?>>Garios</option>
                        <option value="wowmoto" <?php if ($selectedDestination === 'wowmoto') echo 'selected'; ?>>Wowmoto</option>
                        <option value="bodega_home" <?php if ($selectedDestination === 'bodega_home') echo 'selected'; ?>>Bodega Home</option>
                    </select>
                </div>
                <div class="form-group flex-container">
                    <button type="submit" name="addProduct">Add Product</button>
                </div>
            </form>
        </div>

        <?php
        // Retrieve data from the database
        $sql = "SELECT * FROM products ORDER BY brand ASC, model ASC, CASE 
                WHEN size = 'M' THEN 1
                WHEN size = 'L' THEN 2
                WHEN size = 'XL' THEN 3
                WHEN size = 'XXL' THEN 4
                ELSE 5
                END ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Product List</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Brand</th>";
            echo "<th>Model</th>";
            echo "<th>Size</th>";
            echo "<th>SKU</th>";
            echo "<th>K1</th>";
            echo "<th>Kishii</th>";
            echo "<th>Garios</th>";
            echo "<th>Wowmoto</th>";
            echo "<th>Bodega Home</th>";
            echo "<th>Total Quantity</th>";
            echo "<th>Actions</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['brand'] . "</td>";
                echo "<td>" . $row['model'] . "</td>";
                echo "<td>" . $row['size'] . "</td>";
                echo "<td>" . $row['sku'] . "</td>";
                echo "<td>" . $row['k1'] . "</td>";
                echo "<td>" . $row['kishii'] . "</td>";
                echo "<td>" . $row['garios'] . "</td>";
                echo "<td>" . $row['wowmoto'] . "</td>";
                echo "<td>" . $row['bodega_home'] . "</td>";
                echo "<td>" . ($row['k1'] + $row['kishii'] + $row['garios'] + $row['wowmoto'] + $row['bodega_home']) . "</td>";
                echo "<td>";
                echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a> | ";
                echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No products found</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>