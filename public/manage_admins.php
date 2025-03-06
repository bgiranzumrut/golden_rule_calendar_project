<?php
session_start();
require_once '../config/db_connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

function getAdminUsersData($conn) {
    try {
        $search = $_GET['admins_search'] ?? '';
        $sort = $_GET['admins_sort'] ?? 'name';
        $order = $_GET['admins_order'] ?? 'ASC';

        $query = "SELECT * FROM admin_users";
        if ($search) {
            $query .= " WHERE name LIKE :search OR email LIKE :search";
        }
        $query .= " ORDER BY $sort $order";

        $stmt = $conn->prepare($query);
        if ($search) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching admin users: " . $e->getMessage());
        return [];
    }
}

$adminsData = getAdminUsersData($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin Users</title>
    <link rel="stylesheet" href="../styles/admin.css">

</head>
<body>
    <div class="container">
        <h1>Manage Admin Users</h1>

        <form method="GET" class="search-form no-print">
            <input type="text" name="admins_search"
                   placeholder="Search admin users..."
                   value="<?php echo htmlspecialchars($_GET['admins_search'] ?? ''); ?>">

            <select name="admins_sort">
                <option value="name" <?php echo ($_GET['admins_sort'] ?? '') === 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="email" <?php echo ($_GET['admins_sort'] ?? '') === 'email' ? 'selected' : ''; ?>>Email</option>
            </select>

            <select name="admins_order">
                <option value="ASC" <?php echo ($_GET['admins_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                <option value="DESC" <?php echo ($_GET['admins_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>Descending</option>
            </select>

            <button type="submit">Search</button>
            <button type="button" onclick="window.print()">Print Admin Users</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th class="no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adminsData as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['name']); ?></td>
                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td><?php echo htmlspecialchars($admin['phone_number']); ?></td>
                        <td class="actions no-print">
                            <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($admin['id']); ?>&role=admin">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <a href="../views/user_details.php?id=<?php echo htmlspecialchars($admin['id']); ?>&role=admin">
                                <button class="btn btn-secondary">Details</button>
                            </a>
                            <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin['id']); ?>">
                                <input type="hidden" name="role" value="admin">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this admin user?');">
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