<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page with a success message
header("Location: index.php?message=Logged+out+successfully");
exit;
?>
