<?php

error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the page
require_once 'config/db_connection.php';
require_once 'models/event.php';
require_once 'config/config.php'; // Include the config file to use getBasePath function

use Models\Event;

$eventModel = new Event($conn);

// Default current month/year
$currentMonth = (int) date('m');
$currentYear = (int) date('Y');

// Redirect to current month/year if query parameters are missing
if (!isset($_GET['month']) || !isset($_GET['year'])) {
    header("Location: ?month=$currentMonth&year=$currentYear");
    exit();
}

// Get selected month/year from query parameters
$month = (int) $_GET['month'];
$year = (int) $_GET['year'];

// Calculate previous & next months
$prevMonth = $month == 1 ? 12 : $month - 1;
$prevYear = $month == 1 ? $year - 1 : $year;

$nextMonth = $month == 12 ? 1 : $month + 1;
$nextYear = $month == 12 ? $year + 1 : $year;


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

// Sort events within each date by start time
foreach ($eventsByDate as &$dayEvents) {
    usort($dayEvents, fn($a, $b) => strtotime($a['start_time']) - strtotime($b['start_time']));
}
unset($dayEvents); // Break reference

// Calendar setup
$firstDayOfMonth = date('N', strtotime($startDate)); // 1 = Monday, ..., 7 = Sunday
$totalDays = date('t', strtotime($startDate));
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// Prepare calendar
$calendar = [];
$week = array_fill(0, 7, null);
$dayCounter = 1;

// Fill first week
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

// Remove empty weeks (only weekends)
$filteredCalendar = array_filter($calendar, function ($week) {
    return array_filter(array_slice($week, 0, 5)); // Only keep if Monday-Friday has data
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Rule Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo getBasePath(); ?>styles/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo getBasePath(); ?>styles/modal_styles.css">

</head>

<body>
    <?php include __DIR__ . '/public/header.php'; ?>

    <div class="calendar-wrapper">
        <!-- Calendar Navigation -->
        <div class="calendar-nav d-flex flex-wrap justify-content-center align-items-center">
            <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-outline-primary"
                aria-label="Previous Month">
                &laquo; Previous
            </a>
            <h2 class="h4 mb-0 text-center" id="current-month">
                <?php echo date('F Y', strtotime("$year-$month-01")); ?>
            </h2>
            <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-outline-primary"
                aria-label="Next Month">
                Next &raquo;
            </a>
        </div>

        <!-- Calendar Grid View -->
        <div class="calendar-grid">
            <div class="table-responsive">
                <table class="calendar-table table table-bordered" role="grid" aria-labelledby="current-month">
                    <thead>
                        <tr>
                            <?php foreach ($daysOfWeek as $day): ?>
                                <th scope="col"><?php echo $day; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filteredCalendar as $week): ?>
                            <tr>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <td class="calendar-cell">
                                        <?php if ($week[$i]): ?>
                                            <div class="date-header"><?php echo date('M j', strtotime($week[$i])); ?></div>
                                            <div class="events-container">
                                                <?php if (isset($eventsByDate[$week[$i]])): ?>
                                                    <?php foreach ($eventsByDate[$week[$i]] as $index => $event): ?>
                                                        <div class="event-group">
                                                            <!-- Event Display -->
                                                            <!-- <div class="event" tabindex="0">
                                                                <div class="event-time">
                                                                    <?php echo date('g:i A', strtotime($event['start_time'])); ?></div>
                                                                <a
                                                                    href="<?php echo getBasePath(); ?>controllers/registrationController.php?action=showRegistrationForm&event_id=<?php echo $event['id']; ?>">
                                                                    <div class="event-title">
                                                                        <?php echo htmlspecialchars($event['title']); ?></div>
                                                                </a>
                                                            </div> -->
                                                            <div class="event" tabindex="0">
                                                                <div class="event-time">
                                                                    <?php echo date('g:i A', strtotime($event['start_time'])); ?>
                                                                </div>
                                                                <div class="event-title" onclick="openRegistrationModal(<?php echo $event['id']; ?>)">
                                                                    <?php echo htmlspecialchars($event['title']); ?>
                                                                </div>
                                                            </div>

                                                            <!-- Responsive Event Line -->
                                                            <div class="event-line"
                                                            onclick="openRegistrationModal(<?php echo $event['id']; ?>)">
    <span class="event-short-name"><?php echo htmlspecialchars($event['short_name'] ?? $event['title']); ?></span>
</div>



                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="text-muted">No Events</div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <!-- Event Modal -->



    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/views/modal_form.php'; ?>
    <script src="<?php echo getBasePath(); ?>public/js/modal_script.js"></script>


</body>

</html>
<?php include __DIR__ . '/public/footer.php';?>