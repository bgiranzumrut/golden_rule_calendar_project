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
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            vertical-align: top;
            height: 100px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-size: 1.2rem;
        }
        .day-header {
            text-align: right;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .event {
            margin: 5px 0;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .event-title {
            font-weight: bold;
        }
        .event-time {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- Navigation Links -->
    <h3>
        <!-- Previous Button -->
        <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">&laquo; Previous</a>

        <!-- Current Month Display -->
        <?php echo date('F Y', strtotime("$year-$month-01")); ?>

        <!-- Next Button -->
        <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">Next &raquo;</a>
    </h3>

    <!-- Calendar Table -->
    <table>
        <thead>
            <tr>
                <?php foreach ($daysOfWeek as $day): ?>
                    <th><?php echo $day; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredCalendar as $week): ?>
                <tr>
                    <?php for ($i = 0; $i < 5; $i++): // Display only weekdays ?>
                        <td>
                            <?php if ($week[$i]): ?>
                                <!-- Display Day Number -->
                                <div class="day-header">
                                    <?php echo date('M j', strtotime($week[$i])); ?>
                                </div>

                                <!-- Display Events -->
                                <?php if (isset($eventsByDate[$week[$i]])): ?>
                                    <?php foreach ($eventsByDate[$week[$i]] as $event): ?>
                                        <div class="event">
                                            <div class="event-time"><?php echo date('g:i A', strtotime($event['start_time'])); ?></div>
                                            <div class="event-title">
    <a href="../controllers/registrationController.php?action=showRegistrationForm&event_id=<?php echo $event['id']; ?>" target="_blank">
        <?php echo htmlspecialchars($event['title']); ?>
    </a>
</div>

                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="event">No Events</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Empty Day Slot -->
                                &nbsp;
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
