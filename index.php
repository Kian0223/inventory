<!DOCTYPE html>
<html>
<head>
    <title>Sign Up / Sign In</title>
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
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
            width: 95%;
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
        .signin-link {
            text-align: center;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .additional-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .additional-container h1 {
            text-align: center;
        }
        .additional-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .additional-buttons button {
            width: 50%;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            margin-bottom: 20px;
            cursor: pointer;
            border-radius: 5px;
        }



    </style>
</head>
<body>
    <div class="header">
        <h1>Santos Group of Companies</h1>
    </div>
    <div class="container">
        <?php if(isset($_GET['error'])) { ?>
            <p class="error-message"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <?php if(isset($_GET['success'])) { ?>
            <p class="success-message"><?php echo $_GET['success']; ?></p>
        <?php } ?>
        <?php if(!isset($_GET['signin'])) { ?>
            <form action="signin.php" method="post">
                <h2>Admin Login</h2>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Sign In">
                <p class="signin-link">Don't have an account? <a href="index.php?signin=true">Sign Up</a></p>
            </form>
        <?php } else { ?>
            <form action="signup.php" method="post">
                <h2>Sign Up</h2>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Sign Up">
                <p class="signin-link">Already have an account? <a href="index.php">Sign In</a></p>
            </form>
        <?php } ?>
    </div>

    <!-- Additional container with buttons -->
    <div class="additional-container">
        <h1>Employee Login</h1>
        <div class="additional-buttons">
            <button onclick="window.location.href = 'employee_login.php';">K1 Moto Outlet</button>
            <button onclick="window.location.href = 'employee_login1.php';">KishiiMoto</button>
            <button onclick="window.location.href = 'employee_login2.php';">Garios Mototrend</button>
            <button onclick="window.location.href = 'employee_login3.php';">Wowmoto</button>
            <button onclick="window.location.href = 'employee_login4.php';">Bodega Home</button>
        </div>
    </div>
</body>
</html>
