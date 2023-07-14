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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind the updated data
    $id = $_POST['id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $k1Quantity = $_POST['k1'];
    $kishiiQuantity = $_POST['kishii'];
    $gariosQuantity = $_POST['garios'];
    $wowmotoQuantity = $_POST['wowmoto'];
    $bodegaHomeQuantity = $_POST['bodega_home'];
    $sku = $_POST['sku'];

    if (!preg_match("/^[a-zA-Z]+$/", $brand)) {
        echo "Invalid Brand value. Only letters are allowed.";
        $conn->close();
        exit();
    }

    if (!preg_match("/^[a-zA-Z]+$/", $size)) {
        echo "Invalid Size value. Only letters are allowed.";
        $conn->close();
        exit();
    }

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE products SET brand = ?, model = ?, size = ?, price = ?, k1 = ?, kishii = ?, garios = ?, wowmoto = ?, bodega_home = ?, sku = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $brand, $model, $size, $price, $k1Quantity, $kishiiQuantity, $gariosQuantity, $wowmotoQuantity, $bodegaHomeQuantity, $sku, $id);

    if ($stmt->execute()) {
        // Redirect back to the inventory page
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error updating product.";
    }

    $stmt->close();
} else {
    // Check if the ID parameter is provided in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Retrieve the product data from the database
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $brand = $row['brand'];
            $model = $row['model'];
            $size = $row['size'];
            $price = $row['price'];
            $k1Quantity = $row['k1'];
            $kishiiQuantity = $row['kishii'];
            $gariosQuantity = $row['garios'];
            $wowmotoQuantity = $row['wowmoto'];
            $bodegaHomeQuantity = $row['bodega_home'];
            $sku = $row['sku'];
        } else {
            echo "Product not found.";
            $stmt->close();
            $conn->close();
            exit();
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
        $conn->close();
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
                body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .form-group button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" value="<?php echo $brand; ?>" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo $model; ?>" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" value="<?php echo $size; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo $price; ?>" required>
            </div>
            <div class="form-group">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" value="<?php echo $sku; ?>" required>
            </div>
            <div class="form-group">
                <label for="k1">K1:</label>
                <input type="number" id="k1" name="k1" value="<?php echo $k1Quantity; ?>" required>
            </div>
            <div class="form-group">
                <label for="kishii">Kishii:</label>
                <input type="number" id="kishii" name="kishii" value="<?php echo $kishiiQuantity; ?>" required>
            </div>
            <div class="form-group">
                <label for="garios">Garios:</label>
                <input type="number" id="garios" name="garios" value="<?php echo $gariosQuantity; ?>" required>
            </div>
            <div class="form-group">
                <label for="wowmoto">Wowmoto:</label>
                <input type="number" id="wowmoto" name="wowmoto" value="<?php echo $wowmotoQuantity; ?>" required>
            </div>
            <div class="form-group">
                <label for="bodega_home">Bodega Home:</label>
                <input type="number" id="bodega_home" name="bodega_home" value="<?php echo $bodegaHomeQuantity; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Product</button>
            </div>
        </form>
    </div>
</body>
</html>