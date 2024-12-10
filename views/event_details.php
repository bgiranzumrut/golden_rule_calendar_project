<?php
include_once '../config/db_connection.php';
include_once '../models/event.php';

$eventModel = new Event($conn);

if (isset($_GET['id'])) {
    $event = $eventModel->fetchEventById($_GET['id']);
    if ($event) {
        echo "<h1>{$event['title']}</h1>";
        echo "<p>{$event['description']}</p>";
        echo "<p><strong>Starts:</strong> {$event['start_time']}</p>";
        echo "<p><strong>Ends:</strong> {$event['end_time']}</p>";
        if ($event['image']) {
            echo "<img src='{$event['image']}' alt='{$event['title']}' />";
        }
    } else {
        echo "Event not found.";
    }
} else {
    echo "No event ID provided.";
}
?>
