<?php
session_start();
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reset_token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Update password in the database
    $stmt = $conn->prepare("UPDATE admin_users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param('ss', $new_password, $reset_token);
    if ($stmt->execute()) {
        $success = "Password reset successfully!";
    } else {
        $error = "Invalid token or error occurred.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Add similar styling as above */
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Reset Password</h2>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
    </form>
</body>
</html>
