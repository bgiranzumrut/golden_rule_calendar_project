<?php
// Include the centralized database connection
$conn = require_once __DIR__ . '/../config/db_connection.php';

// Fetch categories
$stmt = $conn->query("SELECT * FROM categories");

// Handle category addition
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindValue(':name', $category_name);
        $stmt->execute();

        header("Location: manage_categories.php");
        exit();
    } else {
        echo "Category name cannot be empty.";
    }
}

$conn = null; // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form, table { margin: 20px auto; width: 80%; }
        input[type="text"] { width: 100%; padding: 8px; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Manage Categories</h1>

<form method="POST">
    <input type="text" name="category_name" placeholder="New Category Name" required>
    <button type="submit">Add Category</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>
            <a href="edit_category.php?id=<?= $row['id'] ?>">Edit</a>
            <a href="delete_category.php?id=<?= $row['id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
