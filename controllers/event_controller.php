<?php
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event;

$eventModel = new Event($conn);

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
                // Redirect to the admin dashboard
                header("Location:../public/admin_dashboard.php?message=Event+created+successfully");
                exit;
            } else {
                throw new Exception('Failed to create the event.');
            }
        } elseif ($action === 'edit') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $image = $_POST['current_image'] ?? null;

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

            if ($eventModel->updateEvent($id, $title, $description, $start_time, $end_time, $image)) {
                header("Location: ../public/admin_dashboard.php?message=Event+updated+successfully");
                exit;
            } else {
                throw new Exception('Failed to update the event.');
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'];

            // Call the model to delete the event
            if ($eventModel->deleteEvent($id)) {
                // Redirect to the admin dashboard with a success message
                header("Location: ../public/admin_dashboard.php?message=Event+deleted+successfully");
                exit;
            } else {
                throw new Exception('Failed to delete the event.');
            }
        } else {
            echo "Error: Invalid action.";
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>