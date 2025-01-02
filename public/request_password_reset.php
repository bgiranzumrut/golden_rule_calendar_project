<?php
session_start();
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM admin_users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                $resetToken = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Update the database with the reset token
                $updateStmt = $conn->prepare("UPDATE admin_users SET reset_token = :resetToken, reset_token_expiry = :expiry WHERE email = :email");
                $updateStmt->execute([
                    ':resetToken' => $resetToken,
                    ':expiry' => $expiry,
                    ':email' => $email
                ]);

                // Send password reset email
                $resetLink = "http://localhost/golden_rule_calendar_project/reset_password.php?token=$resetToken";
                $message = "You requested a password reset. Please click the link below to reset your password:
                $resetLink

                This link will expire in 1 hour.";
                mail($email, "Password Reset", $message);

                $success = "If the email exists in our system, a password reset link will be sent.";
            } else {
                // To prevent email enumeration
                $success = "If the email exists in our system, a password reset link will be sent.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


?>
