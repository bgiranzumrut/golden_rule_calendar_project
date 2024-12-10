<?php
include '../config/db_connection.php';

header('Content-Type: application/json');

$sql = "SELECT id, title, start_time AS start, end_time AS end FROM Events";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>
