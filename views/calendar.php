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
// Organize events by date and sort by time
$eventsByDate = [];
foreach ($events as $event) {
    $date = date('Y-m-d', strtotime($event['start_time']));
    $eventsByDate[$date][] = $event;
}

// Sort events within each date by start time
foreach ($eventsByDate as &$dayEvents) {
    usort($dayEvents, function($a, $b) {
        return strtotime($a['start_time']) - strtotime($b['start_time']);
    });
}
unset($dayEvents); // Break the reference

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Keep existing HTML structure -->
    <!-- Modify the events container in the calendar grid -->
    <div class="calendar-grid">
        <div class="table-responsive">
            <table class="calendar-table table table-bordered" role="grid" aria-labelledby="current-month">
                <tbody>
                    <?php foreach ($filteredCalendar as $week): ?>
                        <tr>
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <td class="calendar-cell">
                                    <?php if ($week[$i]): ?>
                                        <div class="date-header">
                                            <?php echo date('M j', strtotime($week[$i])); ?>
                                        </div>
                                        <div class="events-container">
    <?php if (isset($eventsByDate[$week[$i]])): ?>
        <?php foreach ($eventsByDate[$week[$i]] as $index => $event): ?>
            <!-- Event Display -->
            <div class="event" tabindex="0">
    <div class="event-time">
        <?php echo date('g:i A', strtotime($event['start_time'])); // Display correct start time ?>
    </div>
    <div class="event-title" onclick="openRegistrationModal(<?php echo $event['id']; ?>)">
        <?php echo htmlspecialchars($event['title']); ?>
    </div>
</div>

<!-- Mobile-friendly short name display -->
<div class="event-line d-block d-md-none"
    data-bs-toggle="modal"
    data-bs-target="#eventModal"
    data-event-id="<?php echo $event['id']; ?>"
    data-event-title="<?php echo htmlspecialchars($event['title']); ?>"
    data-event-time="<?php echo date('g:i A', strtotime($event['start_time'])); ?>">

    <span class="event-short-name">
        <?php echo htmlspecialchars($event['short_name'] ?? $event['title']); ?>
    </span>
</div>


            <!-- Responsive Event Line (Corrected Placement) -->
            <?php error_log("Event ID: " . $event['id'] . " Short Name: " . ($event['short_name'] ?? 'NULL')); ?>






        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-muted">No Events</div>
    <?php endif; ?>
</div>
/div>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

