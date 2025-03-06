<?php
// Include the centralized database connection (using PDO)
$conn = require_once __DIR__ . '/../config/db_connection.php';

// Get current date/time for comparing with past events
$currentDateTime = date('Y-m-d H:i:s');

// Set up pagination
$events_per_page = 9;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $events_per_page;

// Get total number of past events
$total_events_query = "SELECT COUNT(*) as total FROM events WHERE end_time < :current_time";
$stmt = $conn->prepare($total_events_query);
$stmt->bindParam(':current_time', $currentDateTime);
$stmt->execute();
$total_events = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_events / $events_per_page);

// Get past events with pagination
$query = "SELECT * FROM events WHERE end_time < :current_time ORDER BY end_time DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($query);
$stmt->bindParam(':current_time', $currentDateTime);
$stmt->bindParam(':limit', $events_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$past_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Archive - Golden Rule Seniors Resource Centre</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .archive-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
        }
        .archive-title {
    text-align: center;
    color: #d04a35; /* Using the reddish color from your navigation buttons */
    font-size: 2.2rem;
    margin: 30px 0;
    padding-bottom: 15px;
    border-bottom: 2px solid #d04a35;
    width: 80%;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    font-family: Arial, sans-serif;
    position: relative;
}

.archive-title::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 4px;
    background-color: #4392bb; /* Using the blue color from your logo */
    border-radius: 2px;
}
        
        .event-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 300px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .event-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .event-details {
            padding: 15px;
        }
        
        .event-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .event-date {
            color: #666;
            margin-bottom: 10px;
        }
        
        .event-description {
            margin-bottom: 15px;
            color: #333;
        }
        
        .view-button {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 10px;
        }
        
        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        
        .pagination a.active {
            background-color: #4caf50;
            color: white;
        }
        
        .no-events {
            text-align: center;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            margin: 20px;
        }
    </style>
</head>
<body>
    
    <?php include './header.php'; ?>
    
    <main>
        <h2 class="archive-title">Past Events Archive</h2>
        
        <?php if (count($past_events) > 0): ?>
            <div class="archive-container">
                <?php foreach ($past_events as $event): ?>
                    <div class="event-card">
                        <?php if (!empty($event['image'])): ?>
                            <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php else: ?>
                            <img src="../assets/images/default-image.webp" alt="Default Event Image">
                        <?php endif; ?>
                        
                        <div class="event-details">
                            <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
                            
                            <div class="event-date">
                                <strong>Date:</strong> <?= date('F j, Y', strtotime($event['start_time'])) ?><br>
                                <strong>Time:</strong> <?= date('g:i A', strtotime($event['start_time'])) ?> - 
                                <?= date('g:i A', strtotime($event['end_time'])) ?>
                            </div>
                            
                            <p class="event-description">
                                <?= strlen($event['description']) > 100 ? 
                                    htmlspecialchars(substr($event['description'], 0, 100)) . '...' : 
                                    htmlspecialchars($event['description']) ?>
                            </p>
                            
                            <!-- <a href="event_details.php?id=<?= $event['id'] ?>" class="view-button">View Details</a> -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?= $current_page - 1 ?>">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" <?= $i == $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?= $current_page + 1 ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="no-events">
                <h3>No past events found</h3>
                <p>Check back later for archived events.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
<?php include './footer.php'; ?>
<?php $conn = null; // Close the connection ?>