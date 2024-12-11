<?php
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event;

$eventModel = new Event($conn);

// Log received data for debugging
file_put_contents('debug.log', "POST DATA:\n" . print_r($_POST, true), FILE_APPEND);
file_put_contents('debug.log', "FILES DATA:\n" . print_r($_FILES, true), FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if (!$action) {
        echo "Error: No action specified.";
        exit;
    }

    try {
        if ($action === 'create') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $created_by = 1; // Replace with the logged-in admin ID
            $image = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image_dir = '../uploads/event_images/';
                if (!is_dir($image_dir)) {
                    mkdir($image_dir, 0777, true);
                }

                $image_path = $image_dir . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    throw new Exception('Error uploading image.');
                }
                $image = $image_path;
            }

            if ($eventModel->createEvent($title, $description, $start_time, $end_time, $created_by, $image)) {
                echo "Event created successfully!";
            } else {
                throw new Exception('Failed to create the event.');
            }
        } elseif ($action === 'edit') {
            // Event editing logic
        } elseif ($action === 'delete') {
            // Event deletion logic
        } else {
            echo "Error: Invalid action.";
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
