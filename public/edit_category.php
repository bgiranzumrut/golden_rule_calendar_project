<?php
// Include the centralized database connection
$conn = require_once __DIR__ . '/../config/db_connection.php';

// Get category details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the category details
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        // Category found, proceed with the rest of the code
    } else {
        die("Category not found.");
    }
} else {
    die("Invalid category ID.");
}

// Handle Category Update
if (isset($_POST['update_category'])) {
    $new_name = trim($_POST['category_name']);

    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->bindValue(':name', $new_name);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
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
    <title>Edit Category</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form {
            width: 40%;
            margin: auto;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
        }
        input {
            width: 80%;
            padding: 8px;
            margin-top: 10px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Edit Category</h1>

<form method="POST">
    <label>Category Name:</label>
    <input type="text" name="category_name" value="<?= htmlspecialchars($category['name']) ?>" required>
    <button type="submit" name="update_category"> Update</button>
</form>

</body>
</html>
