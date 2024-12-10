<?php
include_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs
    $name = htmlspecialchars($_POST['name']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address'] ?? null);
    $emergency_contact_1_name = htmlspecialchars($_POST['emergency_contact_1_name'] ?? null);
    $emergency_contact_1_phone = htmlspecialchars($_POST['emergency_contact_1_phone'] ?? null);
    $emergency_contact_1_relationship = htmlspecialchars($_POST['emergency_contact_1_relationship'] ?? null);
    $emergency_contact_2_name = htmlspecialchars($_POST['emergency_contact_2_name'] ?? null);
    $emergency_contact_2_phone = htmlspecialchars($_POST['emergency_contact_2_phone'] ?? null);
    $emergency_contact_2_relationship = htmlspecialchars($_POST['emergency_contact_2_relationship'] ?? null);
    $recording_consent = isset($_POST['recording_consent']) ? 1 : 0;
    $injury_loss_risk_consent = isset($_POST['injury_loss_risk_consent']) ? 1 : 0;
    $signature_date = $_POST['signature_date'] ?? null;

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_dir = '../uploads/profile_images/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true); // Create directory if it doesn't exist
        }

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            echo "Invalid image type. Only JPG, PNG, and GIF are allowed.";
            exit;
        }
        if ($_FILES['image']['size'] > 2 * 1024 * 1024) { // 2MB limit
            echo "Image size exceeds the 2MB limit.";
            exit;
        }

        $image = $image_dir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            echo "Error uploading image.";
            exit;
        }
    }

    // Insert data into the database
    try {
        $stmt = $conn->prepare("INSERT INTO Users 
            (name, phone_number, email, address, emergency_contact_1_name, emergency_contact_1_phone, emergency_contact_1_relationship,
             emergency_contact_2_name, emergency_contact_2_phone, emergency_contact_2_relationship, image, recording_consent, 
             injury_loss_risk_consent, signature_date) 
            VALUES 
            (:name, :phone_number, :email, :address, :emergency_contact_1_name, :emergency_contact_1_phone, :emergency_contact_1_relationship,
             :emergency_contact_2_name, :emergency_contact_2_phone, :emergency_contact_2_relationship, :image, :recording_consent, 
             :injury_loss_risk_consent, :signature_date)");

        $stmt->execute([
            ':name' => $name,
            ':phone_number' => $phone_number,
            ':email' => $email,
            ':address' => $address,
            ':emergency_contact_1_name' => $emergency_contact_1_name,
            ':emergency_contact_1_phone' => $emergency_contact_1_phone,
            ':emergency_contact_1_relationship' => $emergency_contact_1_relationship,
            ':emergency_contact_2_name' => $emergency_contact_2_name,
            ':emergency_contact_2_phone' => $emergency_contact_2_phone,
            ':emergency_contact_2_relationship' => $emergency_contact_2_relationship,
            ':image' => $image,
            ':recording_consent' => $recording_consent,
            ':injury_loss_risk_consent' => $injury_loss_risk_consent,
            ':signature_date' => $signature_date
        ]);

        echo "User registered successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
