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

// Fetch all images
$result = $conn->query("SELECT * FROM gallery");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
</head>
<body>
    <nav>
    <div>
            <a href="index.php">Home</a>
            <a href="../views/user_registration.php">Register</a>
            <a href="about.php">About</a>
        </div>
    </nav>
    <h1>Gallery</h1>
    <div style="display: flex; flex-wrap: wrap;">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="margin: 10px; border: 1px solid #ccc; padding: 10px; width: 250px;">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p> <!-- Display Category -->
                <p><?= htmlspecialchars($row['description']) ?></p>
                <img src="<?= $row['image_path'] ?>" alt="Image" width="200" height="200">
                <p><small>Uploaded: <?= $row['upload_time'] ?></small></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
