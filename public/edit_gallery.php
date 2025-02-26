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

// Get image ID
if (!isset($_GET['id'])) {
    die("Image ID not provided.");
}

$id = intval($_GET['id']);
$image_result = $conn->query("SELECT * FROM gallery WHERE id = $id");
$image = $image_result->fetch_assoc();

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

    $stmt = $conn->prepare("UPDATE gallery SET title=?, description=?, upload_time=?, category=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $description, $date, $category, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_gallery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Edit Image</title>
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
    <input type="text" name="title" value="<?= htmlspecialchars($image['title']) ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?= htmlspecialchars($image['description']) ?></textarea>

    <label>Date:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($image['upload_time']) ?>" required>

    <label>Category:</label>
    <select name="category" required>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['name']) ?>" 
                <?= ($row['name'] === $image['category']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
