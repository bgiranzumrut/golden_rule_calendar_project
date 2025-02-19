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

// Handle Category Addition
if (isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: manage_categories.php");
    exit();
}

// Handle Category Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $conn->query("DELETE FROM categories WHERE id = $id");

    // **Reorder IDs after deletion**
    $conn->query("SET @count = 0;");
    $conn->query("UPDATE categories SET id = @count:= @count + 1;");
    $conn->query("ALTER TABLE categories AUTO_INCREMENT = 1;");

    header("Location: manage_categories.php");
    exit();
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 60%; border-collapse: collapse; margin: 20px auto; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .delete-btn { color: red; text-decoration: none; margin-left: 10px; }
        .edit-btn { color: blue; text-decoration: none; margin-left: 10px; }

        /* 🔹 Centered form */
        .form-container {
            width: 50%;
            margin: 0 auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        input {
            width: 70%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            padding: 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<nav>
    <a href="logout.php">Logout</a>
    <a href="home.php">Home</a>
    <a href="gallery.php">Gallery</a>
</nav>

<h1 style="text-align: center;">Manage Categories</h1>

<!-- 🔹 Centered Add Category Form -->
<div class="form-container">
    <form method="POST">
        <label>Category Name:</label>
        <input type="text" name="category_name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>
</div>

<h2 style="text-align: center;">Existing Categories</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $categories->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><input type="text" value="<?= htmlspecialchars($row['name']) ?>" readonly></td>
        <td>
            <a href="edit_category.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
            <a href="?delete=<?= $row['id'] ?>" class="delete-btn">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
