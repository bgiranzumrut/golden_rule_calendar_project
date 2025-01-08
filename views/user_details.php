<?php 
require_once '../config/db_connection.php';
require_once '../models/user.php';

use Models\User;

$userModel = new User($conn);

$id = $_GET['id'] ?? null; // Get the user/admin ID from the URL
$role = $_GET['role'] ?? 'user'; // Get the role (default to 'user')

if (!$id) {
    echo "Error: No ID provided.";
    exit;
}

// Fetch the details based on the role
$user = $role === 'admin' 
    ? $userModel->fetchAdminById($id) // Fetch admin user
    : $userModel->fetchUserById($id); // Fetch regular user

if (!$user) {
    echo "User not found";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
</head>
<body>
    <h1><?php echo $role === 'admin' ? 'Admin User Details' : 'User Details'; ?></h1>
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
    <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_number'] ?? 'N/A'); ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($user['address'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 1 - Name:</strong> <?= htmlspecialchars($user['emergency_contact_1_name'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 1 - Phone:</strong> <?= htmlspecialchars($user['emergency_contact_1_phone'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 1 - Relationship:</strong> <?= htmlspecialchars($user['emergency_contact_1_relationship'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 2 - Name:</strong> <?= htmlspecialchars($user['emergency_contact_2_name'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 2 - Phone:</strong> <?= htmlspecialchars($user['emergency_contact_2_phone'] ?? 'N/A'); ?></p>
    <p><strong>Emergency Contact 2 - Relationship:</strong> <?= htmlspecialchars($user['emergency_contact_2_relationship'] ?? 'N/A'); ?></p>
    <p><strong>Recording Consent:</strong> <?= htmlspecialchars($user['recording_consent'] ? 'Yes' : 'No'); ?></p>
    <p><strong>Injury/Loss Risk Consent:</strong> <?= htmlspecialchars($user['injury_loss_risk_consent'] ? 'Yes' : 'No'); ?></p>
    <p><strong>Signature Date:</strong> <?= htmlspecialchars($user['signature_date'] ?? 'N/A'); ?></p>
    <p><strong>Image:</strong> <?= !empty($user['image']) ? '<img src="../uploads/' . htmlspecialchars($user['image']) . '" alt="User Image" width="100">' : 'N/A'; ?></p>
    <p><strong>Extra Notes:</strong> <?= htmlspecialchars($user['extra_notes'] ?? 'N/A'); ?></p>
    <p><strong>Last Edited By:</strong> <?= htmlspecialchars($user['edited_by_name'] ?? 'N/A'); ?></p>
    <p><strong>Last Edited At:</strong> <?= htmlspecialchars($user['last_edit_at'] ?? 'N/A'); ?></p>

    <button onclick="window.history.back();">Back</button>
</body>
</html>
