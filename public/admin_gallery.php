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
    
    // File upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $target_file);
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
    </nav>
    </nav>
    <h1>Admin Gallery</h1>

    <!-- Form for Image Upload -->
    <form method="POST" enctype="multipart/form-data">
        <label>Title: </label><input type="text" name="title" required><br>
        <label>Description: </label><textarea name="description" required></textarea><br>
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
            <th>Uploaded Time</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><img src="<?= $row['image_path'] ?>" width="100" height="100"></td>
            <td><?= $row['upload_time'] ?></td>
            <td><a href="?delete=<?= $row['id'] ?>">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
