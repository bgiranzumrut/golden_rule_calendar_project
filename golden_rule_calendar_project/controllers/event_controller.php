<?php
include_once '../config/db_connection.php';
include_once '../models/event.php';

$eventModel = new Event($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle event creation
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $created_by = 1; // Replace with the logged-in admin ID
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_dir = '../uploads/event_images/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }
        $image = $image_dir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            echo "Error uploading image.";
            exit;
        }
    }

    $result = $eventModel->createEvent($title, $description, $start_time, $end_time, $created_by, $image);
    if ($result) {
        echo "Event created successfully!";
    } else {
        echo "Failed to create the event.";
    }
}
?>
