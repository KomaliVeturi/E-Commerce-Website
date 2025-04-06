<?php
// Start the session and check if the user is logged in
session_start();

// Logout Logic - placed at the top of the script
if (isset($_POST['logout'])) {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php?logout=1"); // ✅ Add logout=1 to trigger alert
    exit(); // Make sure no further code is executed after redirection
}

/*If not logged in, redirect to the login page
( Check if the user is logged in)


if (!isset($_SESSION['user_id'])) {
 header("Location: pages/login.php");
exit();
}*/

// Fetch products from the database
include 'includes/db.php';
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
</head>

<body>
    <header>
        <div class="nav-container">
            <a href="#" class="logo">Chroma Lips</a>
            <button class="nav-toggle" id="navToggle" onclick="toggleMenu()">☰</button>
            <nav>
                <div class="nav-links">
                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>
                    <a href="pages/cart.php" class="cart-link">Cart</a>
                    <a href="#about">About</a>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="logout" class="logout-button">Logout</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>




    <div class="main-container">
        <main>
            <h2 style="text-align: center;">... Latest Arrivals ...</h2>
            <div class="product-list">
                <?php if (empty($products)): ?>
                    <p>No products available.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2); ?></p>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($product['image'])): ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>"
                                    alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php endif; ?>
                            <form method="POST" action="pages/cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
        <!-- About Section -->
    </div>
    <section id="about" class="about-section">
        <div class="about-content">
            <h2>About Chroma Lips</h2>
            <p>At Chroma Lips, we believe beauty is more than skin deep. We specialize in high-quality, cruelty-free lip
                products with a spectrum of bold and classic shades for every personality and occasion.</p>
            <p>Our products are designed with care, creativity, and love. Whether you’re after a vibrant pop of color or
                a subtle nude finish, our wide range of options has something for everyone.</p>
            <p>We are proudly made in India and committed to ethical sourcing and sustainable packaging. Your lips
                deserve the best — and we're here to deliver it.</p>
        </div>
    </section>


    <h2 style="text-align:center; color:#c04c63;">FIND US</h2>
    <div class="contact-map-container">
        <div class="contact-form">
            <h2>Have a Custom Idea? Drop It Here</h2>
            <form action="submit_form.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="place">Place:</label>
                <input type="text" id="place" name="place" required>

                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" required>

                <label for="requirement">Requirement of color combination:</label>
                <textarea id="requirement" name="requirement" rows="3" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Google Map Section -->
        <div class="map-box">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14690.886355801666!2d72.83182397423005!3d21.185438132303588!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04f08e75545e5%3A0xb9175c773373a1dd!2sSUGAR%20Cosmetics!5e0!3m2!1sen!2sin!4v1743852540068!5m2!1sen!2sin"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
    <script>
        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
    <script>
        // Check if URL has ?logout=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('logout') === '1') {
            alert('You have successfully logged out.');
            // Remove ?logout=1 from URL without reloading
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

</body>

</html>