<?php
session_start();
require_once '../config/db_connection.php';
require_once '../models/Event.php';

use Models\Event;

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$eventModel = new Event($conn);

// Check if an event ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid event ID.";
    exit;
}

$eventId = (int)$_GET['id'];

// Fetch the event details
try {
    $query = "
    SELECT 
        e.*, 
        a.name AS admin_name, 
        ae.name AS editor_name 
    FROM 
        Events e
    LEFT JOIN 
        admin_users a 
    ON 
        e.created_by = a.id
    LEFT JOIN 
        admin_users ae 
    ON 
        e.edited_by = ae.id
    WHERE 
        e.id = :id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $eventId]);

    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "No event found with ID $eventId.";
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
</head>
<body>
    <h1>Event Details</h1>
    <p><strong>Title:</strong> <?php echo htmlspecialchars($event['title']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    <p><strong>Start Time:</strong> <?php echo htmlspecialchars($event['start_time']); ?></p>
    <p><strong>End Time:</strong> <?php echo htmlspecialchars($event['end_time']); ?></p>
    <p><strong>Created By:</strong> <?php echo htmlspecialchars($event['admin_name']); ?></p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($event['created_at']); ?></p>
    <?php if (!empty($event['edited_by'])): ?>
        <p><strong>Last Edited By:</strong> <?php echo htmlspecialchars($event['editor_name']); ?></p>
        <p><strong>Last Edited At:</strong> <?php echo htmlspecialchars($event['edit_date']); ?></p>
    <?php endif; ?>
    <?php if (!empty($event['image'])): ?>
        <p><strong>Image:</strong></p>
        <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" style="max-width: 300px;">
    <?php endif; ?>
    <button onclick="window.history.back();">Back</button>
</body>
</html>
