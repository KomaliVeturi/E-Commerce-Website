<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(254, 225, 225);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(255, 136, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #ff6a88;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        nav a {
            text-decoration: none;
            padding: 12px 25px;
            background-color: #ff6a88;
            color: white;
            font-size: 1em;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
        }

        nav a:hover {
            background-color: #ff7675;
            transform: scale(1.05);
        }

        .logout {
            background-color: #e74c3c;
        }

        .logout:hover {
            background-color: #ff6b5c;
        }

        footer {
            text-align: center;
            margin-top: 60px;
            font-size: 0.95em;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <nav>
            <a href="add_product.php">Add Product</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Admin Dashboard</p>
    </footer>
</body>

</html>