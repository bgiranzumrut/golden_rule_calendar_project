<?php
// Include configuration
include_once '../config/db_connection.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Calendar includes its own dependencies
// Adjust path as needed
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
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        nav .search-bar {
            display: flex;
            align-items: center;
        }
        nav input[type="text"] {
            padding: 0.5rem;
            border: none;
            border-radius: 5px;
        }
        nav button {
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        nav button:hover {
            background-color: #777;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        main {
            padding: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div>
            <a href="index.php">Home</a>
            <a href="../views/user_registration.php">Register</a>
            <a href="login.php">Login</a>
            <a href="about.php">About</a>
            <a href="gallery.php">Gallery</a>
        </div>
        <div class="search-bar">
            <form method="GET" action="search.php">
                <input type="text" name="query" placeholder="Search events..." required>
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        

        <!-- Calendar Integration -->
        <?php include_once '../views/calendar.php';  ?>

    </main>

    <!-- Footer -->
    <footer>
        <p>Contact Information: 204 306 1114 | goldenrule@swsrc | 625 Osborne Street</p>
    </footer>

</body>
</html>
