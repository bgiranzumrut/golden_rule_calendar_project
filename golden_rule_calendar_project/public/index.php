<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Rule Calendar</title>
    <link rel="stylesheet" href="assets/css/mainpage.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div>
            <a href="index.php">Home</a>
            <a href="../views/user_registration.php">Register</a>
            <a href="login.php">Login</a>
            <a href="about.php">About</a>
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
        <h1>Welcome to the Golden Rule Calendar</h1>
        <p>Explore and manage events effortlessly using the interactive calendar below.</p>

        <?php include 'views/calendar.php'; ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>Contact Information: 204 306 1114 | goldenrule@swsrc | 625 Osborne Street</p>
    </footer>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
