<?php
require_once 'config/db_connection.php';
require_once 'models/event.php';

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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <style>
        /* Custom Properties */
        :root {
            --primary-red: rgb(203, 68, 47);
            --primary-blue: rgb(64, 114, 151);
            --primary-cream: rgb(245, 240, 230);
        }

        /* Base styles */
        body {
            background-color: var(--primary-cream);
            font-family: Arial, sans-serif;
        }

        /* Calendar Container */
        .calendar-wrapper {
            width: 100%;
            margin: 2rem auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Calendar Navigation */
        .calendar-nav {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
        }

        /* Calendar Table */
        .calendar-table {
            width: 100%;
            table-layout: fixed;
        }

        .calendar-table th {
            background-color: var(--primary-blue);
            color: white;
            padding: 0.75rem;
            text-align: center;
            font-weight: 600;
        }

        .calendar-table td {
            height: auto;
            min-height: 120px;
            vertical-align: top;
            padding: 0.5rem !important;
            position: relative;
        }

        /* Event Styling */
        .event {
            display: flex;
            align-items: flex-start;
            background: #f8f9fa;
            border-left: 4px solid var(--primary-red);
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
        }

        .event-time {
            color: var(--primary-red);
            font-weight: 700;
            min-width: 75px;
            margin-right: 0.5rem;
        }

        .event-title {
            color: var(--primary-blue);
            font-weight: 600;
        }

        /* List View (for mobile) */
        .list-view {
            display: none;
            padding: 1rem;
        }

        .list-view .event {
            margin-bottom: 1rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .calendar-table th {
                font-size: 0.9rem;
                padding: 0.5rem;
            }

            .event {
                flex-direction: column;
            }

            .event-time {
                margin-bottom: 0.25rem;
            }
        }

        @media (max-width: 576px) {
            .calendar-grid {
                display: none;
            }

            .list-view {
                display: block;
            }

            .view-toggle {
                display: block;
            }

            .calendar-table th {
                font-size: 0.8rem;
                padding: 0.25rem;
            }

            .date-header {
                font-size: 0.9rem;
            }
        }

        /* Accessibility Improvements */
        .btn:focus,
        .event:focus-within {
            outline: 3px solid var(--primary-red);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }

        /* Print Styles */
        @media print {
            .calendar-wrapper {
                box-shadow: none;
            }

            .view-toggle,
            .btn-toggle-view {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Header / Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="uploads/logo.png" alt="Golden Rule Calendar Logo" class="navbar-logo me-3">
            <span class="brand-title">Golden Rule Calendar</span>
        </a>
        <!-- Navbar toggler for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">Gallery</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Admin Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="calendar-wrapper">
        <!-- Calendar Navigation -->
        <div class="calendar-nav">
            <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>"
               class="btn btn-outline-primary"
               aria-label="Previous Month">
                &laquo; Previous
            </a>
            <h2 class="h4 mb-0" id="current-month">
                <?php echo date('F Y', strtotime("$year-$month-01")); ?>
            </h2>
            <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>"
               class="btn btn-outline-primary"
               aria-label="Next Month">
                Next &raquo;
            </a>
        </div>

        <!-- View Toggle Button -->
        <div class="d-flex justify-content-end p-2 view-toggle">
            <button class="btn btn-link btn-toggle-view" onclick="toggleView()">
                Switch View
            </button>
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
                                            <div class="date-header">
                                                <?php echo date('M j', strtotime($week[$i])); ?>
                                            </div>
                                            <div class="events-container">
                                                <?php if (isset($eventsByDate[$week[$i]])): ?>
                                                    <?php foreach ($eventsByDate[$week[$i]] as $event): ?>
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

        <!-- List View (for mobile) -->
        <div class="list-view">
            <?php
            $currentDate = null;
            foreach ($events as $event):
                $eventDate = date('Y-m-d', strtotime($event['start_time']));
                if ($eventDate !== $currentDate):
                    $currentDate = $eventDate;
            ?>
                <h3 class="h5 mt-3 mb-2">
                    <?php echo date('l, F j', strtotime($eventDate)); ?>
                </h3>
            <?php endif; ?>
                <div class="event" data-title-length="${title.length > 20 ? 'long' : 'regular'}">
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
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // View Toggle Function
        function toggleView() {
            const gridView = document.querySelector('.calendar-grid');
            const listView = document.querySelector('.list-view');
            const button = document.querySelector('.btn-toggle-view');

            if (gridView.style.display === 'none') {
                gridView.style.display = 'block';
                listView.style.display = 'none';
                button.textContent = 'Switch to List View';
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                button.textContent = 'Switch to Grid View';
            }
        }

        // Responsive Height Adjustment
        function adjustCellHeight() {
    const cells = document.querySelectorAll('.calendar-cell');
    let cellHeight = 160; // Reduced default height

    if (window.innerWidth <= 768) {
        cellHeight = 140;
    }
    if (window.innerWidth <= 576) {
        cellHeight = 120;
    }

    cells.forEach(cell => {
        cell.style.height = `${cellHeight}px`;
    });
}
        // Initialize and add event listeners
        document.addEventListener('DOMContentLoaded', function() {
            adjustCellHeight();
            window.addEventListener('resize', adjustCellHeight);
        });
    </script>
</body>
</html>