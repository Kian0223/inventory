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
            max-width: 950px;
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
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 90%;
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

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:nth-child(odd) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<script>
    function downloadPDF() {
        // Redirect to a PHP script that generates and downloads the PDF
        window.location.href = "generate_pdf2.php";
    }
</script>
    <div class="container">
        <h3 style="text-align: right; font-weight: bold; font-size: 20px;">Hello Garios!</h3>
        <div class="signout-container">
            <form action="employee_login2.php" method="get">
                <button type="submit" name="signout">Sign Out</button>
            </form>
        </div>
        <h2>Product Management (Employee)</h2>
        <form action="restricted_inventory2.php" method="post">
            <div class="form-group">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required autofocus>
            </div>
            <div class="form-group">
                <button type="submit" name="sell">Sell</button>
            </div>
            <div class="form-group">
                <button type="button" onclick="window.location.href='stock_transfer2.php'">Stock Transfer</button>
            </div>
        </form>

        <div class="summary-container">
            <h3>Sold Items Summary (Garios)</h3>
            <button type="button" onclick="downloadPDF()">Download PDF</button>
            <table>
                <tr>
                    <th>SKU</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
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
                    if (isset($_POST['sell'])) {
                        $sku = $_POST['sku'];

                        // Retrieve data from the database to check if the SKU exists
                        $sql = "SELECT * FROM products WHERE sku = '$sku'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // The SKU exists, subtract 1 from the garios column
                            $updateSql = "UPDATE products SET garios = garios - 1 WHERE sku = '$sku'";
                            $updateResult = $conn->query($updateSql);

                            if ($updateResult === true) {
                                // Check if the SKU already exists in the summary table
                                $checkSql = "SELECT * FROM sold_items2 WHERE sku = '$sku'";
                                $checkResult = $conn->query($checkSql);

                                if ($checkResult->num_rows > 0) {
                                    // SKU already exists, update the quantity for the existing row
                                    $updateQuantitySql = "UPDATE sold_items2 SET quantity = quantity + 1 WHERE sku = '$sku'";
                                    $updateQuantityResult = $conn->query($updateQuantitySql);

                                    if ($updateQuantityResult !== true) {
                                        // Handle the error if the update fails
                                        echo "Error updating quantity in summary: " . $conn->error;
                                    }
                                } else {
                                    // Retrieve product details for the SKU
                                    $productSql = "SELECT * FROM products WHERE sku = '$sku'";
                                    $productResult = $conn->query($productSql);

                                    if ($productResult->num_rows > 0) {
                                        $row = $productResult->fetch_assoc();

                                        // SKU does not exist in the summary table, insert a new row
                                        $insertSummarySql = "INSERT INTO sold_items2 (sku, brand, model, size, price, quantity) VALUES ('$row[sku]', '$row[brand]', '$row[model]', '$row[size]', '$row[price]', 1)";
                                        $insertSummaryResult = $conn->query($insertSummarySql);

                                        if ($insertSummaryResult !== true) {
                                            // Handle the error if the insert fails
                                            echo "Error inserting item details into summary: " . $conn->error;
                                        }
                                    }
                                }

                                // Quantity updated successfully, redirect back to the same page
                                header("Location: restricted_inventory2.php");
                                exit();
                            } else {
                                // Handle the error if the update fails
                                echo "Error updating quantity: " . $conn->error;
                            }
                        } else {
                            // SKU not found in the database, insert a new row in the summary table
                            $insertSummarySql = "INSERT INTO sold_items2 (sku, brand, model, size, price, quantity) VALUES ('$sku', 'N/A', 'N/A', 'N/A', 'N/A', 1)";
                            $insertSummaryResult = $conn->query($insertSummarySql);

                            if ($insertSummaryResult === true) {
                                // Quantity updated successfully, redirect back to the same page
                                header("Location: restricted_inventory2.php");
                                exit();
                            } else {
                                // Handle the error if the insert fails
                                echo "Error inserting item details into summary: " . $conn->error;
                            }
                        }
                    }
                }

                // Retrieve sold items summary from the database
                $summarySql = "SELECT sku, brand, model, size, price, quantity FROM sold_items2";
                $summaryResult = $conn->query($summarySql);

                if ($summaryResult->num_rows > 0) {
                    while ($summaryRow = $summaryResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $summaryRow['sku'] . "</td>";
                        echo "<td>" . $summaryRow['brand'] . "</td>";
                        echo "<td>" . $summaryRow['model'] . "</td>";
                        echo "<td>" . $summaryRow['size'] . "</td>";
                        echo "<td>" . $summaryRow['price'] . "</td>";
                        echo "<td>" . $summaryRow['quantity'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No sold items yet</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>

        <h2>Product List</h2>
        <table>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Size</th>
                <th>SKU</th>
                <th>K1</th>
                <th>Kishii</th>
                <th>Garios</th>
                <th>Wowmoto</th>
                <th>Bodega Home</th>
                <th>Total Quantity</th>
                <th>Price</th>
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
                    echo "<td>" . $row['k1'] . "</td>";
                    echo "<td>" . $row['kishii'] . "</td>";
                    echo "<td>" . $row['garios'] . "</td>";
                    echo "<td>" . $row['wowmoto'] . "</td>";
                    echo "<td>" . $row['bodega_home'] . "</td>";
                    echo "<td>" . $row['total_quantity'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No products found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
