<?php

require_once '../config/db_connection.php';
require_once '../models/event.php';
require_once '../models/user.php';

use Models\Event;
use Models\User;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class RegistrationController {
    private $eventModel;
    private $userModel;

    public function __construct(Event $eventModel, User $userModel) {
        $this->eventModel = $eventModel;
        $this->userModel = $userModel;
    }

    // In registrationController.php, update the registerParticipant method
public function registerParticipant() {
    header('Content-Type: application/json');
    try {
        $eventId = $_POST['event_id'] ?? null;
        $name = $_POST['name'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (empty($eventId) || empty($name) || empty($phone)) {
            throw new \Exception("Event ID, name, and phone number are required.");
        }

        // Check if the user exists
        $user = $this->userModel->findUserByNameAndPhone($name, $phone);
        $userId = null;

        if ($user) {
            $userId = $user['id'];
        }

        $registered = $this->eventModel->registerParticipantWithOptionalUserId($eventId, $name, $phone, $userId, $notes);

        if ($registered) {
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            throw new \Exception("Failed to register. Please try again.");
        }
    } catch (\Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

    private function sendJsonResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

// Initialize the controller and route actions
$eventModel = new Event($conn);
$userModel = new User($conn);
$controller = new RegistrationController($eventModel, $userModel);

$action = $_REQUEST['action'] ?? null;

if ($action === 'register') {
    $controller->registerParticipant();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}