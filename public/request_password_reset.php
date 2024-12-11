<?php
session_start();
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $reset_token = bin2hex(random_bytes(16));
        $stmt->close();

        // Update reset token in the database
        $update_stmt = $conn->prepare("UPDATE admin_users SET reset_token = ? WHERE email = ?");
        $update_stmt->bind_param('ss', $reset_token, $email);
        $update_stmt->execute();
        $update_stmt->close();

        // Send reset link to email
        $reset_link = "http://localhost/golden_rule_calendar_project/reset_password.php?token=$reset_token";
        mail($email, "Password Reset", "Click the following link to reset your password: $reset_link");

        $success = "Password reset link sent to your email.";
    } else {
        $error = "Email not found.";
    }
}
?>
