<?php
include('../includes/db.php');  // Adjusted path
session_start();

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before saving
    $role = 'user'; // Default role for regular users

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Email is already registered!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);

        // After registration, log the user in and redirect to the main page
        $_SESSION['user_id'] = $conn->lastInsertId(); // Store the user ID in session
        header("Location: ../index.php"); // Redirect to the main page
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffeaa7, #ff6a88);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* Prevent page scroll */
        }

        .register-container {
            background: #ffffff;
            padding: 30px 24px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(255, 136, 0, 0.3);
            width: 100%;
            max-width: 450px;
            height: auto;
            max-height: 100vh;
            overflow-y: auto;
        }

        h2 {
            text-align: center;
            color: #ff6a88;
            font-size: 1.6em;
            margin-bottom: 24px;
        }

        label {
            font-size: 1em;
            margin-bottom: 6px;
            display: block;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ffd6d6;
            border-radius: 8px;
            font-size: 1em;
            background: #fff8f8;
        }

        input:focus {
            border-color: #ff914d;
            outline: none;
            background: #fff0e6;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #ff6a88, #ff914d);
            color: white;
            font-size: 1.05em;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 150, 100, 0.3);
            transition: background 0.3s, transform 0.2s;
            margin-top: 8px;
        }

        button:hover {
            background: linear-gradient(to right, #ff7675, #ffb347);
            transform: scale(1.02);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.95em;
            text-align: center;
            margin-top: 12px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" required>

            <label>Middle Name:</label>
            <input type="text" name="middle_name" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Phone Number:</label>
            <input type="tel" name="phone" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" name="register">Register</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>
</body>

</html>