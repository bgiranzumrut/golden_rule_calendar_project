<?php
session_start();
require_once '../config/db_connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

function getUsersData($conn) {
  try {
      $search = $_GET['users_search'] ?? '';
      $sort = $_GET['users_sort'] ?? 'name';
      $order = $_GET['users_order'] ?? 'ASC';

      $query = "SELECT * FROM users";
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
      error_log("Error fetching users: " . $e->getMessage());
      return [];
  }
}

$usersData = getUsersData($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
<div class="container">
    <h1>Manage Users</h1>

    <form method="GET" class="search-form no-print">
    <div class="search-container">
        <a href="../views/create_user.php" class="btn btn-primary">Add New User</a>

        <input type="text" name="users_search"
               placeholder="Search users..."
               value="<?php echo htmlspecialchars($_GET['users_search'] ?? ''); ?>">

        <select name="users_sort">
            <option value="name" <?php echo ($_GET['users_sort'] ?? '') === 'name' ? 'selected' : ''; ?>>Name</option>
            <option value="email" <?php echo ($_GET['users_sort'] ?? '') === 'email' ? 'selected' : ''; ?>>Email</option>
        </select>

        <select name="users_order">
            <option value="ASC" <?php echo ($_GET['users_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
            <option value="DESC" <?php echo ($_GET['users_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="submit" class="btn btn-primary">Search</button>
        <button type="button" onclick="window.print()" class="btn btn-secondary">Print Users</button>
    </div>
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
                <?php foreach ($usersData as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                    <td class="actions no-print">
                        <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user"
                           class="btn btn-primary">Edit</a>
                        <a href="../views/user_details.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user"
                           class="btn btn-secondary">Details</a>
                        <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <input type="hidden" name="role" value="user">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this user?');">
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
