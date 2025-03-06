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
        /* General Page Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f5f0; /* Matches other pages */
}

/* Navigation Bar */
.nav-bar {
    background-color: #c04b3e; /* Matches button color */
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    font-size: 1rem;
}

.nav-bar a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
    font-size: 1rem;
    transition: opacity 0.3s;
}

.nav-bar a:hover {
    opacity: 0.8;
}

/* Container */
.container {
    padding: 20px;
    max-width: 1200px;
    margin: 20px auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Dashboard Title */
h1 {
    font-size: 1.8rem;
    color: #c04b3e; /* Matches other headings */
    text-align: center;
    margin-bottom: 20px;
}

/* Section Filter */
.section-filter {
    margin: 20px 0;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
}

.section-filter label {
    font-size: 1rem;
    margin-right: 10px;
}

.section-filter select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
    min-width: 220px;
}

/* Iframe Container */
.iframe-container {
    width: 100%;
    height: calc(100vh - 250px);
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-top: 20px;
}

iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-bar {
        flex-direction: column;
        text-align: center;
    }

    .nav-bar a {
        margin: 5px 0;
    }

    .section-filter {
        flex-direction: column;
    }

    .section-filter label {
        margin-bottom: 5px;
    }

    .iframe-container {
        height: 60vh;
    }
}

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-bar">
        <span>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        <div>
            <a href="admin_gallery.php">Gallery</a>
            <a href="users_list.php">View Users</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- Container -->
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Section Selector -->
        <div class="section-filter">
            <label for="section-select">View Section:</label>
            <select id="section-select" onchange="loadSection()">
                <option value="manage_events.php">Manage Events</option>
                <option value="manage_users.php">Manage Users</option>
                <option value="manage_admins.php">Manage Admin Users</option>
            </select>
        </div>

        <!-- Iframe Container -->
        <div class="iframe-container">
            <iframe id="content-frame" src="manage_events.php"></iframe>
        </div>
    </div>

    <!-- JavaScript -->
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