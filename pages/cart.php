<!-- <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session

// Handle Add to Cart with Quantity
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;  // Default to 1 if quantity is not set

    // Check if product is already in the user's cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Update quantity if the product is already in the cart
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $user_id, $product_id]);
    } else {
        // Add new product to the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Product Removal from Cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Quantity Update
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    // Update the quantity in the cart
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
    header("Location: " . $_SERVER['PHP_SELF']);//doesn't asks confirm for resubmission
    exit();
}

// Fetch the user's cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;  // Initialize total cost variable
?> -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(to right, #ffeaa7, #ff6a88);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            color: #333;
            font-size: 14px;
        }

        /* Main Container */
        .container {
            width: 85%;
            max-width: 1000px;
            margin: 100px auto 30px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(255, 136, 0, 0.3);
            border-radius: 16px;
        }

        /* Heading */
        h2 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 25px;
            color: #ff6a88;
        }

        /* Cart Item */
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            margin-bottom: 12px;
            background: #fff8f8;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out;
        }

        .cart-item:hover {
            transform: scale(1.02);
        }

        .cart-item img {
            max-width: 80px;
            border-radius: 8px;
            margin-right: 15px;
        }

        /* Item Info */
        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
        }

        .item-price {
            font-size: 1em;
            color: #495057;
            margin-top: 4px;
        }

        /* Actions (Update/Remove) */
        .item-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .item-actions button,
        .item-actions form button {
            background: linear-gradient(to right, #ff6a88, #ff914d);
            color: white;
            border: none;
            padding: 7px 12px;
            margin-left: 8px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
            transition: background 0.3s ease, transform 0.2s;
        }

        .item-actions button:hover,
        .item-actions form button:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            transform: scale(1.05);
        }

        .quantity {
            width: 55px;
            padding: 5px;
            margin-right: 8px;
            border: 1px solid #ffd6d6;
            border-radius: 8px;
            text-align: center;
            background: #fff8f8;
        }

        .quantity:focus {
            border-color: #ff914d;
            outline: none;
            background: #fff0e6;
        }

        /* Total and Navigation */
        .total-cost {
            font-size: 1.5em;
            font-weight: bold;
            color: #2d3436;
            text-align: center;
            margin-top: 25px;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .cart-actions a {
            background: linear-gradient(to right, #ff6a88, #ff914d);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 1em;
            transition: background 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
        }

        .cart-actions a:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            transform: translateY(-2px);
        }

        /* Empty Cart Message */
        .empty-cart {
            text-align: center;
            font-size: 1.2em;
            color: #6c757d;
        }

        /* Responsive Tweaks */
        @media (max-width: 480px) {
            .container {
                margin: 40px 10px;
                padding: 16px;
            }

            .cart-actions a {
                flex: 1 1 100%;
            }

            .item-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity {
                width: 100%;
            }

            .cart-item img {
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Your Cart</h2>
        <?php
        if (empty($cart_items)) {
            echo "<p class='empty-cart'>Your cart is empty.</p>";
        } else {
            $product_ids = array_column($cart_items, 'product_id');
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
            $stmt->execute($product_ids);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($products as $product) {
                $quantity = 0;
                foreach ($cart_items as $cart_item) {
                    if ($cart_item['product_id'] == $product['id']) {
                        $quantity = $cart_item['quantity'];
                        break;
                    }
                }
                $total_cost += $product['price'] * $quantity;
                echo "<div class='cart-item'>
                        <img src='../images/{$product['image']}' alt='{$product['name']}' class='item-image'>
                        <div class='item-details'>
                            <div class='item-name'>{$product['name']}</div>
                            <div class='item-price'>\${$product['price']} x $quantity</div>
                        </div>
                        <div class='item-actions'>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <input type='number' name='quantity' value='$quantity' class='quantity' min='1' required>
                                <button type='submit' name='update_quantity'>Update</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <button type='submit' name='remove_from_cart'>Remove</button>
                            </form>
                        </div>
                      </div>";
            }
        }
        ?>
        <?php if (!empty($cart_items)): ?>
            <div class="total-cost">
                Total: $<?= number_format($total_cost, 2); ?>
            </div>
        <?php endif; ?>
        <div class="cart-actions">
            <a href="../index.php">Back to Shop</a>
            <a href="logout.php">Proceed to logout</a>
        </div>
    </div>
</body>

</html>