<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if product ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = $_GET['id'];

// Check if product exists
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

// Delete product from database only
$delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete_stmt->execute([$product_id]);

// Redirect after deletion
header("Location: manage_products.php?message=Product Deleted Successfully");
exit();
?>