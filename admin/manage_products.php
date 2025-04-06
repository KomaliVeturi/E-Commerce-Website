<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffeaa7, #ff6a88);
            margin: 0;
            padding: 0;
            color: #2d3436;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(255, 136, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #ff6a88;
            font-size: 2em;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ffd6d6;
            text-align: left;
        }

        th {
            background: rgb(253, 167, 113);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fff8f8;
        }

        tr:hover {
            background-color: #fff0e6;
        }

        td img {
            width: 60px;
            height: auto;
            border-radius: 6px;
        }

        .actions a {
            margin: 0 8px;
            padding: 8px 12px;
            color: #ff6a88;
            border: 1px solid #ff6a88;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9em;
            transition: background 0.3s, color 0.3s, transform 0.2s;
        }

        .actions a:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            color: white;
            transform: scale(1.05);
        }

        .btn-back {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            background: linear-gradient(to right, #ff6a88, #ff914d);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
        }

        .btn-back:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            transform: scale(1.02);
        }

        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            th {
                display: none;
            }

            td {
                position: relative;
                padding-left: 50%;
                text-align: right;
                margin-bottom: 10px;
                border: none;
                border-bottom: 1px solid #ffd6d6;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                font-weight: bold;
                color: #2d3436;
                text-align: left;
            }

            .btn-back {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Manage Products</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td data-label="ID"><?= $product['id']; ?></td>
                        <td data-label="Name"><?= htmlspecialchars($product['name']); ?></td>
                        <td data-label="Price">$<?= number_format($product['price'], 2); ?></td>
                        <td data-label="Description"><?= htmlspecialchars($product['description']); ?></td>
                        <td data-label="Image"><img src="../images/<?= htmlspecialchars($product['image']); ?>"
                                alt="Product Image"></td>
                        <td data-label="Actions" class="actions">
                            <a href="edit.php?id=<?= $product['id']; ?>">Edit</a>
                            <a href="delete.php?id=<?= $product['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

</body>

</html>