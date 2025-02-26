<?php
session_start();
require_once '../config/db_connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-filter {
            margin: 20px 0;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-filter select {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
            min-width: 200px;
        }
        .iframe-container {
            width: 100%;
            height: calc(100vh - 200px);
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <span>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        <a href=" logout.php">Logout</a>
        <a href=" admin_gallery.php">Gallery</a>
        <a href=" users_list.php">View Users</a>
    </nav>

    <div class="container">
        <h1>Admin Dashboard</h1>

        <div class="section-filter">
            <label for="section-select">View Section:</label>
            <select id="section-select" onchange="loadSection()">
                <option value="manage_events.php">Manage Events</option>
                <option value="manage_users.php">Manage Users</option>
                <option value="manage_admins.php">Manage Admin Users</option>
            </select>
        </div>

        <div class="iframe-container">
            <iframe id="content-frame" src="manage_events.php"></iframe>
        </div>
    </div>

    <script>
        function loadSection() {
            const select = document.getElementById('section-select');
            const iframe = document.getElementById('content-frame');
            iframe.src = select.value;
        }

        // Preserve selected section on page reload
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');
            if (section) {
                const select = document.getElementById('section-select');
                if (select.querySelector(`option[value="${section}.php"]`)) {
                    select.value = section + '.php';
                    loadSection();
                }
            }
        });

        // Update URL when section changes
        document.getElementById('section-select').addEventListener('change', function() {
            const section = this.value.replace('.php', '');
            const url = new URL(window.location);
            url.searchParams.set('section', section);
            window.history.pushState({}, '', url);
        });
    </script>
</body>
</html>