<?php
session_start();
include_once '../config/db_connection.php';

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$admin_name = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 2rem;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        button {
            padding: 0.5rem 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
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
            <?php
            // Fetch events
            $stmt = $conn->query("SELECT * FROM Events ORDER BY start_time ASC");
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($events)) {
                echo '<table>';
                echo '<tr><th>Title</th><th>Description</th><th>Start Time</th><th>End Time</th><th>Actions</th></tr>';
                foreach ($events as $event) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($event['title']) . '</td>';
                    echo '<td>' . htmlspecialchars($event['description']) . '</td>';
                    echo '<td>' . htmlspecialchars($event['start_time']) . '</td>';
                    echo '<td>' . htmlspecialchars($event['end_time']) . '</td>';
                    echo '<td>
                        <a href="edit_event.php?id=' . $event['id'] . '"><button>Edit</button></a>
                        <a href="delete_event.php?id=' . $event['id'] . '" onclick="return confirm(\'Are you sure?\');"><button>Delete</button></a>
                    </td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No events found.</p>';
            }
            ?>
        </section>

        <!-- Section for managing users -->
        <section>
            <h2>Manage Users</h2>
            <h3>Registered Users</h3>
            <?php
            // Fetch users
            $stmt = $conn->query("SELECT * FROM users ORDER BY name ASC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($users)) {
                echo '<table>';
                echo '<tr><th>Name</th><th>Email</th><th>Phone Number</th><th>Address</th><th>Actions</th></tr>';
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['phone_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['address']) . '</td>';
                    echo '<td>
                        <a href="view_user.php?id=' . $user['id'] . '"><button>View</button></a>
                        <a href="delete_user.php?id=' . $user['id'] . '" onclick="return confirm(\'Are you sure?\');"><button>Delete</button></a>
                    </td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No users found.</p>';
            }
            ?>
        </section>
    </main>
</body>
</html>
