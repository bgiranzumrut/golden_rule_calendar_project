<?php
require_once __DIR__ . '/../vendor/autoload.php';


// Database connection or other setup...

// Database configuration
$host = 'localhost';
$db_name = 'golden_rules_calendar'; // Replace with your database name
$username = 'root';                 // Replace with your database username
$password = '';                     // Replace with your database password

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Uncomment the line below to confirm successful connection during testing
    // echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
