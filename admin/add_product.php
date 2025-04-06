<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);
    echo "Product added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffeaa7, #ff6a88);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 32px 28px;
            border-radius: 20px;
            box-shadow: 0 6px 18px rgba(255, 136, 0, 0.3);
            overflow-y: auto;
            max-height: 95vh;
        }

        h2 {
            text-align: center;
            color: #ff6a88;
            font-size: 1.6em;
            margin-bottom: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 6px;
            font-weight: 500;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ffd6d6;
            border-radius: 10px;
            font-size: 1em;
            background: #fff8f8;
            transition: background 0.3s, border 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #ff914d;
            background: #fff0e6;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background: linear-gradient(to right, #ff6a88, #ff914d);
            color: white;
            padding: 14px;
            font-size: 1.05em;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
            transition: background 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            transform: scale(1.02);
        }

        .message {
            color: #27ae60;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .back-link {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
        }

        .back-link a {
            color: #ff6a88;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" required>

            <button type="submit" name="add_product">Add Product</button>
        </form>

        <?php if (isset($successMessage)): ?>
            <div class="message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <div class="back-link">
            <a href="manage_products.php">Back to Manage Products</a>
        </div>
    </div>
</body>

</html>