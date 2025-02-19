<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        .date-time-pair {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-date {
            color: red;
            cursor: pointer;
            margin-left: 10px;
        }
        #addMoreDates {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 0;
        }
        #addMoreDates:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Create New Event</h1>
    <form method="POST" action="../controllers/event_controller.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">

        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Event Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <div id="dates-container">
            <div class="date-time-pair">
                <label for="start_time_0">Start Time:</label>
                <input type="datetime-local" id="start_time_0" name="start_time[]" required><br><br>

                <label for="end_time_0">End Time:</label>
                <input type="datetime-local" id="end_time_0" name="end_time[]" required>
            </div>
        </div>

        <button type="button" id="addMoreDates">+ Add Another Date</button><br><br>

        <label for="image">Event Image:</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>

        <button type="submit">Create Event</button>
    </form>

    <script>
        let dateCount = 1;

        document.getElementById('addMoreDates').addEventListener('click', function() {
            const container = document.getElementById('dates-container');
            const newDatePair = document.createElement('div');
            newDatePair.className = 'date-time-pair';

            newDatePair.innerHTML = `
                <label for="start_time_${dateCount}">Start Time:</label>
                <input type="datetime-local" id="start_time_${dateCount}" name="start_time[]" required>
                <br><br>
                <label for="end_time_${dateCount}">End Time:</label>
                <input type="datetime-local" id="end_time_${dateCount}" name="end_time[]" required>
                <span class="remove-date" onclick="this.parentElement.remove()">Remove</span>
            `;

            container.appendChild(newDatePair);
            dateCount++;
        });
    </script>
</body>
</html>