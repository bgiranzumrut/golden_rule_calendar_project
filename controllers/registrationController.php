<?php

require_once '../config/db_connection.php';
require_once '../models/event.php';
require_once '../models/user.php';

use Models\Event;
use Models\User;

class RegistrationController {
    private $eventModel;
    private $userModel;

    public function __construct(Event $eventModel, User $userModel) {
        $this->eventModel = $eventModel;
        $this->userModel = $userModel;
    }

    public function registerParticipant() {
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
    
            $userId = null; // Default user_id is null for unregistered users
    
            if ($user) {
                // Registered user, use their ID
                $userId = $user['id'];
            } else {
                // Unregistered user, show a reminder
                echo "<h2>Reminder: You are not registered in our system. Please consider registering for full benefits!</h2>";
            }
    
            // Register the participant with either user_id (if exists) or name/phone
            $registered = $this->eventModel->registerParticipantWithOptionalUserId($eventId, $name, $phone, $userId, $notes);
    
            if ($registered) {
                echo "<h1>Registration successful!</h1>";
                echo "<p>Thank you for registering for this event.</p>";
            } else {
                throw new \Exception("Failed to register. Please try again.");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "<h1>Error: " . htmlspecialchars($e->getMessage()) . "</h1>";
        }
    }
    
    public function showRegistrationForm($eventId) {
        try {
            $event = $this->eventModel->fetchEventById($eventId);
    
            if (!$event) {
                throw new \Exception("Event with ID {$eventId} not found.");
            }
    
            // Fetch participants and count
            $participants = $this->eventModel->fetchRegistrationsByEvent($eventId);
            $participantCount = count($participants);
    
            // Pass the data to the view
            $this->renderView('../views/registrationForm.php', [
                'event' => $event,
                'participants' => $participants,
                'participantCount' => $participantCount
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "<h1>Error: " . htmlspecialchars($e->getMessage()) . "</h1>";
        }
    }
    
    
    
    
    
    
    

    private function getUserIdOrCreate($name, $phone) {
        try {
            $user = $this->userModel->findUserByNameAndPhone($name, $phone);

            if ($user) {
                return $user['id'];
            }

            $userCreated = $this->userModel->createUser([
                'name' => $name,
                'phone_number' => $phone,
                'email' => null,
                'address' => null,
                'signature_date' => date('Y-m-d'),
                'role' => 'user',
            ]);

            if ($userCreated) {
                return $this->userModel->getLastInsertId();
            }

            throw new \Exception("Failed to create user.");
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception("Error processing user data.");
        }
    }

    private function renderView($view, $data = []) {
        extract($data);
        include $view;
    }
}

// Initialize the controller and route actions
$eventModel = new Event($conn);
$userModel = new User($conn);
$controller = new RegistrationController($eventModel, $userModel);

$action = $_GET['action'] ?? null;

if ($action === 'showRegistrationForm') {
    $eventId = $_GET['event_id'] ?? null;
    if ($eventId) {
        $controller->showRegistrationForm($eventId);
    } else {
        echo "<h1>Error: Event ID is missing.</h1>";
    }
} elseif ($action === 'registerParticipant') {
    $controller->registerParticipant();
} else {
    echo "<h1>Error: Invalid action.</h1>";
}
