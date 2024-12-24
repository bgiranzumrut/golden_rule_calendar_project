<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Event</title>
</head>
<body>
    <?php if (isset($event) && !empty($event)): ?>
        <h1>Register for: <?php echo htmlspecialchars($event['title']); ?></h1>
        <p><?php echo htmlspecialchars($event['description']); ?></p>
        <form action="../controllers/registrationController.php?action=registerParticipant" method="POST">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="phone">Your Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <br>
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea>
            <br>
            <button type="submit">Register</button>
        </form>
    <?php else: ?>
        <h1>Event Not Found</h1>
        <p>The event you are trying to register for does not exist or has been removed.</p>
    <?php endif; ?>
</body>
</html>
