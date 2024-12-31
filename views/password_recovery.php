<?php
session_start();
include_once '../config/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php';

// Load the .env variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$variables = $dotenv->load();

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
                // Generate reset token
                $resetToken = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $resetToken);
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Update database with reset token and expiry
                $updateStmt = $conn->prepare(
                    "UPDATE admin_users SET reset_token = :resetToken, reset_token_expiry = :expiry WHERE email = :email"
                );
                $updateStmt->execute([
                    ':resetToken' => $hashedToken,
                    ':expiry' => $expiry,
                    ':email' => $email
                ]);

                // Configure PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = $variables['SMTP_HOST'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $variables['SMTP_USER'];
                    $mail->Password = $variables['SMTP_PASS'];
                    $mail->SMTPSecure = $variables['SMTP_SECURE'];
                    $mail->Port = $variables['SMTP_PORT'];

                    $mail->setFrom($variables['SMTP_USER'], 'Golden Rule Calendar');
                    $mail->addAddress($email);

                    // Create reset link
                    $resetLink = "http://localhost/golden_rule_calendar_project/public/reset_password.php?token=$resetToken";

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "Click the link to reset your password: <a href='$resetLink'>$resetLink</a>";

                    $mail->send();
                    $success = "If the email exists, a reset link has been sent.";
                } catch (Exception $e) {
                    $error = "Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                $success = "If the email exists, a reset link has been sent.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <form method="POST" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
