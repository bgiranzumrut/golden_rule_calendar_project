<?php
session_start();
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL query with placeholders
        $stmt = $conn->prepare("SELECT id, name, password FROM admin_users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        // Fetch the user data
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];

            // Redirect to the admin dashboard
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form input {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
        }
        form button {
            padding: 10px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<form method="POST" action="">
    <h2>Admin Login</h2>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <!-- Forgot Password Button -->
    <div style="text-align: center; margin-top: 10px;">
        <a href="../views/password_recovery.php" style="text-decoration: none; color: #4CAF50; font-size: 0.9rem;">Forgot Password?</a>
    </div>
</form>

</body>
</html>
