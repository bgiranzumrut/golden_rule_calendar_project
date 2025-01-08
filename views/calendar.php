<?php
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event;

$eventModel = new Event($conn);

// Default current month/year
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');

// Redirect to current month/year if query parameters are missing
if (!isset($_GET['month']) || !isset($_GET['year'])) {
    header("Location: ?month=$currentMonth&year=$currentYear");
    exit();
}

// Get selected month/year from query parameters
$month = (int)$_GET['month'];
$year = (int)$_GET['year'];

// Calculate previous month/year
if ($month == 1) {
    $prevMonth = 12;
    $prevYear = $year - 1;
} else {
    $prevMonth = $month - 1;
    $prevYear = $year;
}

// Calculate next month/year
if ($month == 12) {
    $nextMonth = 1;
    $nextYear = $year + 1;
} else {
    $nextMonth = $month + 1;
    $nextYear = $year;
}

// Fetch events for the selected month
$startDate = sprintf("%04d-%02d-01", $year, $month);
$endDate = date("Y-m-t", strtotime($startDate));
$events = $eventModel->fetchEventsByDateRange($startDate, $endDate);

// Organize events by date
$eventsByDate = [];
foreach ($events as $event) {
    $date = date('Y-m-d', strtotime($event['start_time']));
    $eventsByDate[$date][] = $event;
}

// Calendar setup
$firstDayOfMonth = date('N', strtotime($startDate)); // 1 = Monday, ..., 7 = Sunday
$totalDays = date('t', strtotime($startDate)); // Total days in the month
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// Prepare calendar
$calendar = [];
$week = array_fill(0, 7, null); // Initialize a week with 7 days (Mon-Sun)
$dayCounter = 1;

// Fill the first week, considering the offset for the starting weekday
for ($i = $firstDayOfMonth - 1; $i < 7 && $dayCounter <= $totalDays; $i++) {
    $week[$i] = sprintf("%04d-%02d-%02d", $year, $month, $dayCounter++);
}
$calendar[] = $week;

// Fill remaining weeks
while ($dayCounter <= $totalDays) {
    $week = array_fill(0, 7, null);
    for ($i = 0; $i < 7 && $dayCounter <= $totalDays; $i++) {
        $week[$i] = sprintf("%04d-%02d-%02d", $year, $month, $dayCounter++);
    }
    $calendar[] = $week;
}

// Remove rows where all weekdays (Monday to Friday) are empty
$filteredCalendar = array_filter($calendar, function ($week) {
    // Check if all weekday slots (0 to 4) are null
    for ($i = 0; $i < 5; $i++) {
        if ($week[$i] !== null) {
            return true; // Keep this row
        }
    }
    return false; // Exclude this row
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Rule Calendar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom stylesheet -->
    <link href="../styles/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Main Content -->
    <main class="container py-4">
        <div class="calendar-container">
            <!-- Calendar Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" 
                   class="btn btn-outline-primary">&laquo; Previous</a>
                <h3 class="mb-0"><?php echo date('F Y', strtotime("$year-$month-01")); ?></h3>
                <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" 
                   class="btn btn-outline-primary">Next &raquo;</a>
            </div>

            <!-- Calendar Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <?php foreach ($daysOfWeek as $day): ?>
                                <th class="text-center"><?php echo $day; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filteredCalendar as $week): ?>
                            <tr>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <td class="calendar-cell">
                                        <?php if ($week[$i]): ?>
                                            <div class="day-header text-end">
                                                <?php echo date('M j', strtotime($week[$i])); ?>
                                            </div>
                                            <?php if (isset($eventsByDate[$week[$i]])): ?>
                                                <?php foreach ($eventsByDate[$week[$i]] as $event): ?>
                                                    <div class="event rounded">
                                                        <div class="event-time">
                                                            <?php echo date('g:i A', strtotime($event['start_time'])); ?>
                                                        </div>
                                                        <div class="event-title">
                                                            <a href="../controllers/registrationController.php?action=showRegistrationForm&event_id=<?php echo $event['id']; ?>" 
                                                               class="text-decoration-none">
                                                                <?php echo htmlspecialchars($event['title']); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="text-muted small">No Events</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

   
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
