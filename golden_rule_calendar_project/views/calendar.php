<?php
include '../config/db_connection.php';
include '../models/event.php';

$eventModel = new Event($conn);

// Fetch all events from the database
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

<table border="1" style="width: 100%; text-align: center; border-collapse: collapse;">
    <thead>
        <tr>
            <?php foreach ($daysOfWeek as $day): ?>
                <th style="padding: 10px; background-color: #f0f0f0;"><?php echo $day; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $rowOpen = false;
        foreach ($dates as $date => $day) {
            // Start a new row for Monday
            if ($day === 'Monday') {
                if ($rowOpen) {
                    echo '</tr>';
                }
                echo '<tr>';
                $rowOpen = true;
            }

            // Display cell content
            echo '<td style="padding: 10px; vertical-align: top;">';
            echo '<strong>' . date('M j', strtotime($date)) . '</strong><br>';
            if (isset($eventsByDate[$date])) {
                foreach ($eventsByDate[$date] as $event) {
                    echo '<div style="margin: 5px 0; font-size: 0.9em; text-align: left;">';
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
        // Close the last row
        if ($rowOpen) {
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
