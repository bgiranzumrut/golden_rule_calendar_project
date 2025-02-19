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
            $start_times = $_POST['start_time'];
            $end_times = $_POST['end_time'];
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

            // Create events for each date pair
            $success = true;
            $created_events = [];

            for ($i = 0; $i < count($start_times); $i++) {
                $event_id = $eventModel->createEvent(
                    $title,
                    $description,
                    $start_times[$i],
                    $end_times[$i],
                    $created_by,
                    $image
                );

                if ($event_id) {
                    $created_events[] = $event_id;
                } else {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                header("Location: ../public/manage_events.php?message=Event(s)+created+successfully");
                exit;
            } else {
                // If any event creation failed, delete the successfully created ones
                foreach ($created_events as $event_id) {
                    $eventModel->deleteEvent($event_id);
                }
                throw new Exception('Failed to create one or more events.');
            }

        } elseif ($action === 'edit') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $image = $_POST['current_image'] ?? null;
            $edited_by = $_SESSION['admin_id'];
            $edit_date = date('Y-m-d H:i:s'); // Current timestamp for the edit date

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image_dir = '../uploads/event_images/';
                if (!is_dir($image_dir)) {
                    mkdir($image_dir, 0777, true);
                }

                $image_path = $image_dir . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    throw new Exception('Error uploading image.');
                }

                // Delete old image if exists
                if ($image && file_exists($image)) {
                    unlink($image);
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
                // Get the event details to delete the image
                $event = $eventModel->fetchEventById($id);

                // Delete the event
                if ($eventModel->deleteEvent($id)) {
                    // Delete the image file if it exists
                    if ($event && $event['image'] && file_exists($event['image'])) {
                        unlink($event['image']);
                    }

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
        // Optionally log the error
        error_log("Event Controller Error: " . $e->getMessage());
    }
}

// Handle GET requests for fetching event details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? null;

    if ($action === 'getEvent') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $event = $eventModel->fetchEventById($id);
            if ($event) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'event' => $event
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Event not found'
                ]);
            }
        }
        exit;
    }
}
?>