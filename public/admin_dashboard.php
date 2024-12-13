<?php
session_start();
require_once '../config/db_connection.php';

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$admin_name = $_SESSION['admin_name'];

try {
    // Fetch events
    $stmt = $conn->query("SELECT * FROM Events ORDER BY start_time ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch users (only existing columns are included)
    $stmt = $conn->query("SELECT id, name, address, phone_number, email, image, recording_consent, injury_loss_risk_consent, created_at, signature_date FROM users ORDER BY name ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch admin users (use relevant columns)
    $stmt = $conn->query("SELECT id, name, phone_number, email, address FROM admin_users ORDER BY name ASC");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Styles for the page */
    </style>
</head>
<body>
    <nav>
        <span>Welcome, Admin <?php echo htmlspecialchars($admin_name); ?></span>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h1>Admin Dashboard</h1>

        <!-- Section for managing events -->
        <section>
            <h2>Manage Events</h2>
            <a href="../views/create_event.php"><button>Add New Event</button></a>
            <h3>Event List</h3>
            <?php if (!empty($events)) : ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td><?php echo htmlspecialchars($event['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($event['end_time']); ?></td>
                            <td>
                                <a href="../views/edit_event.php?id=<?php echo htmlspecialchars($event['id']); ?>">
                                    <button>Edit</button>
                                </a>
                                <form method="POST" action="../controllers/event_controller.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this event?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>No events found.</p>
            <?php endif; ?>
        </section>

        <!-- Section for managing users -->
        <section>
            <h2>Manage Users</h2>
            <a href="../views/create_user.php"><button>Add New User</button></a>
            <h3>Registered Users</h3>
            <?php if (!empty($users)) : ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                            <td>
                                <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user">
                                    <button>Edit</button>
                                </a>
                                <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <input type="hidden" name="role" value="user">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>No users found.</p>
            <?php endif; ?>

            <h3>Admin Users</h3>
            <?php if (!empty($admins)) : ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($admins as $admin) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars($admin['phone_number']); ?></td>
                            <td>
                                <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($admin['id']); ?>&role=admin">
                                    <button>Edit</button>
                                </a>
                                <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin['id']); ?>">
                                    <input type="hidden" name="role" value="admin">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>No admin users found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
