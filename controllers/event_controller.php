<?php
require_once '../config/db_connection.php';
require_once '../models/event.php';
session_start();

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
            $created_by = $_SESSION['admin_id'] ?? null;
            $short_name = $_POST['short_name'] ?? null;

            if (!$created_by) {
                throw new Exception("Error: Admin not logged in.");
            }
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

            if ($eventModel->createEvent($title, $description, $short_name,$start_time, $end_time, $created_by, $image)) {
                // Redirect to the admin dashboard
                header("Location: ../public/manage_events.php?message=Event+created+successfully");
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
            $edited_by = $_SESSION['admin_id']; // The admin editing the event
            $short_name = $_POST['short_name'] ?? null;

 // Current timestamp for the edit date

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

            // Call the model's updateEvent method
            if ($eventModel->updateEvent($id, $title, $description, $short_name,$start_time, $end_time, $image, $edited_by, $edit_date)) {
                header("Location: ../public/manage_events.php?message=Event+updated+successfully");
                exit;
            } else {
                throw new Exception('Failed to update the event.');
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'];

            // First, delete all registrations associated with the event
            if ($eventModel->deleteRegistrationsByEvent($id)) {
                // Now delete the event
                if ($eventModel->deleteEvent($id)) {
                    // Redirect to the admin dashboard with a success message
                    header("Location: ../public/manage_events.php?message=Event+deleted+successfully");
                    exit;
                } else {
                    throw new Exception('Failed to delete the event.');
                }
            } else {
                throw new Exception('Failed to delete registrations associated with the event.');
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