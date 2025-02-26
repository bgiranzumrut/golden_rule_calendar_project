<?php
// Include the centralized database connection
$conn = require_once __DIR__ . '/../config/db_connection.php';

// Get image ID
if (!isset($_GET['id'])) {
    die("Image ID not provided.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM gallery WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Fetch the gallery item details
$galleryItem = $stmt->fetch(PDO::FETCH_ASSOC);

if ($galleryItem) {
    // Gallery item found, proceed with the rest of the code
} else {
    die("Gallery item not found.");
}

// Fetch categories
$categories = $conn->query("SELECT * FROM categories");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $category = trim($_POST['category']);

    if (empty($title) || empty($description) || empty($date) || empty($category)) {
        die("All fields are required!");
    }

    // Update the gallery item using PDO
    $stmt = $conn->prepare("UPDATE gallery SET title = :title, description = :description, upload_time = :upload_time, category = :category WHERE id = :id");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':upload_time', $date);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin_gallery.php");
    exit();
}

$conn = null; // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Image</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        label { display: block; margin-top: 10px; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<h2>Edit Image</h2>

<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($galleryItem['title']) ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?= htmlspecialchars($galleryItem['description']) ?></textarea>

    <label>Date:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($galleryItem['upload_time']) ?>" required>

    <label>Category:</label>
    <select name="category" required>
        <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= htmlspecialchars($row['name']) ?>" 
                <?= ($row['name'] === $galleryItem['category']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
