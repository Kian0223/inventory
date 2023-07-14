<?php
// Establish database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // SQL query to check if the user exists in the database
    $sql = "SELECT * FROM kishii WHERE username = '$username' AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, redirect to the success page
        header("Location: restricted_inventory1.php");
        exit();
    } else {
        // User does not exist, redirect back to the login page with an error message
        $error = "Invalid username or password";
        header("Location: employee_login1.php?error=" . urlencode($error));
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kishii Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        h2 {
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Santos Group of Companies</h1>
    <h2>Kishii Login</h2>
    <div class="container">
        <form action="employee_login1.php" method="post">
            <?php if(isset($_GET['error'])) { ?>
                <p class="error-message"><?php echo $_GET['error'];?></p>
            <?php } ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Sign In">
        </form>
    </div>
</body>
</html>
