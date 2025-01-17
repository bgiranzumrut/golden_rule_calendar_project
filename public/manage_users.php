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
  <style>
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 20px;
          background-color: #f5f5f5;
      }
      .container {
          max-width: 1200px;
          margin: 0 auto;
          background-color: white;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }
      h1 {
          color: #333;
          margin-bottom: 20px;
      }
      .search-bar {
          margin: 20px 0;
          padding: 15px;
          background-color: #f8f9fa;
          border-radius: 5px;
          display: flex;
          gap: 10px;
          align-items: center;
          flex-wrap: wrap;
      }
      .search-bar input,
      .search-bar select {
          padding: 8px 12px;
          border: 1px solid #ddd;
          border-radius: 4px;
          font-size: 14px;
      }
      .table-container {
          overflow-x: auto;
      }
      table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
      }
      th, td {
          padding: 12px;
          text-align: left;
          border: 1px solid #ddd;
      }
      th {
          background-color: #f8f9fa;
          font-weight: bold;
      }
      tr:nth-child(even) {
          background-color: #f9f9f9;
      }
      .action-buttons {
          display: flex;
          gap: 5px;
      }
      .btn {
          padding: 6px 12px;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          color: white;
          text-decoration: none;
          font-size: 14px;
      }
      .btn-primary {
          background-color: #007bff;
      }
      .btn-secondary {
          background-color: #6c757d;
      }
      .btn-danger {
          background-color: #dc3545;
      }
      .btn:hover {
          opacity: 0.9;
      }
      @media print {
          .no-print {
              display: none;
          }
          .container {
              box-shadow: none;
              margin: 0;
              padding: 0;
          }
          th {
              background-color: #f8f9fa !important;
              -webkit-print-color-adjust: exact;
          }
      }
  </style>
</head>
<body>
  <div class="container">
      <h1>Manage Users</h1>

      <div class="search-bar no-print">
          <a href="views/create_user.php" class="btn btn-primary">Add New User</a>

          <form method="GET" style="display: flex; gap: 10px; align-items: center;">
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
          </form>

          <button onclick="window.print()" class="btn btn-secondary">Print Users</button>
      </div>

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
                      <td class="no-print">
                          <div class="action-buttons">
                              <a href="views/edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user"
                                 class="btn btn-primary">Edit</a>
                              <a href="views/user_details.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user"
                                 class="btn btn-secondary">Details</a>
                              <form method="POST" action="controllers/user_controller.php" style="display:inline;">
                                  <input type="hidden" name="action" value="delete">
                                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                  <input type="hidden" name="role" value="user">
                                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                  <button type="submit"
                                          class="btn btn-danger"
                                          onclick="return confirm('Are you sure you want to delete this user?');">
                                      Delete
                                  </button>
                              </form>
                          </div>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>
</body>
</html>
