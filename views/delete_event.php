<?php
session_start();
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event;

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Validate that the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Error: Invalid request method.";
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "Error: Invalid CSRF token.";
    exit;
}

// Validate and sanitize the event ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "Error: Invalid event ID.";
    exit;
}

$eventId = (int) $_POST['id'];
$eventModel = new Event($conn);

try {
    // Attempt to delete the event
    if ($eventModel->deleteEvent($eventId)) {
        // Redirect to the dashboard with a success message
        header("Location: ../public/admin_dashboard.php?message=Event+deleted+successfully");
        exit;
    } else {
        throw new Exception("Failed to delete the event. Event may not exist.");
    }
} catch (Exception $e) {
    // Redirect with an error message
    header("Location: ../public/admin_dashboard.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
