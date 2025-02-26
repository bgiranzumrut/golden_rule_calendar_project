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

// Handle Image Upload
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $category = $_POST['category'];

    if (empty($date)) {
        die("Date field is empty. Please select a date.");
    }

    // File upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_path, upload_time, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $target_file, $date, $category);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error uploading image.");
    }

    header("Location: admin_gallery.php");
    exit();
}

// Handle Image Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get image path before deletion
    $result = $conn->query("SELECT image_path FROM gallery WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        $image_path = $row['image_path'];

        // Delete image file from server
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete from database
        $conn->query("DELETE FROM gallery WHERE id = $id");

        // Reorder IDs after deletion
        $conn->query("SET @count = 0;");
        $conn->query("UPDATE gallery SET id = @count:= @count + 1;");
        $conn->query("ALTER TABLE gallery AUTO_INCREMENT = 1;");

        header("Location: admin_gallery.php");
        exit();
    }
}

// Fetch all images for display
$result = $conn->query("SELECT * FROM gallery ORDER BY id ASC");

// Fetch categories for dropdown
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { width: 100px; height: auto; }
        .delete-btn { color: red; text-decoration: none; margin-right: 10px; }
        .edit-btn { color: blue; text-decoration: none; margin-right: 10px; }

        /* 🔹 Centered Form & Background */
        .form-container {
            width: 50%; /* 🔹 Half the page width */
            margin: 0 auto; /* Centering */
            background: #f4f4f4; /* Light grey background */
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        /* 🔹 Make input boxes also half width */
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<nav>
    <a href="logout.php">Logout</a>
    <a href="admin_dashboard.php">Home</a>
    <a href="manage_categories.php">Add Category</a>
</nav>

<h1>Admin Gallery</h1>

<!-- 🔹 Centered Half-Width Form -->
<div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Category:</label>
        <select name="category" required>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="submit">Upload</button>
    </form>
</div>

<h2>Uploaded Images</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Short Description</th>
        <th>Image</th>
        <th>Date</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>
            <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
                <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image">
            <?php else: ?>
                <span style="color: red;">Image Not Found</span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['upload_time']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td>
            <a href="edit_gallery.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
            <a href="?delete=<?= $row['id'] ?>" class="delete-btn">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
