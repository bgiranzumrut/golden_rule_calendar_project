<?php
// Include necessary files
require_once '../config/db_connection.php'; // Ensure this path is correct
require_once '../models/event.php';

use Models\Event; // Import Event class from the Models namespace

// Initialize Event model
$eventModel = new Event($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect input data
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $created_by = 1; // Replace with the logged-in admin ID
        $image = null;

        // Handle image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_dir = '../uploads/event_images/';
            if (!is_dir($image_dir)) {
                mkdir($image_dir, 0777, true); // Create directory if it doesn't exist
            }

            $image_path = $image_dir . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                throw new Exception('Error uploading image.');
            }
            $image = $image_path;
        }

        // Call the model's method to create the event
        $result = $eventModel->createEvent($title, $description, $start_time, $end_time, $created_by, $image);

        // Check if the event was created successfully
        if ($result) {
            echo "Event created successfully!";
        } else {
            throw new Exception('Failed to create the event.');
        }
    } catch (Exception $e) {
        // Display error message
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle invalid HTTP methods
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
