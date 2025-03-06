<?php

session_start();
include_once '../config/db_connection.php';
include("header.php");

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
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;

    background-color: #f8f5f0; /* Match your website’s background */
}

.container {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}
form {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 350px; /* Adjust width */
    text-align: center; /* Center everything */
    height: 350px; /* Adjust height */
    margin: 30px;
}

h2 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    color: #c04b3e; /* Match header colors */
}

form input {
    margin: 10px 0;
    padding: 12px;
    width: 90%;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

form button {
    padding: 12px;
    width: 100%;
    background-color: #c04b3e; /* Match your button style */
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}

form button:hover {
    background-color: #a03e34; /* Darker on hover */
}

.error {
    color: red;
    font-size: 0.9rem;
    margin-top: 10px;
}

/* Forgot Password Link */
.forgot-password {
    display: block;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #c04b3e;
    text-decoration: none;
}

.forgot-password:hover {
    text-decoration: underline;
}

.nav-link {
  font-size: 1.1rem;
  font-weight: bold;
  padding: 15px 15px;
  transition: all 0.3s ease-in-out;
  width: 130px;
}

    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="">
        <h2>Admin Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <a href="../views/password_recovery.php" class="forgot-password">Forgot Password?</a>
    </form>
</div>


</body>
</html>
<?php include("footer.php");
