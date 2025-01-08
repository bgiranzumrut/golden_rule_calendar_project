<?php
require_once '../config/db_connection.php';
require_once __DIR__ . '/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Rule Calendar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet">
   
</head>
<body>
    <!-- Header / Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../uploads/logo.png" alt="Golden Rule Calendar Logo" class="navbar-logo me-3">
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



    <!-- Main Content -->
    <main class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php include_once '../views/calendar.php'; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-4 border-top">
    <div class="container-f text-center">
        <p class="mb-0-contact contact-info">
            Contact Information: 
            <a href="tel:2043061114" class="footer-link">204 306 1114</a> | 
            <a href="mailto:goldenrule@swsrc" class="footer-link">goldenrule@swsrc</a> | 
            625 Osborne Street
        </p>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>