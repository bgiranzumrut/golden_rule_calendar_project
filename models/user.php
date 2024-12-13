<?php 
namespace Models;

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user or admin
    public function createUser($data) {
        // Determine table based on selected role
        $table = ($data['role'] === 'admin') ? 'admin_users' : 'users';

        $query = "INSERT INTO $table (name, phone_number, email, address, 
                  emergency_contact_1_name, emergency_contact_1_phone, emergency_contact_1_relationship, 
                  emergency_contact_2_name, emergency_contact_2_phone, emergency_contact_2_relationship, 
                  image, recording_consent, injury_loss_risk_consent, signature_date";

        if ($table === 'admin_users') {
            $query .= ", password";
        }

        $query .= ") VALUES (:name, :phone_number, :email, :address, 
                  :emergency_contact_1_name, :emergency_contact_1_phone, :emergency_contact_1_relationship, 
                  :emergency_contact_2_name, :emergency_contact_2_phone, :emergency_contact_2_relationship, 
                  :image, :recording_consent, :injury_loss_risk_consent, :signature_date";

        if ($table === 'admin_users') {
            $query .= ", :password";
        }

        $query .= ")";

        $stmt = $this->conn->prepare($query);

        // Bind common parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':phone_number', $data['phone_number']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':emergency_contact_1_name', $data['emergency_contact_1_name']);
        $stmt->bindParam(':emergency_contact_1_phone', $data['emergency_contact_1_phone']);
        $stmt->bindParam(':emergency_contact_1_relationship', $data['emergency_contact_1_relationship']);
        $stmt->bindParam(':emergency_contact_2_name', $data['emergency_contact_2_name']);
        $stmt->bindParam(':emergency_contact_2_phone', $data['emergency_contact_2_phone']);
        $stmt->bindParam(':emergency_contact_2_relationship', $data['emergency_contact_2_relationship']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':recording_consent', $data['recording_consent']);
        $stmt->bindParam(':injury_loss_risk_consent', $data['injury_loss_risk_consent']);
        $stmt->bindParam(':signature_date', $data['signature_date']);

        // Bind password only for admin_users
        if ($table === 'admin_users') {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }

        return $stmt->execute();
    }

    // Update user/admin details
    public function updateUser($data, $id, $role) {
      $table = ($role === 'admin') ? 'admin_users' : 'users';
  
      $query = "UPDATE $table SET 
          name = :name, 
          phone_number = :phone_number, 
          email = :email, 
          address = :address
          WHERE id = :id";
  
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':phone_number', $data['phone_number']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':address', $data['address']);
      $stmt->bindParam(':id', $id);
  
      return $stmt->execute();
  }
  

    // Delete user/admin
    public function deleteUser($id, $role) {
        $table = ($role === 'admin') ? 'admin_users' : 'users';
        $query = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
