<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Event</title>
</head>
<body>
    <?php if (isset($event) && !empty($event)): ?>
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p><?php echo htmlspecialchars($event['description']); ?></p>

        <?php if (isset($participantCount) && isset($participants)): ?>
            <h2>Number of Participants: <?php echo htmlspecialchars($participantCount); ?></h2>
            <ul>
                <?php foreach ($participants as $participant): ?>
                    <?php if (!empty($participant['registered_user_name'])): ?>
                        <li>Registered User: <?php echo htmlspecialchars($participant['registered_user_name']); ?> (Phone: <?php echo htmlspecialchars($participant['registered_user_phone']); ?>)</li>
                    <?php else: ?>
                        <li>Unregistered Participant: <?php echo htmlspecialchars($participant['participant_name']); ?> (Phone: <?php echo htmlspecialchars($participant['participant_phone']); ?>)</li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No participants have registered yet.</p>
        <?php endif; ?>

        <form action="../controllers/registrationController.php?action=registerParticipant" method="POST">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
            <label for="name">Enter your name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="phone">Enter your phone number:</label>
            <input type="text" id="phone" name="phone" required>
            <br>
            <label for="notes">Type your notes:</label>
            <textarea id="notes" name="notes"></textarea>
            <br>
            <button type="submit">Click to Join</button>
        </form>
    <?php else: ?>
        <h1>Event Not Found</h1>
        <p>The event you are trying to register for does not exist or has been removed.</p>
    <?php endif; ?>
</body>
</html>
