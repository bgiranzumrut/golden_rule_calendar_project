<?php
// Correctly include dependencies
require_once '../config/db_connection.php';
require_once '../models/event.php';

use Models\Event; // Import the Event class with namespace

// Instantiate Event class
$eventModel = new Event($conn);

// Fetch all events
$events = $eventModel->fetchAllEvents();

// Organize events by date
$eventsByDate = [];
foreach ($events as $event) {
    $date = date('Y-m-d', strtotime($event['start_time']));
    if (!isset($eventsByDate[$date])) {
        $eventsByDate[$date] = [];
    }
    $eventsByDate[$date][] = $event;
}

// Define calendar structure
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$dates = [
    '2024-12-02' => 'Monday', '2024-12-03' => 'Tuesday', '2024-12-04' => 'Wednesday',
    '2024-12-05' => 'Thursday', '2024-12-06' => 'Friday',
    '2024-12-09' => 'Monday', '2024-12-10' => 'Tuesday', '2024-12-11' => 'Wednesday',
    '2024-12-12' => 'Thursday', '2024-12-13' => 'Friday',
    '2024-12-16' => 'Monday', '2024-12-17' => 'Tuesday', '2024-12-18' => 'Wednesday',
    '2024-12-19' => 'Thursday', '2024-12-20' => 'Friday',
    '2024-12-23' => 'Monday', '2024-12-24' => 'Tuesday', '2024-12-25' => 'Wednesday',
    '2024-12-26' => 'Thursday', '2024-12-27' => 'Friday',
    '2024-12-30' => 'Monday', '2024-12-31' => 'Tuesday'
];
?>
<!-- Calendar HTML structure -->
<table>
    <thead>
        <tr>
            <?php foreach ($daysOfWeek as $day): ?>
                <th><?php echo $day; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $rowOpen = false;
        foreach ($dates as $date => $day) {
            if ($day === 'Monday') {
                if ($rowOpen) {
                    echo '</tr>';
                }
                echo '<tr>';
                $rowOpen = true;
            }

            echo '<td>';
            echo '<strong>' . date('M j', strtotime($date)) . '</strong><br>';
            if (isset($eventsByDate[$date])) {
                foreach ($eventsByDate[$date] as $event) {
                    echo '<div>';
                    echo '<strong>' . htmlspecialchars($event['title']) . '</strong><br>';
                    echo 'Start: ' . date('g:i A', strtotime($event['start_time'])) . '<br>';
                    echo 'End: ' . date('g:i A', strtotime($event['end_time'])) . '<br>';
                    echo 'Details: ' . htmlspecialchars($event['description']) . '<br>';
                    echo '</div>';
                }
            } else {
                echo 'No Events';
            }
            echo '</td>';
        }
        if ($rowOpen) {
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
