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
    
        // Start building the query
        $query = "INSERT INTO $table (
                      name, phone_number, email, address, 
                      emergency_contact_1_name, emergency_contact_1_phone, emergency_contact_1_relationship, 
                      emergency_contact_2_name, emergency_contact_2_phone, emergency_contact_2_relationship, 
                      image, recording_consent, injury_loss_risk_consent, signature_date, extra_notes";
    
        // Add password field only for admin users
        if ($table === 'admin_users') {
            $query .= ", password";
        }
    
        $query .= ") VALUES (
                      :name, :phone_number, :email, :address, 
                      :emergency_contact_1_name, :emergency_contact_1_phone, :emergency_contact_1_relationship, 
                      :emergency_contact_2_name, :emergency_contact_2_phone, :emergency_contact_2_relationship, 
                      :image, :recording_consent, :injury_loss_risk_consent, :signature_date, :extra_notes";
    
        // Add password value only for admin users
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
        $stmt->bindParam(':extra_notes', $data['extra_notes']);
    
        // Bind password only for admin_users
        if ($table === 'admin_users') {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }
    
        return $stmt->execute();
    }
    

    public function updateUser($data, $id, $role, $editedBy) {
        $table = ($role === 'admin') ? 'admin_users' : 'users';
    
        $query = "UPDATE $table SET 
                    name = :name, 
                    phone_number = :phone_number, 
                    email = :email, 
                    address = :address, 
                    emergency_contact_1_name = :emergency_contact_1_name, 
                    emergency_contact_1_phone = :emergency_contact_1_phone, 
                    emergency_contact_1_relationship = :emergency_contact_1_relationship, 
                    emergency_contact_2_name = :emergency_contact_2_name, 
                    emergency_contact_2_phone = :emergency_contact_2_phone, 
                    emergency_contact_2_relationship = :emergency_contact_2_relationship, 
                    image = :image, 
                    recording_consent = :recording_consent, 
                    injury_loss_risk_consent = :injury_loss_risk_consent, 
                    signature_date = :signature_date, 
                    edited_by = :edited_by, 
                    extra_notes = :extra_notes, 
                    last_edit_at = NOW()
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
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
        $stmt->bindParam(':edited_by', $editedBy, \PDO::PARAM_INT);
        $stmt->bindParam(':extra_notes', $data['extra_notes']);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    
    
    
    
  
  // Add this method to fetch a user by name and phone
  public function findUserByNameAndPhone($name, $phone) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name AND phone_number = :phone");
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}




    // Delete user/admin
    public function fetchUserById($id) {
        $query = "SELECT u.*, a.name AS edited_by_name  
                  FROM users u 
                  LEFT JOIN admin_users a 
                  ON u.edited_by = a.id 
                  WHERE u.id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAdminById($id) {
        $query = "SELECT a.*, u.name AS edited_by_name 
                  FROM admin_users a 
                  LEFT JOIN admin_users u 
                  ON a.edited_by = u.id 
                  WHERE a.id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function deleteUser($id, $role) {
    $table = ($role === 'admin') ? 'admin_users' : 'users';

    $query = "DELETE FROM $table WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

    return $stmt->execute();
}

    
}
