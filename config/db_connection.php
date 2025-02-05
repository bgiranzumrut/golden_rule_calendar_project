<?php
require_once __DIR__ . '/../vendor/autoload.php';
$url = parse_url(getenv('JAWSDB_URL'));


// Database connection or other setup...

// Database configuration
$host = 'sp6xl8zoyvbumaa2.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$db_name = 'xlpvdqhpe4a2j0ce'; // Replace with your database name
$port = $url['3306'];
$username = 'p18xtaa8049zrg2f';                 // Replace with your database username
$password = 'pbtoxgvurznenfy8';                     // Replace with your database password

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
