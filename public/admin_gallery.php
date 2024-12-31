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
    $category = $_POST['category']; // Capture the category field

    if (empty($date)) {
        die("Date field is empty. Please select a date.");
    }

    // File upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_path, upload_time, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $target_file, $date, $category);
    $stmt->execute();
    $stmt->close();
}

// Handle Image Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM gallery WHERE id = $id");
}

// Fetch all images for display
$result = $conn->query("SELECT * FROM gallery");

// Fetch categories for the dropdown
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Gallery</title>
</head>
<body>
    <nav>
    <nav>
        <a href="logout.php">Logout</a>
        <a href="admin_dashboard.php">Home</a>
        <a href="manage_categories.php">Add Category</a>
    </nav>
    </nav>
    <h1>Admin Gallery</h1>

    <!-- Form for Image Upload -->
    <form method="POST" enctype="multipart/form-data">
    <label>Title: </label><input type="text" name="title" required><br>
    <label>Description: </label><textarea name="description" required></textarea><br>
    <label>Date: </label><input type="date" name="date" required><br>
    <label>Category: </label>
    <select name="category" required>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
    </select><br>
    <label>Image: </label><input type="file" name="image" accept="image/*" required><br>
    <button type="submit" name="submit">Upload</button>
</form>


    <h2>Uploaded Images</h2>
    <table border="1">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Image</th>
        <th>Date</th>
        <th>Category</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['title'] ?></td>
        <td><?= $row['description'] ?></td>
        <td><img src="<?= $row['image_path'] ?>" width="100" height="100"></td>
        <td><?= $row['upload_time'] ?></td>
        <td><?= $row['category'] ?></td>
        <td><a href="?delete=<?= $row['id'] ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
<?php $conn->close(); ?>
