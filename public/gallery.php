<?php
// Include the centralized database connection
$conn = require_once __DIR__ . '/../config/db_connection.php';
include("header.php");

// Pagination setup
$images_per_page = 12; // Adjust number of images per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $images_per_page;

// Fetch images for the current page
$total_images_stmt = $conn->query("SELECT COUNT(*) AS total FROM gallery");
$total_images = $total_images_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_images / $images_per_page);

$stmt = $conn->prepare("SELECT * FROM gallery LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $images_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="../styles/gallery_styles.css">
    <script src="../styles/gallery_script.js" defer></script>
</head>
<body>

    <div class="gallery-container">
        <?php if ($current_page > 1): ?>
            <button class="nav-btn prev-btn" onclick="location.href='?page=<?= $current_page - 1 ?>'">&lt;</button>
        <?php endif; ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <?php if (!empty($row['image_path'])): // Check if image exists ?>
                <div class="gallery-item"
                    data-title="<?= htmlspecialchars($row['title']) ?>"
                    data-category="<?= htmlspecialchars($row['category']) ?>"
                    data-description="<?= htmlspecialchars($row['description']) ?>"
                    data-date="<?= htmlspecialchars($row['upload_time']) ?>">
                    <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="content">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                    </div>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>

        <?php if ($current_page < $total_pages): ?>
            <button class="nav-btn next-btn" onclick="location.href='?page=<?= $current_page + 1 ?>'">&gt;</button>
        <?php endif; ?>
    </div>

</body>
</html>
<?php include("footer.php"); // Include existing footer ?>
<?php $conn = null; // Close the connection ?>
