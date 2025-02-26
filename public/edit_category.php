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

// Get category details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM categories WHERE id = $id");

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
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
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage_categories.php");
        exit();
    } else {
        echo "Category name cannot be empty.";
    }
}

$conn->close();
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
