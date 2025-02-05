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
    <style>
        :root {
            --event-colors:
                rgb(203, 68, 47),    /* Red */
                rgb(64, 114, 151),   /* Blue */
                rgb(40, 167, 69),    /* Green */
                rgb(255, 193, 7),    /* Yellow */
                rgb(23, 162, 184),   /* Teal */
                rgb(220, 53, 69),    /* Crimson */
                rgb(128, 0, 128)     /* Purple */
        }

        /* Existing styles from original file */
        /* ... (keep all previous CSS from the original file) */

        /* Responsive Event Line Styles */
        @media (max-width: 768px) {
            .calendar-table td {
                position: relative;
            }

            .events-container {
                display: flex;
                flex-direction: column;
                gap: 4px;
                height: 100%;
                overflow: hidden;
            }

            .event {
                display: none; /* Hide original events */
            }

            .event-line-container {
                display: flex;
                flex-direction: column;
                gap: 4px;
                height: 100%;
                width: 100%;
            }

            .event-line {
                width: 100%;
                height: 8px;
                cursor: pointer;
                transition: opacity 0.3s;
            }

            .event-line:hover {
                opacity: 0.7;
            }
        }

        /* Event Modal Styles */
        #eventModal .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }

        #eventModal .modal-content {
            border-left: 8px solid;
        }
    </style>
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
                                                    <!-- Original Event Display -->
                                                    <div class="event" tabindex="0">
                                                        <div class="event-time">
                                                            <?php echo date('g:i A', strtotime($event['start_time'])); ?>
                                                        </div>
                                                        <a href="../controllers/registrationController.php?action=showRegistrationForm&event_id=<?php echo $event['id']; ?>">
                                                            <div class="event-title">
                                                                <?php echo htmlspecialchars($event['title']); ?>
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>

                                                <!-- Responsive Event Lines -->
                                                <div class="event-line-container d-none d-md-flex">
                                                    <?php foreach ($eventsByDate[$week[$i]] as $index => $event): ?>
                                                        <div
                                                            class="event-line"
                                                            style="background-color: var(--event-colors, <?php echo $index % 7; ?>)"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#eventModal"
                                                            data-event-title="<?php echo htmlspecialchars($event['title']); ?>"
                                                            data-event-time="<?php echo date('g:i A', strtotime($event['start_time'])); ?>"
                                                            data-event-id="<?php echo $event['id']; ?>"
                                                        ></div>
                                                    <?php endforeach; ?>
                                                </div>
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

    <!-- Event Modal (Keep existing modal structure) -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="eventModalTime" class="fw-bold"></p>
                    <p id="eventModalDescription"></p>
                </div>
                <div class="modal-footer">
                    <a href="#" id="eventRegistrationLink" class="btn btn-primary">Register</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventModal = document.getElementById('eventModal');
            const modalTitle = document.getElementById('eventModalTitle');
            const modalTime = document.getElementById('eventModalTime');
            const registrationLink = document.getElementById('eventRegistrationLink');

            eventModal.addEventListener('show.bs.modal', function (event) {
                const eventTrigger = event.relatedTarget;
                const title = eventTrigger.getAttribute('data-event-title');
                const time = eventTrigger.getAttribute('data-event-time');
                const eventId = eventTrigger.getAttribute('data-event-id');

                modalTitle.textContent = title;
                modalTime.textContent = time;

                // Set registration link dynamically
                registrationLink.href = `../controllers/registrationController.php?action=showRegistrationForm&event_id=${eventId}`;
            });
        });
    </script>
</body>
</html>

