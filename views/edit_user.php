<?php
require_once '../config/db_connection.php';

// Fetch the user data based on the ID passed in the query string
if (isset($_GET['id']) && isset($_GET['role'])) {
  $id = $_GET['id'];
  $role = $_GET['role'];
  $table = $role === 'admin' ? 'admin_users' : 'users';

  $stmt = $conn->prepare("SELECT * FROM $table WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $user = $stmt->fetch();

  if (!$user) {
      echo "No user found.";
      exit;
  }
} else {
  echo "User ID or role is missing.";
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
</head>
<body>
<h1>Edit User</h1>
<form method="POST" action="../controllers/user_controller.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
    <input type="hidden" name="role" value="<?php echo htmlspecialchars($_GET['role']); ?>">

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

    <label for="address">Address:</label>
    <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

    <h3>Emergency Contact 1</h3>
    <label for="emergency_contact_1_name">Name:</label>
    <input type="text" id="emergency_contact_1_name" name="emergency_contact_1_name" value="<?php echo htmlspecialchars($user['emergency_contact_1_name']); ?>">

    <label for="emergency_contact_1_phone">Phone:</label>
    <input type="text" id="emergency_contact_1_phone" name="emergency_contact_1_phone" value="<?php echo htmlspecialchars($user['emergency_contact_1_phone']); ?>">

    <label for="emergency_contact_1_relationship">Relationship:</label>
    <input type="text" id="emergency_contact_1_relationship" name="emergency_contact_1_relationship" value="<?php echo htmlspecialchars($user['emergency_contact_1_relationship']); ?>">

    <h3>Emergency Contact 2</h3>
    <label for="emergency_contact_2_name">Name:</label>
    <input type="text" id="emergency_contact_2_name" name="emergency_contact_2_name" value="<?php echo htmlspecialchars($user['emergency_contact_2_name']); ?>">

    <label for="emergency_contact_2_phone">Phone:</label>
    <input type="text" id="emergency_contact_2_phone" name="emergency_contact_2_phone" value="<?php echo htmlspecialchars($user['emergency_contact_2_phone']); ?>">

    <label for="emergency_contact_2_relationship">Relationship:</label>
    <input type="text" id="emergency_contact_2_relationship" name="emergency_contact_2_relationship" value="<?php echo htmlspecialchars($user['emergency_contact_2_relationship']); ?>">

    <label for="image">Profile Image:</label>
    <input type="file" id="image" name="image">

    <label for="recording_consent">
        <input type="checkbox" id="recording_consent" name="recording_consent" <?php echo $user['recording_consent'] ? 'checked' : ''; ?>> Recording Consent
    </label>

    <label for="injury_loss_risk_consent">
        <input type="checkbox" id="injury_loss_risk_consent" name="injury_loss_risk_consent" <?php echo $user['injury_loss_risk_consent'] ? 'checked' : ''; ?>> Injury or Loss Risk Consent
    </label>

    <label for="signature_date">Signature Date:</label>
    <input type="date" id="signature_date" name="signature_date" value="<?php echo htmlspecialchars($user['signature_date']); ?>">

    <button type="submit">Update User</button>
    <button type="button" onclick="window.history.back();">Cancel</button>
</form>
</body>
</html>
