<?php
require_once '../config/db_connection.php';
require_once '../models/user.php';

use Models\User;

$userModel = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if (!$action) {
        echo "Error: No action specified.";
        exit;
    }

    try {
        // Create User
        if ($action === 'create') {
            $data = [
                'name' => $_POST['name'],
                'phone_number' => $_POST['phone_number'] ?? null,
                'email' => $_POST['email'],
                'address' => $_POST['address'] ?? null,
                'emergency_contact_1_name' => $_POST['emergency_contact_1_name'] ?? null,
                'emergency_contact_1_phone' => $_POST['emergency_contact_1_phone'] ?? null,
                'emergency_contact_1_relationship' => $_POST['emergency_contact_1_relationship'] ?? null,
                'emergency_contact_2_name' => $_POST['emergency_contact_2_name'] ?? null,
                'emergency_contact_2_phone' => $_POST['emergency_contact_2_phone'] ?? null,
                'emergency_contact_2_relationship' => $_POST['emergency_contact_2_relationship'] ?? null,
                'image' => $_FILES['image']['name'] ?? null,
                'recording_consent' => isset($_POST['recording_consent']) ? 1 : 0,
                'injury_loss_risk_consent' => isset($_POST['injury_loss_risk_consent']) ? 1 : 0,
                'signature_date' => $_POST['signature_date'] ?? null,
                'role' => $_POST['role'], // Ensure role is included
                'password' => $_POST['password'] ?? null // For admin users only
            ];
        
            // Image upload logic
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "../uploads/";
                $targetFile = $targetDir . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }
        
            if ($userModel->createUser($data)) {
                header("Location: ../public/admin_dashboard.php");
                exit;
            } else {
                echo "Error: Failed to create user.";
            }
        }
        

        // Edit User
        elseif ($action === 'edit') {
            $id = $_POST['id'];
            $role = $_POST['role']; // Ensure role is captured from the form
            $data = [
                'name' => $_POST['name'],
                'phone_number' => $_POST['phone_number'] ?? null,
                'email' => $_POST['email'],
                'address' => $_POST['address'] ?? null,
                // Add other fields as necessary
            ];
        
            if ($userModel->updateUser($data, $id, $role)) {
                header("Location: ../public/admin_dashboard.php");
                exit;
            } else {
                echo "Error: Failed to update user.";
            }
        }
        
        

        // Delete User
        elseif ($action === 'delete') {
            $id = $_POST['id'];
            $role = $data['role'] ?? 'user'; // Default role is 'user'


            if ($userModel->deleteUser($id, $role)) {
                header("Location: ../public/admin_dashboard.php"); // Redirect to admin_dashboard
                exit;
            } else {
                echo "Error: Failed to delete user.";
            }
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
