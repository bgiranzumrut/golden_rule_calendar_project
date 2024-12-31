<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "golden_rules_calendar";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Category
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle Edit Category
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all categories
$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
</head>
<body>
<nav>
    <nav>
        <a href="logout.php">Logout</a>
        <a href="admin_dashboard.php">Home</a>
        <a href="admin_gallery.php">Gallery</a>
    </nav>
    </nav>
    <h1>Manage Categories</h1>

    <!-- Add Category -->
    <form method="POST">
        <label>Category Name: </label>
        <input type="text" name="name" required>
        <button type="submit" name="add">Add Category</button>
    </form>

    <h2>Existing Categories</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                    <button type="submit" name="edit">Edit</button>
                </form>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
