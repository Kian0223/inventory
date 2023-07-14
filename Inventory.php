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
            text-align: right;
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
            margin-right: 20px;
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
        <h2>Product Management (ADMIN)</h2>
        <div class="signout-container">
            <form action="index.php" method="get">
                <button type="submit" name="signout">Sign Out</button>
            </form>
        </div>
        <form action="process.php" method="post">
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" required>
            </div>
            <div class="form-group">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="destination">Destination:</label>
                <select id="destination" name="destination">
                    <option value="k1">K1</option>
                    <option value="kishii">Kishii</option>
                    <option value="garios">Garios</option>
                    <option value="wowmoto">Wowmoto</option>
                    <option value="bodega_home">Bodega Home</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Total Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Product</button>
            </div>
            <div class="form-group">
                <button type="button" onclick="window.location.href='add_product.php'">Scan Barcode</button>
            </div>
        </form>

        <h2>Product List</h2>
        <table>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Size</th>
                <th>SKU</th>
                <th>Price</th>
                <th>K1</th>
                <th>Kishii</th>
                <th>Garios</th>
                <th>Wowmoto</th>
                <th>Bodega Home</th>
                <th>Total Quantity</th>
                <th>Actions</th>
            </tr>
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['quantity']) && isset($_POST['sku'])) {
                    $quantity = $_POST['quantity'];
                    $sku = $_POST['sku'];

                    // Update quantity in the database
                    $sql = "UPDATE products SET quantity = quantity + $quantity WHERE sku = '$sku'";
                    $result = $conn->query($sql);
                }
            }

            // Retrieve data from the database
            $sql = "SELECT *, (k1 + kishii + garios + wowmoto + bodega_home) AS total_quantity FROM products ORDER BY brand ASC, model ASC, CASE 
                WHEN size = 'M' THEN 1
                WHEN size = 'L' THEN 2
                WHEN size = 'XL' THEN 3
                WHEN size = 'XXL' THEN 4
                ELSE 5
                END ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['brand'] . "</td>";
                    echo "<td>" . $row['model'] . "</td>";
                    echo "<td>" . $row['size'] . "</td>";
                    echo "<td>" . $row['sku'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['k1'] . "</td>";
                    echo "<td>" . $row['kishii'] . "</td>";
                    echo "<td>" . $row['garios'] . "</td>";
                    echo "<td>" . $row['wowmoto'] . "</td>";
                    echo "<td>" . $row['bodega_home'] . "</td>";
                    echo "<td>" . $row['total_quantity'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a> | ";
                    echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No products found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
