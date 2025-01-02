<?php 
namespace Models;

class Event {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new event
    public function createEvent($title, $description, $start_time, $end_time, $created_by, $image = null) {
        $stmt = $this->conn->prepare("INSERT INTO Events (title, description, start_time, end_time, created_by, image) 
                                      VALUES (:title, :description, :start_time, :end_time, :created_by, :image)");
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':created_by' => $created_by,
            ':image' => $image
        ]);
    }

    // Update an existing event
    // Update an existing event with editor details
    public function updateEvent($id, $title, $description, $start_time, $end_time, $image = null, $edited_by = null) {
        $stmt = $this->conn->prepare("
            UPDATE Events 
            SET title = :title, 
                description = :description, 
                start_time = :start_time, 
                end_time = :end_time, 
                image = :image, 
                edited_by = :edited_by
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':image' => $image,
            ':edited_by' => $edited_by
        ]);
    }
    
    


    // Delete an event
    public function deleteEvent($id) {
        $stmt = $this->conn->prepare("DELETE FROM Events WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Fetch all events
    public function fetchAllEvents() {
        $stmt = $this->conn->prepare("SELECT * FROM Events ORDER BY start_time ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Fetch event by ID
    public function fetchEventById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Events WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Fetch events within a date range
    public function fetchEventsByDateRange($startDate, $endDate) {
        $stmt = $this->conn->prepare("SELECT * FROM Events 
                                      WHERE DATE(start_time) BETWEEN :start_date AND :end_date 
                                      ORDER BY start_time ASC");
        $stmt->execute([
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Delete all events for a specific month and year
    public function deleteEventsByMonth($year, $month) {
        $startDate = sprintf("%04d-%02d-01", $year, $month);
        $endDate = date("Y-m-t", strtotime($startDate));

        $stmt = $this->conn->prepare("DELETE FROM Events 
                                      WHERE DATE(start_time) BETWEEN :start_date AND :end_date");
        return $stmt->execute([
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
    }
    public function registerParticipant($eventId, $name, $phone, $notes = null) {
        $stmt = $this->conn->prepare("INSERT INTO registrations (event_id, name, phone_number, registered_at, notes) 
                                      VALUES (:event_id, :name, :phone, NOW(), :notes)");
        return $stmt->execute([
            ':event_id' => $eventId,
            ':name' => $name,
            ':phone' => $phone,
            ':notes' => $notes
        ]);
    }
    
    public function registerParticipantWithOptionalUserId($eventId, $userId = null, $name, $phone, $notes = null) {
        $stmt = $this->conn->prepare("INSERT INTO registrations (event_id, user_id, name, phone_number, notes, registered_at) 
                                      VALUES (:event_id, :user_id, :name, :phone, :notes, NOW())");
        return $stmt->execute([
            ':event_id' => $eventId,
            ':user_id' => $userId,
            ':name' => $name,
            ':phone' => $phone,
            ':notes' => $notes
        ]);
    }
    
    public function fetchRegistrationsByEvent($eventId) {
        $stmt = $this->conn->prepare("SELECT 
                                            r.name AS participant_name, 
                                            r.phone_number AS participant_phone, 
                                            u.name AS registered_user_name, 
                                            u.phone_number AS registered_user_phone 
                                       FROM registrations r 
                                       LEFT JOIN users u ON r.user_id = u.id 
                                       WHERE r.event_id = :event_id 
                                       ORDER BY r.registered_at ASC");
        $stmt->execute([':event_id' => $eventId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    
    public function fetchRegistrationByUserAndEvent($eventId, $userId) {
        $stmt = $this->conn->prepare("SELECT * FROM registrations 
                                      WHERE event_id = :event_id AND user_id = :user_id");
        $stmt->execute([
            ':event_id' => $eventId,
            ':user_id' => $userId
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
}
?>
