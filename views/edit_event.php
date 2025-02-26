<?php
session_start();
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event;

// Ensure tha admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header('Location: login.php');
  exit;
}

$eventModel = new Event($conn);

// Fetch the event details
if (isset($_GET['id'])) {
  $eventId = $_GET['id'];
  $event = $eventModel->fetchEventById($eventId);
  if (!$event) {
      echo "Error: Event not found.";
      exit;
  }
} else {
  echo "Error: Event ID not specified.";
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
</head>
<body>
    <h1>Edit Event</h1>
    <form method="POST" action="../controllers/event_controller.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">

        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required><br><br>

        <label for="description">Event Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

        <label for="start_time">Start Time:</label>
        <input type="datetime-local" id="start_time" name="start_time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_time'])); ?>"><br><br>

        <label for="end_time">End Time:</label>
        <input type="datetime-local" id="end_time" name="end_time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['end_time'])); ?>"><br><br>
<label for="short_name">Short Name:</label>
<input type="text" id="short_name" name="short_name"
       value="<?php echo htmlspecialchars($event['short_name'] ?? ''); ?>" required>
<br><br>

        <label for="image">Event Image:</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>
        <?php if (!empty($event['image'])): ?>
            <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" style="max-width: 200px;"><br><br>
        <?php endif; ?>

        <button type="submit">Update Event</button>
    </form>
</body>
</html>