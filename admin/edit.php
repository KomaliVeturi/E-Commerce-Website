<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Product ID.");
}

$product_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            die("Error uploading the image.");
        }
    }

    $update_stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
    $update_stmt->execute([$name, $price, $description, $image, $product_id]);

    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 15px;
        }

        label {
            font-weight: 500;
            display: block;
            margin: 12px 0 6px;
            text-align: left;
            color: #444;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #ff6b81;
            box-shadow: 0px 0px 8px rgba(255, 107, 129, 0.5);
            outline: none;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #ff5277, #ff6a88);
            transform: scale(1.05);
        }

        .btn-back {
            display: block;
            margin: 20px auto;
            padding: 12px;
            width: 100%;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-back:hover {
            background-color: #495057;
            transform: scale(1.05);
        }

        img {
            display: block;
            margin: 15px auto;
            max-width: 150px;
            border-radius: 8px;
            border: 2px solid #ddd;
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price ($):</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= $product['price']; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description"
                required><?= htmlspecialchars($product['description']); ?></textarea>

            <label>Current Image:</label>
            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image">

            <label for="image">Change Image (optional):</label>
            <input type="file" name="image" id="image">

            <button type="submit" class="btn">Update Product</button>
        </form>

        <a href="manage_products.php" class="btn-back">Back to Manage Products</a>
    </div>

</body>

</html>