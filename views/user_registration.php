<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User and Admin Registration</title>
</head>
<body>
<form method="POST" action="../controllers/user_controller.php" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email">

    <label for="address">Address:</label>
    <textarea id="address" name="address"></textarea>

    <h3>Emergency Contact 1</h3>
    <label for="emergency_contact_1_name">Name:</label>
    <input type="text" id="emergency_contact_1_name" name="emergency_contact_1_name">
    
    <label for="emergency_contact_1_phone">Phone:</label>
    <input type="text" id="emergency_contact_1_phone" name="emergency_contact_1_phone">
    
    <label for="emergency_contact_1_relationship">Relationship:</label>
    <input type="text" id="emergency_contact_1_relationship" name="emergency_contact_1_relationship">

    <h3>Emergency Contact 2</h3>
    <label for="emergency_contact_2_name">Name:</label>
    <input type="text" id="emergency_contact_2_name" name="emergency_contact_2_name">
    
    <label for="emergency_contact_2_phone">Phone:</label>
    <input type="text" id="emergency_contact_2_phone" name="emergency_contact_2_phone">
    
    <label for="emergency_contact_2_relationship">Relationship:</label>
    <input type="text" id="emergency_contact_2_relationship" name="emergency_contact_2_relationship">

    <label for="image">Profile Image:</label>
    <input type="file" id="image" name="image">

    <label for="recording_consent">
        <input type="checkbox" id="recording_consent" name="recording_consent"> Recording Consent
    </label>
    
    <label for="injury_loss_risk_consent">
        <input type="checkbox" id="injury_loss_risk_consent" name="injury_loss_risk_consent"> Injury or Loss Risk Consent
    </label>

    <label for="signature_date">Signature Date:</label>
    <input type="date" id="signature_date" name="signature_date">

    <!-- Differentiation between User and Admin -->
    <label for="role">Register As:</label>
    <select id="role" name="role" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>

    <label for="password">Password (for Admins only):</label>
    <input type="password" id="password" name="password">

    <button type="submit">Register</button>
</form>
</body>
</html>
