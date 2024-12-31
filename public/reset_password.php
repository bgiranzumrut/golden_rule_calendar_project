<?php
session_start();
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Hash the token
        $hashedToken = hash('sha256', $token);

        // Validate token and check expiry
        $stmt = $conn->prepare("SELECT id, reset_token_expiry FROM admin_users WHERE reset_token = :token");
        $stmt->execute([':token' => $hashedToken]);
        $user = $stmt->fetch();

        if ($user && strtotime($user['reset_token_expiry']) > time()) {
            $_SESSION['reset_user_id'] = $user['id'];
        } else {
            echo "Invalid or expired token. <a href='password_recovery.php'>Request a new reset link</a>";
            exit;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['reset_user_id'])) {
    $newPassword = htmlspecialchars($_POST['password']);
    $csrfToken = $_POST['csrf_token'];

    // Check CSRF token
    if ($csrfToken !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }
    unset($_SESSION['csrf_token']); // Invalidate CSRF token

    // Password validation
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/', $newPassword)) {
        die("Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.");
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    try {
        // Update the password in the database
        $stmt = $conn->prepare("UPDATE admin_users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
        $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $_SESSION['reset_user_id']
        ]);

        unset($_SESSION['reset_user_id']);
        echo "Password updated successfully. <a href='login.php'>Click here to login</a>.";
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
