<?php
session_start();
require_once '../config/db_connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getEventsData($conn) {
    try {
        $search = $_GET['events_search'] ?? '';
        $sort = $_GET['events_sort'] ?? 'start_time';
        $order = $_GET['events_order'] ?? 'ASC';

        $query = "SELECT * FROM events";
        if ($search) {
            $query .= " WHERE title LIKE :search OR description LIKE :search";
        }
        $query .= " ORDER BY $sort $order";

        $stmt = $conn->prepare($query);
        if ($search) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching events: " . $e->getMessage());
        return [];
    }
}

$eventsData = getEventsData($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Manage Events</title>
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
    <div class="container">
        <h1>Manage Events</h1>
        <a href="../views/create_event.php"><button>Add New Event</button></a>

        <form method="GET" class="search-form">
            <input type="text" name="events_search"
                   placeholder="Search events..."
                   value="<?php echo htmlspecialchars($_GET['events_search'] ?? ''); ?>">

            <select name="events_sort">
                <option value="start_time" <?php echo ($_GET['events_sort'] ?? '') === 'start_time' ? 'selected' : ''; ?>>Start Time</option>
                <option value="title" <?php echo ($_GET['events_sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Title</option>
            </select>

            <select name="events_order">
                <option value="ASC" <?php echo ($_GET['events_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                <option value="DESC" <?php echo ($_GET['events_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>Descending</option>
            </select>

            <button type="submit">Search</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventsData as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                        <td><?php echo htmlspecialchars($event['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($event['end_time']); ?></td>
                        <td class="actions">
                            <a href="../views/edit_event.php?id=<?php echo htmlspecialchars($event['id']); ?>">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <a href="../views/event_details.php?id=<?php echo htmlspecialchars($event['id']); ?>">
                                <button class="btn btn-secondary">Details</button>
                            </a>
                            <form method="POST" action="../controllers/event_controller.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>