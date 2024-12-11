<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
</head>
<body>
    <h1>Create New Event</h1>
    <form method="POST" action="../controllers/event_controller.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="create">
    <label for="title">Event Title:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="description">Event Description:</label>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="start_time">Start Time:</label>
    <input type="datetime-local" id="start_time" name="start_time"><br><br>

    <label for="end_time">End Time:</label>
    <input type="datetime-local" id="end_time" name="end_time"><br><br>

    <label for="image">Event Image:</label>
    <input type="file" id="image" name="image" accept="image/*"><br><br>

    <button type="submit">Create Event</button>
</form>

</body>
</html>
